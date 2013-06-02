---

layout: post
title: Setting up Hudson for PHP Projects in 15 minutes
tags: [php, hudson, phpqatools, phpunit]

---

[**Please see here for an updated version of this guide!**](/2011-02-01-setting-up-jenkins-for-php-projects) 

##Preface

This is the first draft oft this guide, if you have corrections or comments please let me know.

If you know that Hudson is and just looking for the "install guide" just skip over the first five paragraphs right to "Installing".

##Wait, what is Hudson?

Hudson is a continuous integration Server !

**Ok, what is continuous integration Server then?**

For starters think of it as a glorified "cron" job with a nice web interface. It's a piece of software that is build around the notion that it would be a really good idea to see if another piece of software you are currently developing "works" all the time. Since "works" is a pretty loose definition that also varies greatly among different types of Software these servers tend to be pretty flexible and open. [For a longer explanation check Wikipedia.](http://en.wikipedia.org/wiki/Continuous_integration)

##Why do i want one and what do i to with it ?

So PHP developers the last few years where awesome don't you think ? Many of us stopped "programming" and started "creating software", our language matured greatly, got a pretty solid object model and more and more "really big" projects are written in PHP. Since you need more people to write one of those than you needed to hack together a guest book site there is a growing strife to "quality software". Without going to much into detail for most people that boils down to stuff like automated testing and some sort of quality control.

I'll just assume you heard of [Unit Testing](http://en.wikipedia.org/wiki/Unit_testing) using [PHPUnit](http://www.phpunit.de/manual/current/en/index.html) and that you have written some tests or that you have some other way of making sure your software "works". While this is great it can be pretty tedious to run the whole test suite every time before a commit but not doing it leads to a broken test suite that other people have to repair or go around asking who broke it.. make up your own story.

##This is where a continuous integration (CI) server jumps in!

Every time you commit, or push if you're using git, to a repository it detects the change, gets the new version of the source, runs all your tests (and more if you tell it to) and notifies you if there was a Problem.

##Why Hudson?

If there is one thing i really despise about software then it is installing it. I want to spend my time DOING stuff with that software not setting it up and reading some installation guides that feel longer than my thesis. In that regard Hudson was an epiphany, a CI server that is _running_ on my machine in less than 5 minutes time regardless of the system I'm current working on. It's faster to install than to search for a "show demo" link!

And of course it's really powerful having easy-to-install plugins for just about everything you can think of while being fast, stable and now problem to maintain at all.

Coming from the Java world Hudson features a variety of quality measurement tools that are integrated into the ci server via plugins. The "big" one beeing xUnit (PHPUnit) for Unit Testing and to name same other ones: checkstyle (php code sniffer) for making sure your code matches our coding standards, pmd (phpmd) for mess detection that tells you about too complex structures and much much more or pmd-cpd (phpcpd) the copy paste detector that lets you find duplicate code blocks.

##Hudson and PHP?

For some time it was some hassle to get a full blown PHP project running. While most of the php-qa-tools, some named above, feature a way of outputting a xml file that is compatible with the corresponding Java tool and integrates nicely into Hudson there was the need to look into every tool to find the switch, put it in place and tell Hudson where to find the output file.

