---

layout: post
title: Setting up Jenkins for PHP Projects
tags: [hundsonci, jenkinsci, phpqatools, phpunit]

---

One and a half month ago i wrote [a little guide about setting up Hudson for PHP Projects in 15 minutes](/2010-12-19-setting-up-hudson-for-php-projects), called it a first draft and got quite some feedback and retweets (even a flattr!) from which i assume it wasn't total crap. Thank you :)

Since then two relevant important things happened. [Hudson got renamed to Jenkins](http://www.infoq.com/news/2011/01/jenkins) moved away from [http://hudson-ci.org/](http://hudson-ci.org/) (which oracles still supports afaik) and over to [http://jenkins-ci.org/](http://jenkins-ci.org/). I'll be talking about the site with the "Like" Button, not the one with the oracle logo from now one. While this was happening [Sebastian](http://sebastian-bergmann.de/) created the wonderful [http://jenkins-php.org/](http://jenkins-php.org/) and polished the setup instructions that are now featured there with me contributing some minor details for the new PHPloc graphs.

So for me it was time to update the guide, especially because you now get even more features and nice graphs and some issues have been resolved. If you followed the old tutorial i recommend you create a new job now.. but I'm getting ahead of my self.

This guide will feature two parts. The first one being the obligatory "why do i need this" or maybe the "why, dear friend or coworker who i send this link do YOU / WE need to install this" resulting in a (spoiler): Because it's fucking awesome!

## Why? (Scroll down for 'Installing! ')

### For starters let's talk about what Jenkins is

Jenkins is a continuous integration server!

Ok, that was helpful !

###What is continuous integration server then?

Think of it as a glorified "cron" job with a nice web interface. It's a piece of software that is build around the notion that it would be a really good idea to see if another piece of software you are currently developing "works" all the time. Since "works" is a pretty loose definition that also varies greatly among different types of Software these servers tend to be pretty flexible and open. [For a longer explanation check Wikipedia](http://en.wikipedia.org/wiki/Continuous_integration).
 
### Why do i want one and what do i to with it?

So PHP developers the last few years where awesome don't you think ? Many of us stopped "programming" and started "creating software", our language matured greatly, got a pretty solid object model and more and more "really big" projects are written in PHP. Since you need more people to write one of those than you needed to hack together a guest book site there is a growing strife to "quality software". Without going to much into detail for most people that boils down to stuff like automated testing and some sort of quality control.

I'll just assume you heard of Unit Testing using PHPUnit and that you have written some tests or that you have some other way of making sure your software "works". While this is great it can be pretty tedious to run the whole test suite every time before a commit but not doing it leads to a broken test suite that other people have to repair or go around asking who broke it.. make up your own story.

This is where a continuous integration (CI) server jumps in!

Every time you commit, or push if you're using git, to a repository it detects the change, gets the new version of the source, runs all your tests (and more if you tell it to) and notifies you if there was a problem.

### Why Jenkins

If there is one thing i really despise about software then it is installing it. I want to spend my time DOING stuff with that software not setting it up and reading some installation guides that feel longer than my thesis.

In that regard Jenkins was an epiphany, a CI server that is _running_ on my machine in less than 5 minutes time regardless of the system I'm current working on. It's faster to install than to search for a "show demo" link!

And of course it's really powerful having easy-to-install plugins for just about everything you can think of while being fast, stable and now problem to maintain at all.

Coming from the Java world Hudson features a variety of quality measurement tools that are integrated into the CI server via plugins. The "big" one being xUnit (PHPUnit) for Unit Testing and to name same other ones: checkstyle (php code sniffer) for making sure your code matches our coding standards, pmd (phpmd) for mess detection that tells you about too complex structures and much much more or pmd-cpd (phpcpd) the copy paste detector that lets you find duplicate code blocks.

###Jenkins and PHP?

For some time it was some hassle to get a full blown PHP project running. While most of the php-qa-tools, some named above, feature a way of outputting a XML file that is compatible with the corresponding Java tool and integrates nicely into Jenkins there was the need to look into every tool to find the switch, put it in place and tell Jenkins where to find the output file.

Then there was [https://github.com/sebastianbergmann/php-hudson-template/](php-hudson-template), a small GitHub project started by Sebastian that took away most of the hassle.

Now there is an updated version of the the project on [http://jenkins-php.org/](http://jenkins-php.org/) and while only details changed those little changes are going to make it even easier to get something running before you HAVE to care about how everything works.

##Installing!

While Jenkins runs on pretty much anything for this guide i will be using <strong>Ubuntu 10.10.</strong>

I'll go through the parts of the setup that aren't described on the tutorial and send you there for some of the steps and copy/pasting since that page will be updated and this blog post most likely will not, at least not as frequently.

After a fresh installation of Ubuntu we of course need a PHP and we'll install and update the PEAR installer while we are at it so you won't run into any troubles with all the great phpqa-tools.

	sudo apt-get install php5-cli
	sudo apt-get install php-pear
	sudo apt-get install php5-xdebug

and please make sure you have xDebug activated. The error messages are not really helping you in case you forgot to write it in your php.ini ;)

	php -r "var_dump(extension_loaded('xdebug'));

To install Jenkins follow [http://pkg.jenkins-ci.org/debian/](http://pkg.jenkins-ci.org/debian/) or if you are not on Ubuntu pick the system of your choosing on [http://jenkins-ci.org/](http://jenkins-ci.org/). That will also take care of most the dependencies.

For anything else that is needed:

	sudo apt-get install ant
	sudo apt-get install git

Let's create an example project now:

	cd ~
	mkdir mydemo
	cd mydemo
	git init
	mkdir src tests
	touch build.xml build.properties phpunit.xml.dist src/MyClass.php tests/MyClassTest.php
	echo "build" > .gitignore
	echo "source=\${basedir}/src" > build.properties
	git add src tests .gitignore build.xml build.properties</code> <code>phpunit.xml.dist</code> <code>
	git commit -m"Inital dump"

Now [head over to the guide](http://jenkins-php.org/) and:

 * Install the Jenkins Plugins
 * Install the required PHP Tools
 * Copy the build.xml into your stuff into your build.xml
 * Copy the following in your phpunit.dist.xml
 * In addition to the other install commands run: `java -jar jenkins-cli.jar -s http://localhost:8080 install-plugin git` since we are using git for this example

{:.prettyprint .lang-xml}
	<?xml version="1.0" encoding="UTF-8"?>
	<phpunit backupGlobals="false"
	   backupStaticAttributes="false"
	          syntaxCheck="false">
	
	<testsuites>
	     <testsuite name="php-demo">
	         <directory suffix="Test.php">tests</directory>
	     </testsuite>
	 </testsuites>
	<logging>
	  <log type="coverage-html" target="build/coverage" title="php-demo"
	       charset="UTF-8" yui="true" highlight="true"
	       lowUpperBound="35" highLowerBound="70"/>
	  <log type="coverage-clover" target="build/logs/clover.xml"/>
	  <log type="junit" target="build/logs/junit.xml" logIncompleteSkipped="false"/>
	</logging>
	</phpunit>

and fill your class and test with some code:

{:.prettyprint .lang-php}
	<?php
	class MyClass {
	    public function demo($a) {
	        if($a == 1) {
	            return 1;
	        }
	        return 0;
	    }
	}


And a sample test for `tests/MyClassTest.php`

{:.prettyprint .lang-php}
	<?php
	
	require_once("src/MyClass.php");
	
	class MyClassTest extends PHPUnit_Framework_TestCase {
	
	    public function testDemo() {
	        $x = new MyClass();
	        $this->assertEquals(1, $x->demo(1));
	    }
	}

Commit into git and let's get to the last step!

	cd ~/mydemo
	git commit -am"Lets do this"
	cd /var/lib/jenkins/jobs
	sudo git clone git://github.com/sebastianbergmann/php-jenkins-template.git php-template
	sudo /etc/init.d/jenkins stop
	sudo /etc/init.d/jenkins start

[Now head back over to the guide](http://jenkins-php.org/) and run through step 3 to finish.

For "Fill in your 'Source Code Management' information." use "Git" and for the path use `file:///home/YOUR_USERNAME/mydemo`.

**DONE!**

Click "Build now" two times and look at all the pretty graphs.  

--------

I hope this got you started. This is the second draft of this guide and if you have something to add or ran into any problems please leave me a comment.
