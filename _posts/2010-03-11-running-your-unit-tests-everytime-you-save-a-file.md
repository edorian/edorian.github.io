---

layout: post
title: Running your unit tests everytime you save a file
tags: [php, phpqatools, PHPUnit, testing]

---

Three days ago i had a little discussion [with a friend of mine](http://www.gremu.net) about unit testing and the differences between the "PHP(Unit) way" and the "Python/Django way".

One topic we talked about was Continuous integration and how many times he runs his tests. While i was going on about build systems and running your tests for every commit he commented:

> oh, mine run every time i save a file

BAM! *ENVY*!

I know this is possible with PHP for some time if your using an IDE like NetBeans or Eclipse and developing on your local machine. Something i don't have at work and I'll won't go into detail too much unless someone really wants to read it. Short: One linux dev server, everyone uses NetBeans on windows and sftp sync do write stuff to the server, Apache &amp; db runs there, unit tests through commandline (putty).


So i was looking for a way to run my test suite to run every time a file changed. Turns out, [someone did figure that out. Thank you.](http://heisel.org/blog/code/pywatch/) 

It's called `pywatch` and is a simple python script that monitors files for changes and executes a script if one has changed.

The way we run our Testsuite is a simple `./runSuite.sh` call so the script at first looked like that:

`find -name '*.php* | xargs pywatch "./runSuite.sh"`

It turned out that pywatch couldn't really handle over 10.000 source files well and i don't blame it for that but for smaller projects that should do it :)

Te deployed solution was embedding pywatch into our custom test runner and only use it on the part of the suite your currently working on. For us that looked like:

    ~/work/unittests $ ./runSuite.php -l Cache
 
> Running only:
>
>   ./path/CacheTest.class.php
>   
>   PHPUnit 3.4.3 by Sebastian Bergmann.
>   
>   .............
>   
>   Time:  0 seconds
>   
>   OK (13 tests, 72 assertions)

So i added a simple "-w / --watch" Flag that starts pywatch for all the source and test files that where included in that run of PHPUnit. I happen to have that list generated in our test runner anyways (since we don't use autoloading and i didn't want to use "require" in the unit test files) but you can get that list on multiple ways ;)

It takes all the arguements passed to the testrunner, strips the "-w" and calls `passthrou` with the command passed to pywatch. It's a litte hackish.

{:.prettyprint .linenums .lang-php}
	<?php
    $sCommand = 'pywatch " '.join(" ", $_SERVER['argv']).' " '.join(" ",$requiredFiles);
	$sCommand = str_replace(" -w", " ", $sCommand);
	passthru($sCommand);

but works well for now.

Now every time i press "save" in NetBeans i look over to the other monitor and with a 0.5 to 2 second delay the test suite runs, CodeCoverage report gets created and i put my browser on auto refresh every 5 seconds so i see that kinda quick too.

While this is not an optimal solution I'm really happy about it that i could make my work environment a little bit smother for me