Nowadays there is [php-hudson-template](https://github.com/sebastianbergmann/php-hudson-template/), a small GitHub project started by Sebastian Bergmann that takes all that hassle away too.

##Installing: In 15 minutes you will be done!

If you read the preface: Nice to see you are still here. Lets get going!

For the course of this tutorial we will be setting everything up using a clean Linux installation. Adaption to other distributions should be easy since Hudson and PHP will work the same everywhere.

We will be using:

 - Ubuntu 10.10 x86 desktop edition

We are going to install and setup:

 - Hudson
 - PHP
 - Git (Of course you can use pretty much any scm you want but we'll need git later anyways so i go with it)
 - PHPUnit
 - many php-qa-tools
 - A Hudson project that will make use of all those tools

##Starting with the basics:

    sudo apt-get update
    sudo apt-get install default-jre php5-cli ant daemon git-core

Your package manager will also install all the other java related programs so we are good to go for Hudson

Ubuntu:

    wget -O /tmp/hudson.deb http://hudson-ci.org/latest/debian/hudson.deb
    sudo dpkg --install /tmp/hudson.deb

Any other *nix:

Check the front page of [http://hudson-ci.org/](http://hudson-ci.org/) or if Hudson isn't packaged for you it's very easy too:

    wget http://hudson-ci.org/latest/hudson.war
    java -jar hudson.war

Now you can check [http://localhost:8080](http://localhost:8080) to see Hudson running.

After that we'll get going with the PHP parts of your shiny new Hudson.

##Following php-hudson-template

From here on out you can [https://github.com/sebastianbergmann/php-hudson-template#readme](just follow the instructions provided by Sebastian on GitHub) but for the sake of "one page to follow and get everything set up and copy/paste as much as possible" I'll rewrite it here.

##From here on out the tutorial is even more out of date now - Stick to the guide on GitHub

Current changes to Hudson, the plugins and that i discovered that the "use publishers from another project" doesn't work out quite well. The guide on GitHub got updated accordingly pretty quick and using it and checking back here should work out well for you.

I'll try to rewrite the tutorial in the next week and maybe the GitHub guide too.

-----

First off we need to install 10 Plugins, thats quite boring to do with the Interface so you could use the Hudson-cli

	wget http://localhost:8080/jnlpJars/hudson-cli.jar
	java -jar hudson-cli.jar -s http://localhost:8080 install-plugin checkstyle
	java -jar hudson-cli.jar -s http://localhost:8080 install-plugin dry
	java -jar hudson-cli.jar -s http://localhost:8080 install-plugin htmlpublisher
	java -jar hudson-cli.jar -s http://localhost:8080 install-plugin jdepend
	java -jar hudson-cli.jar -s http://localhost:8080 install-plugin pmd
	java -jar hudson-cli.jar -s http://localhost:8080 install-plugin template-project
	java -jar hudson-cli.jar -s http://localhost:8080 install-plugin violations
	java -jar hudson-cli.jar -s http://localhost:8080 install-plugin xunit
	java -jar hudson-cli.jar -s http://localhost:8080 install-plugin clover
	java -jar hudson-cli.jar -s http://localhost:8080 install-plugin git

If that doesn't work just go to [http://localhost:8080/pluginManager/available](http://localhost:8080/pluginManager/available) and install the plugins by hand:

 * Checkstyle 
 * DRY
 * HTML Publisher
 * JDepend
 * PMD
 * Template Project
 * Violations
 * xUnit
 * Clover
 * Git

Restart Hudson while that is running lets install all the PHP Tools:

First of we need the "pear" installer and the xdebug extension:

	sudo apt-get install php-pear php5-xdebug

Then register all the relevant pear channels:

	sudo pear channel-discover pear.pdepend.org 
	sudo pear channel-discover pear.phpmd.org 
	sudo pear channel-discover pear.phpunit.de
	sudo pear channel-discover components.ez.no
	sudo pear channel-discover pear.symfony-project.com

And install all the tools:

	sudo pear install pdepend/PHP_Depend-beta
	sudo pear install phpmd/PHP_PMD-alpha
	sudo pear install phpunit/phpcpd
	sudo pear install PHPDocumentor
	sudo pear install PHP_CodeSniffer
	sudo pear install --alldeps phpunit/PHP_CodeBrowser-alpha
	sudo pear install --alldeps phpunit/PHPUnit

Setting up a demo project

	cd ~
	mkdir mydemo
	cd mydemo
	git init
	mkdir src tests
	touch build.xml phpunit.xml.dist src/MyClass.php tests/MyClassTest.php
	echo "build" > .gitignore
	git add src tests .gitignore 
	git commit -m"Inital dump"

Now let's fill our demo project with some life !

Paste the following in your phpunit.xml.dist

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

Some sample code for "src/MyClass.php"
	
	<?php
	class MyClass {
	    public function demo($a) {
	        if($a == 1) {
	            return 1;
	        }
	        return 0;
	    }
	}

And a sample test for "tests/MyClassTest.php"

	<?php
	
	require_once("src/MyClass.php");
	
	class MyClassTest extends PHPUnit_Framework_TestCase {
	
	    public function testDemo() {
	        $x = new MyClass();
	        $this->assertEquals(1, $x->demo(1));
	    }
	}

Now paste that behemoth into your build.xml:

	<project name="php-demo-project" default="build" basedir=".">
	 <target name="clean">
	 <!-- Clean up -->
	 <delete dir="build"/>
	
	 <!-- Create build directories -->
	 <mkdir dir="${basedir}/build/api"/>
	 <mkdir dir="${basedir}/build/code-browser"/>
	 <mkdir dir="${basedir}/build/coverage"/>
	 <mkdir dir="${basedir}/build/logs"/>
	 <mkdir dir="${basedir}/build/pdepend"/>
	 </target>
	
	 <!-- Run unit tests and generate junit.xml and clover.xml 
	 (This is done in the phpunit.xml.dist,
	 you could also write the switches here)
	 -->
	 <target name="phpunit">
	 <exec executable="phpunit" failonerror="true" />
	 </target>
	
	 <!-- Run pdepend, phpmd, phpcpd, and phpcs in parallel -->
	 <target name="parallelTasks">
	   <parallel>
	     <antcall target="pdepend"/>
	     <antcall target="phpmd"/>
	     <antcall target="phpcpd"/>
	     <antcall target="phpcs"/>
	     <antcall target="phpdoc"/>
	   </parallel>
	 </target>
	
	 <!-- Generate jdepend.xml and software metrics charts -->
	 <target name="pdepend">
	   <exec executable="pdepend">
	     <arg line="--jdepend-xml=${basedir}/build/logs/jdepend.xml src" />
	   </exec>
	 </target>
	
	 <!-- Generate pmd.xml -->
	 <target name="phpmd">
	   <exec executable="phpmd">
	     <arg line="src xml codesize,unusedcode --reportfile ${basedir}/build/logs/pmd.xml" />
	   </exec>
	 </target>
	
	 <!-- Generate pmd-cpd.xml -->
	 <target name="phpcpd">
	   <exec executable="phpcpd">
	     <arg line="--log-pmd ${basedir}/build/logs/pmd-cpd.xml src" />
	   </exec>
	 </target>
	
	 <!-- Generate checkstyle.xml -->
	 <target name="phpcs">
	   <exec executable="phpcs" output="/dev/null">
	     <arg line="--report=checkstyle
	     --report-file=${basedir}/build/logs/checkstyle.xml
	     --standard=Sebastian
	     src" />
	   </exec>
	 </target>
	
	 <!-- Generate API documentation -->
	 <target name="phpdoc">
	   <exec executable="phpdoc">
	     <arg line="-d src -t build/api" />
	   </exec>
	 </target>
	
	 <target name="phpcb">
	   <exec executable="phpcb">
	     <arg line="--log    ${basedir}/build/logs
	     --source ${basedir}/src
	     --output ${basedir}/build/code-browser" />
	   </exec>
	 </target>
	
	 <target name="build" depends="clean,phpunit,parallelTasks,phpcb"/>
	</project>

<span style="font-size: large;">Nearly done !</span>

	cd ~/mydemo
	git commit -am"Lets do this"
	cd /var/lib/hudson/jobs
	git clone git://github.com/sebastianbergmann/php-hudson-template.git
	sudo /etc/init.d/hudson stop
	sudo /etc/init.d/hudson start

 * Now go to [http://localhost:8080/](http://localhost:8080/) again
 * Click "New Job"
 * Build a free-style software project "php-demo"
 * Select "git" as the "Source Code Management"
 * User /home/$USERNAME/mydemo as the URL
 * Under Build: click "Add build Step -> Invoke Ant"
 * Under "Post-build Actions": click "Use publishers from another project" and select php-hudson-template as  the "Template Project"
 * Save
 * Click "Build now"
 * Enjoy your first build ! Click it and look at all the pretty output :)
 * If you want to build every time you commit that option is under "configure, build triggers, poll scm"

##Done!

I hope that guide was helpful to you. Note that this is only a draft. Please tell me about any problems you rant into, if it worked for you or if you have suggestions!

Thanks for reading!

