---

layout: post
title: Textual code coverage information for PHPUnit
tags: [php, phpqatools, phpunit]

---

Three weeks ago PHPUnit 3.6 was released and it has a little new feature you might have missed until now.

**PHPUnit can now show you code coverage information on the command line**

	phpunit --coverage-text
	 [...]
	 Code Coverage Report for 'BankAccount'
	 YYYY-MM-DD HH:II:SS
	
	 Summary: 
	   Classes: 90.91% (20/22)
	   Methods: 94.74% (36/38)
	   Lines:   98.38% (182/185)
	
	 @bankaccount.controller::BankAccountController
	   Methods: 100.00% ( 2/ 2)   Lines: 100.00% ( 13/ 13)
	 @bankaccount.controller::BankAccountListController
	   Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  2/  2)
	 @bankaccount.framework::ControllerFactory
	 [...]

# What it can do

- Colored output will be generated if you use - colors or have configured it in your phpunit.xml so you can now bring some more green into your day
-  coverage-text will by default write to STDOUT but you can tell it to write in a file too if you want
- A small project summary and a list of all covered classes including their coverage percentages will be printed. If you want ALL classes listed there is an xml config option for that.
- When your code uses namespaces those will be used to sort and list the classes `\My\Namespace\ClassName`
- If you don't use namespaces but phpdoc-style @package tags those will be listed like shown in the example above.

# Why it exists

While practicing TDD and while working at new classes I usually have my test suite running on one of the other screens using an advanced version of `watch -n1 phpunit` so i don't have to press a button to see the testing status.

To make sure I always stay at 100% test coverage, which is not hard when doing TDD, I was using - coverage-html and a browser window with auto refresh but this was a rather cumbersome approach so I wanted something smaller and faster.

Another nice thing is that it got really easy to get an overview of a projects code coverage status without needing to generate a set of html file first.

# Main use case

You should have figured out by now that this type of reporting is pretty pointless for anything expect very small projects when used on the whole code base.

For anything a little bigger this can still be **used in conjunction with the - filter option**.

	watch -n1 phpunit --filter MyNewClass --coverage-text

keeps you updated on the class you are currently working on shows its code coverage information.

# Docs

This feature is of course also documented in the [phpunit documentation](http://www.phpunit.de/manual/3.6/en/logging.html#logging.codecoverage.text)

# Conclusion

If people are interested in using PHPUnit this way one possible addition would be to list the methods of a class if it is the only one with coverage information as this would help a little when working with single classes but for any more advanced information the HTML report will still be the place to look.

Give it a try and let me know if you like it.
