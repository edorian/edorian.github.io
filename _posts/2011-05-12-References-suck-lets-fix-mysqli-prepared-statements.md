---

layout: post
title: References suck! - Let's fix MySqli prepared statements!
tags: [mysql, php, references]

---

They are [a pain to work with](http://schlueters.de/blog/archives/141-References-and-foreach.html). PHP uses [copy on write](www.trl.ibm.com/people/mich/pub/200901_popl2009phpsem.pdf) and `$x = $y = $z = str_repeat("a", 10000);` only stores 10.000 Chars not 30.000 so there is no performance gain in 99.999% of the cases.

Even so not every PHP Developers knows WHY we don't use references pretty much every core function and every somewhat modern framework avoids them so people adapted this best practice. The leftovers in the PHP core, like sort() or str_replace(), are exceptions to the rule.

So if the common consensus is, or at least 'should be', that **we** [**should not use references**](http://schlueters.de/blog/archives/125-Do-not-use-PHP-references.html) then maybe we should start looking for places where they hurt and how we could fix them?

# MySqli prepared statements

I really like [Prepared statements](http://en.wikipedia.org/wiki/SQL_injection#Parameterized_statements). I found them to be the 'default secure' way of creating queries. (Yes, I'm aware that they where not designed as a security feature but it helps so lets just do that ok? :) ) - When using normal queries you have to remember to use escaping every single time in order to be save, with prepared statements you just have to remember to use them at all. Thats a lot easier and way less error prone.

I'm not going into detail about the why and when not to use them for now. I'm more interested in talking about the one thing that, imho, **makes it REALLY hard to sell this way of querying to PHP developers**.

# The MySqli_STMT API

[Just look at the API](http://de.php.net/manual/en/class.mysqli-stmt.php) and let's compare that to a normal [MySqli Query](http://de.php.net/manual/en/mysqli.query.php)

*Samples mostly copied from the PHP manual*

**A normal query**

{:.prettyprint .lang-php}
	$query = "SELECT Name, CountryCode FROM City WHERE id = 1";
	$result = $mysqli->query($query);
	while($array = $result->fetch_array()) {
	    // do stuff with 
	    // array("name" => "...", "CountryCode" => "...");
	}


**The same thing with a prepared statement**

{:.prettyprint .lang-php}
	$query = "SELECT Name, CountryCode FROM City WHERE id = ?";
	$statement = $mysqli->prepare($query);
	$id = 1;
	$statement->bind_param('i', $id);
	$statement->bind_result($name, $countryCode); // Look ma, i can create variables out of thin air!
	while($statement->fetch()) {
	    // you thing we are done here?
	    // we just have two variables, not an associative array!
	    $array = array("Name" => $name, "CountryCode" => $countryCode);
	    // ^^ hard-coding the names? No.. that can't be right!
	
	    // ------
	    // There is another way!
	    // ------
	
	    $fieldnames = $statement->result_metadata()->fetch_fields();
	    $fieldsAsArray = array();
	    foreach($fieldnames as $field) {
	        $fieldsAsArray[] = $field->name;
	    }
	
	    $array = array_combine($fieldsAsArray, array($name, $countryCode));
	    // Writing this makes me quite sick.. i hope you can stand reading it
	}



**Counting lines:**

Normal query: **3 lines**

Prepared statement: **6 lines to get to the while** and **another 6 lines to get a 'proper' array**

So even without the comments the prepared statement way of doing things just is A LOT longer and doesn't look very nice. Most IDEs and PHP_CodeSniffer will also bug you about an '**use of undeclared variable**' in the `->bind_result` line even so that's technically correct. But I'd say it just look very unfamiliar to many PHP Programmers.

But who uses that database directly anyways? Well if you don't use an ORM you usually use some sort of DB Abstraction or [write your own](http://c2.com/cgi/wiki?NotInventedHere). While you might be able to use mysql(i)_* functions natively I'd like to make the case that:

# You can NOT use MySqli_STMT without abstraction

At least i can't. **You can make a case for NOT NEEDING THAT ARRAY ANYMORE** and thats ok if all your classes don't deal with those types of arrays but PHP is (or at least was for many years) hash map based (array) programming, the C code and the userland PHP code. Having 1 or 2 dimensional arrays with your data can work out quite well for many use cases and more OOP-Style data structures can be [YAGNI](http://en.wikipedia.org/wiki/You_ain't_gonna_need_it)'d away if you know when you know when you can get away with using that data structure.

For example they can be a pretty nice way of getting rows from a database. (No, stdClass doesn't count as an OOP-Array, [] or -> access isn't enough of a difference ;) ).

# So let's write a simple abstraction layer

I challenge you do look into the Zend_MySqli Driver and examine their statement implementation. It's vomit inducing and it's not even their fault. I've spend quite some time talking to people if there would be a better way to achieve their goals and nobody could come up with anything well anything exception questions like **'oh my god why/what on earth are you doing there $randomSwearWords'**

**My very simple requirements**

I want a `->fetchAll($query, array $params)` function that returns a 2D array with assoc arrays for each row.

**Thats it! Let's write that!**

This is quite a lot of pretty ugly code so I'm skipping some steps like creating the connection it's self.

**Our ->fetchAll function in one big pile of code**

{:.prettyprint .lang-php}
	public function fetchAll($query, $arguments) {
	
	    $statement = $this->mysqli->prepare($query);
	    // Skipped error handling for readability
	    $argumentCount = count($arguments);
	    if($statement->param_count !== $argumentCount) {
	        // fail
	    }
	
	    // Now we need to call 'bind_param'
	    // 'bind_param' is a procedure and the only way to call a procedure with a variable number of arguments is call_user_func_array
	    // BUT WE NEED TO CALL IT WITH REFERENCES!
	    $callArgs = array();
	    foreach($arguments as $index => $arg) {
	        $callArgs[$index] = &$arguments[$index]; // :(
	    }
	
	    // Assume all parameters to be strings, works quite well apparently
	    array_unshift($callArgs, str_repeat("s", count($arguments));
	
	    // Now bind the parameters
	    call_user_func_array(array($statement, 'bind_param'), $callArgs);
	
	    // Now we can execute the statement, finally
	    $statement->execute();
	    // again, error handling skipped
	
	    // Now the RESULTS!
	
	    // The fieldnames
	    $fields = $statement->result_metadata()->fetch_fields();
	    $fieldnames = array();
	    foreach($fields as $field) {
	        $fieldnames[] = $field->name;
	    }
	
	    // Now we need a CONTAINER where the results get fetched into!
	    $resultRow = array_fill(0, count($fieldnames), null);
	    // Oh and btw.. THOSE FIELDS NEED! TO BE REFERENCES!
	    foreach($resultRow as $index => &$value) {
	        // ^^ Don't try this at home! foreach & references are evil!
	        $resultRow[$index] = &$value;
	    }
	    call_user_func_array(array($statement, 'bind_result'), $resultRow);
	
	    // All preparations done! Let's fetch!
	    $result = array();
	    while($statement->fetch()) {
	
	        // THIS IS WHERE IT GETS REALLY UGLY!
	
	        // we need to dereference the result values since we don't want to return reference to the user
	        // Doing so would break in very hard to debug ways!
	
	        $deref = array();
	        foreach($resultRow as $value) {
	            $deref[] = $value;
	            // This is not a copy on write, this hurts!
	        }
	        $result[] = array_combine($fieldnames, $deref);
	    }
	
	    // You are still reading this? Thanks :)
	    return $result;
	    // Done!
	}


# This is a massive performance hit

Fetching data this way uses a lot(!) more memory than it should and from heavy production use I've benchmarked that for the average case around **20% of all query execution time is spend dereferencing the return values**. That of course heavily depends on the queries and the amount of data returned but **thats time i waste pandering an API just to make it somewhat usable!**

**Again:**

20% of the time it takes to run `$result = $db->fetchAll("...", ...);` is spend **moving around stuff in PHP memory for no reason what-so-ever**.

I hope after reading this you can agree that this needs to be fixed!

# Who to fix it?

**The 'easiest' way:**

	$statement->fetchArray()

That would take away the need to 'bind_result' and while the other stuff isn't really nice I can live with quite well. It doesn't cost that much performance. **Being able to pass the parameters directly to ->fetchArray would be even nicer!** but maybe a rather strange API.. not to sure :)

**Maybe the proper way?**

Give me a way to get an MySqli_Result from a prepared statement execution so i can use its fetch methods.

**Use PDO?**

PDO doesn't do 'real' prepared statements but client side escaping/expanding and that creates a whole lot more problems than I'm trying to solve. No thanks, I'd rather do the escaping myself and use sprintf before going with PDO.

# Yes, No, Maybe?

Do you know of a better of doing so?

I'd be pretty interested and I guess the Zend Framework guys would be too. So please share!

For a complete, working implementation of this sample ether contact me (for my ~600 loc implementation thats a little cleaner and has more features) or look at the Zend Framework DB package. The *really* ugly parts are the same :)

**And on your way out take a cookie for reading the whole post!**
