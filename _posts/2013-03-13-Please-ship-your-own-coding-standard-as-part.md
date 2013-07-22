---

layout: post
title: Please ship your own coding standard as part of your project
tags: [php, phpcs, phpmd, phpqatools]

---

# What?

I assume most of you know why we, as programmers, define something called a [coding standard](http://en.wikipedia.org/wiki/Coding_conventions) for a project.

We create a set of guidelines on how we go about certain aspects of programming. Covering things from `indentation` over `variable naming` to more complex topics like `whats a too complex piece of code`.

# Why?

Having a well defined set of rules allows for easier collaboration between members of a project, produces code that looks more like "one person wrote it" and that's easier to follow, understand and change.

If there would be no written rules it would just make it harder to grasp a project or to contribute to it.

Let me elaborate on the second point: Contribution. Most developers i know care about producing `good code`, especially then they are contributing to an open source project!

Those people will respect your coding standard, naming scheme and every thing else that they can check for before sending you all patch/pull request. So try to make that part easy.


# So?

Now that i spend a few words on why every project should have its own coding standard and what is good for there are two things to consider:

- Which one to use
- How to help enforce it

PHP doesn't tell you what [the one true way do go about things](http://www.python.org/dev/peps/pep-0008/) is, like python does. So it is even more important to make developers care about what code THIS project looks like.

I don't want to into the discussion on which one you SHOULD use because this ether asks for a whole new post or just boils done to `apply common sense` and `what works for you works`.

**So lets talk about ways to enforce it:**

You can reformat everything after receiving a contribution. For smaller to medium size projects with one or two main project leads it can work pretty good to just go over a patch and fix everything that doesn't look right.

I'd like to argue that if you make it easy for people to provide patches that already look good most people will do that. Their indent to do so scales with the amount of trouble they have to go though to do so.

Running one or two CLI commands and fixing all errors those tools report is very easy IF those tools only show the errors the developer introduced, if your tools fire out some hundred errors and you ask the contributor to check which one he introduces than chances are he is not going to do so.

# How?

While there might be other tools out there I'm only going to talk about [PHP_CodeSniffer](http://pear.php.net/manual/en/package.php.php-codesniffer.intro.php) and the PHP Mess Detector [PHPMD](http://phpmd.org/). They integrate nicely into Jenkins and let me do everything i want to do when it comes to a coding standard.

**Both tools enable to you do define one .xml file with your defined rules**

Those files should go somewhere into your project sources. For this post and the example included I'm going to stick with a folder layout that works nicely with [http://jenkins-php.org](http://jenkins-php.org/) and the [PHP Project Wizard](https://github.com/sebastianbergmann/php-project-wizard). Of course every other layout will just work out too.

So if the root project looks like this:

	root
	|-- source
	|-- tests
	|-- docs

the suggestion is to create a build folder with a `phpcs.xml` and a `phpxml.xml` and putting a `build.xml` in your project root where you put the exact CLI command to run the tools.

The `build.xml` is optional but once you start ignoring certain folders other some other I'd have to know about I'd rather have you, the project maintainer, tell me how to run your tools than figuring that out myself.

So the new root folder looks like this:

	root
	|-- source
	|-- tests 
	|-- docs
	|-- build.xml
	`-- build
	    |-- phpcs.xml
	    |-- phpmd.xml

For now i don't into the `build.xml` but once you get into using continues integration you could thing about providing an extra task that also make the TEXT output of the tools accessible.

**So i created those file and people checked out my code what can they do now?**

Now every developer can run

	ant checkcode (or whatever you named the task, i am not to sure myself)

or just

	phpcs --standard=build/phpcs.xml source
	phpmd source text build/phpmd.xml

and hopefully you created a standard that is maintained and that you care about so those tools will report ZERO errors. (Yes I've said that already but it's really important if you want people to care that the rules you define are maintained. Throw out every rule you don't plane on following through!)

Now if that contributor goes to change something about your code he can run your tools to make his clean "pretty" before handing it over to you.

Of course the first step after a change is to run your unit tests and making sure his change doesn't break anything. For those tests you might also want to provide a `phpunit.dist.xml` so that running `phpunit` in the root folder does the bootstrap and runs the tests without the contributor having to know how to run your suite.

You as the maintainer have the added bonus of doing all that yourself.

# Wrapping up

When drafting this post i thought about going into the creation of the three .xml files ([phpunit.dist](http://www.phpunit.de/manual/current/en/appendixes.configuration.html), [phpmd](http://phpmd.org/documentation/creating-a-ruleset.html), [phpcs](http://pear.php.net/manual/en/package.php.php-codesniffer.annotated-ruleset.php)) but this is getting quite long and figuring out how to create those files isn't that hard. If you want me to elaborate on one of the subjects touched upon here let me now.
