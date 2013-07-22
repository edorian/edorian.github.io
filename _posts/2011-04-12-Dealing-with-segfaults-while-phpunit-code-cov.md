---

layout: post
title: Dealing with segfaults while PHPUnit code coverage generation for CI
tags: [jenkinsci, php, phpqatools, phpunit]

---

# Oh, the build failed?

Let's look at the console output!

	phpunit
	[...]
	Return code: 139
	[...]
	Build FAILED!

*sigh*. Not again!

# The Problem

About half the "Build failed" mails I've gotten from [Jenkins](http://jenkins-ci.org) in the last two weeks where not due to me breaking the tests but just [PHPUnit](http://phpunit.de) segfaulting. Wait! I know **PHPUnit can't segfault!**", only PHP itself can.

And it does, quite often. For some reason that probably has to do with using **PHP 5.2.OLD** it doesn't survive generate the clover.xml file or the HTML report about 20% of the times it's being run.

This probably could be solved by upgrading PHP but as long as that hasn't happened on the production servers i don't want to do that for CI ether and the production env. is on that old version because `$randomLameExcuse`.

So for now I'd like Jenkins to not send me a mail when that has happened. I don't want failed builds because of that ether. After playing around with several ideas how i could handle that and discovering that very one of those brought it's share of new problems. Like telling ant to ignore the segfault it, of course, lets to Jenkins complaining about the clover.xml not being valid an so on. So i resorted to something pretty simple.

# The "Solution"

For now I'll just rerun PHPUnit until it gets there. The script below does, for now, a pretty good job at that.

It wraps the "phpunit" call passing though all parameters. Should PHP segfault the script is run again until something else happens. That PHPUnit return code is than returned by the script.

Sometimes the build takes a little longer (if it runs twice) but so far i haven't seen it run more than those two times. Even if it should get stuck in an endless loop adding a counter to the script seems pretty trivial. Well, as is the whole problem but it generates useless mails and wastes time, so away with it!

# The Code

`phpunit-segfault-wrapper`


	#!/usr/bin/env bash

	returnCode=139; # Segfault
	
	echo 'Starting PHPUnit Segfault Wrapper';
	echo
	
	while [ $returnCode -eq 139 ]
	do
	    phpunit $*
	    returnCode=$?
	done
	
	echo
	echo 'Done with PHPUnit';
	echo
	
	exit $returnCode

-------------

Placing that file next to your build.xml and a changing the `phpunit` call to a `phpunit-segfault-wrapper` call is all there is left to do.

# Disclamer

Chris Cornutt over at [phpdeveloper.org](http://phpdeveloper.org/news/16197) pretty much nailed it:

> If something was seriously broken, this could cause all sorts of problems, but in theory it's a simple hack that gets the job done.
