---

layout: post
title: Stackframes! Stackframes everywhere!
tags: [php, thankyou]

---

## Exception thrown without a stack frame in Unknown on line 0

Also know as: **The best way to start a day as a php developer.**

This little piece of code, embedded in a big application, easily could send you of debugging for an hour or more:

{:.prettyprint .lang-php}
	class Doom {
	
	    public function __destruct() {
	        throw new Exception;
	    }
	}
	
	$x = new Doom();


Up until now it just produced the nice: **PHP Fatal error:  Exception thrown without a stack frame in Unknown on line **

# Behold of changes

But rejoice fellow php developers. Those days are over!

With the [release of the amazing php 5.3.6](http://www.php.net/archive/2011.php#id2011-03-17-1) that [fixed an amazing number of bugs and annoyances](http://www.php.net/ChangeLog-5.php#5.3.6) and even brought us nice new features like [The dom goodie](http://gooh.posterous.com/the-dom-goodie-in-php-536) there is one more stackframe for us.

The above code now produces:

{:.prettyprint}
	Fatal error: Uncaught exception 'Exception' in /home/edo/throw.php:5
	Stack trace:
	#0 [internal function]: Doom->__destruct()
	#1 {main}


And let me just say: **Thank you very much!**
