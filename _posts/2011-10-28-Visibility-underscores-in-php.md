---

layout: post
title: Visibility underscores in PHP
tags: [php, phpcs]

---

Recently i had the chance to discuss a coding-standard with someone. It's way more important to have and follow one than what it contains about i enjoy those discussions and we got to one point that i haven't talked to anyone about for years: Using the _underscore to denote private methods and variables.

Let's have a short look at PHPs recent history.

PHP 5.4 is just around the corner, 5.3 is alive and kicking and our beloved "I'm still stuck with it but it was the first 5.x that really worked well" [PHP 5.2 is not supported any more](http://www.php.net/archive/2011.php#id2011-08-18-1).

PHP 5 gave us proper OO (again) and now that we have namespaces and closures you could start calling PHP a proper language ;)

We are deep into 5.x now and it's time to get rid of your PHP 4 legacy. The last release was 3 years ago, the number of people that still admit to using it in production dropped below the care ratio and all major frameworks and libs migrated.

## Why we had underscores

* and we needed to walk 20 miles though snow to start the interpreter but thats another story*

So just to remind anyone that what was right some day isn't right the other day (many years later).

**PHP 4**

{:.prettyprint .lang-php}
	class Foo {
		var $_a;
		var $_b;
		function myStuff() {}
		function _myHelper() {}
	}

Back in the day we did this to say "Don't touch my privates" because we had no other way of doing that.. well except @phpdoc and ASCII art.

It worked kinda sorta well and it did its job good enough that we didn't mind.

Then came **PHP 5**

{:.prettyprint .lang-php}
	class Foo {
		private $a;
		private $b;
		public function myStuff() {}
		protected function myHelper() {}
	}


and we could get rid of all the underscores.

Even with PHP 4 people didn't use them for variables all the time as you didn't expect people to fiddle around in your members anyways but now **&lsquo;they' gave us visibility** and we got a clear way of expressing what one was supposed to call and that was considered an implementation detail.

## All there is left is redundancy

Of course there are older projects that transitioned from PHP 4 and while migrating one had more issues than fiddling with underscores and thats fine. I'm not suggesting to change everything this instant! There are BC reasons to keep names and so on.

**Projects starting nowadays shouldn't adopt this practice!**

It doesn't offer any benefit and should be considered legacy that once was useful but is superfluous and redundant nowadays.

Code like:

	private _myHelper() {}

just tells me twice that it's a private method and if i have to choose I'll gladly take the one that reads better and is commonly used throughout the language.

Modern IDEs are able to only show me the public API anyways and while working from within the class it doesn't really matter all that much if i call another private or public.

From a refactoring standpoint it doesn't really matter all that much but it makes promoting a function to public easier when i don't have to change the name to do that. Although even search &amp; replace can usually take care of that.

## Conclusion

I just don't see any reason to keep this practice around in PHP 5+ so my suggestion is to remove `PEAR_Sniffs_NamingConventions_ValidVariableNameSniff` from your phpcs.xml for any upcoming projects.
