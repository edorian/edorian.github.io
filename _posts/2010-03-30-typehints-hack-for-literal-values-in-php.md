---

layout: post
title: Type hints hack for literal Values in PHP
tags: [php]

---

For some years we are able to use "array" and class names as type hints [http://php.net/manual/en/language.oop5.typehinting.php](http://php.net/manual/en/language.oop5.typehinting.php) and since the @internals discussion if we get type hints for literals (int, bool, ...) and if so in what form is going on some time [http://wiki.php.net/rfc/typecheckingweak](http://wiki.php.net/rfc/typecheckingweak) and [http://wiki.php.net/rfc/typecheckingstrictandweak](http://wiki.php.net/rfc/typecheckingstrictandweak) i was really intrigued by a comment point out how you can do it in php 5.2+ TODAY.

{:.prettyprint .linenums .lang-php}
	function typehint($level, $message) {
	    if($level == E_RECOVERABLE_ERROR && preg_match(
			'/^Argument (\d)+ passed to (?:(\w+)::)?(\w+)\(\) must be an instance of (\w+), (\w+) given/', $message, $match)
		) { 
			if($match[4] == $match[5]) {
				return true;
			}
		} 
		return false;
	}
	set_error_handler("typehint");


If you name a "class" in a function definition that isn't know at compline time PHP doesn't complain. Only if you pass in the wrong value it throws an "Catchable fatal error":

> Catchable fatal error: Argument 1 passed to CLASS::FUNCTION() must be an instance of MYCLASS, WHAT_YOU_PUT_IN given.
 
If you now pass in a string in that function it reports it as "string given". Combine that with the regex from above and you get:

> Catchable fatal error: Argument 1 passed to CLASS::FUNCTION() must be an instance of string, string given.

The error might sound stupid but PHP expects a instance of class "string" not a literal string so thats ok.

If you catch all these errors you have now literal type hints in your application!

### GREAT?

Well let's look at the downsides... (apart from "OMG THATS A FUCKING STUPID IDEA!")

It's slow.. really slow. I didn't test it on a real project but for calling a million functions with one and three params the results look like this:

**PHP 5.2.6, one param**

5 Times slower

**PHP 5.2.6, three params**

12 Times slower

**PHP 5.3.1 one param**

30 Times slower

**PHP 5.3.1 three params**

45 Times slower

-----------------

I haven't really looked into WHY it's slower on 5.3 nor tried 5.3.2 or 5.3.99 but i don't think that really matters. (Test script attached if you want to try it yourself).

So for a production environment this method is somewhat unusable (given you just could patch your php if you really want that and don't care about the trouble and not 'reusable' code you will get).

So what else could you do with it... well if you have a good API Documentation you could build a script to put those type hints into your code before you run your test suite... but that also did work before (putting the type checks in the first lines of the method).

I guess it's good for nothing but showing off another way to hack around in PHP. Presumably it's not even forward compatible to the point where PHP will have literal type hints since i guess they will call them "int/bool" and not "integer/boolean".