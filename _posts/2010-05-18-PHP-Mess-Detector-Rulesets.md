---

layout: post
title: PHP Mess Detector - Rulesets
tags: [php]

---

A while ago I've seen a tweet [linking to a phpundercontrol tutorial](http://techportal.ibuildings.com/2009/03/03/getting-started-with-phpundercontrol/) and decided to give it another try.

As part of that i picked up [PHP Mess Detector](http://phpmd.org) again and while it provides a small but very good documentation and was not much of a hassle to set it up, i wanted to do a short writeup about the things that took me to figure out and provide my opinions on some of the rules.

PHPMD is a great tool to help you produce high quality code by providing some of the feedback you else would only get by a human review. It's pointing out too long/complex Classes and Methods, unused and sloppy written Code. (Rule Overview: [http://phpmd.org/rules/index.html](http://phpmd.org/rules/index.html)

If you want to play along:

Install:

	pear channel-discover pear.phpmd.org
	pear channel-discover pear.pdepend.org
	pear install --alldeps phpmd/PHP_PMD-alpha

To execute all the Checks and get a first impression use:

	phpmd /path/to/source text codesize,unusedcode,naming

If you have a big code base i recommended picking a subset to play around at first since it can take quite a while ( On my machine it takes around 10 Minutes per MLOC ).

You should get lots of output and by skipping through I'm sure you'll find some stuff you wonder about. At the moment the "unused Variable" Output is buggy for some cases so don't let that disturb you.


As a next Step we'll build a custom XML file that should show you how you can configure everything PHPMD has to offer.

Attached you find a sample config that implements most of the rules and i strongly advise you not to take it as is. But as far as i know it uses every tag and option available and combined this the documentation you shouldn't have any problems building your own.

Some Samples:

All "unusedcode" Rules without configuration. Same goes for "codesize" and "naming".

	<rule ref="rulesets/unusedcode.xml" />

Just use one Rule:

	<rule ref="rulesets/unusedcode.xml/UnusedFormalParameter">

Using the "LongVariable" rule, changing the priority and a rule Parameter.

{:.prettyprint .lang-xml}
    <rule ref="rulesets/naming.xml/LongVariable">
        <priority>5</priority>
        <properties>
            <property name="maximum" value="40">
        </properties>
    </rule>

Some words on using the tool in your daily QA:

Per Default PHPMD complains about a variable exceeding 20 Chars, the only decision i don't understand and don't agree with.

I'd rather have my developers using long Variable like `$customerAbleToDeleteProducts` than `$canDelete` or something and not worry about anything complaining about it.

Of course you can completely skip certain rules if the don't fit your project or coding rules. E.g. i skipped "show variable name" since i didn't want to refactor every variable named `$db`.

It's my (current) believe that a QA shouldn't spit out any warnings about stuff your not going to fix. It lowers developer acceptance (broken window...) and makes your job harder // makes you waste time skipping those things in your head every time you see them.

So if a rule is impractical just change it.

If your applying all the rules at once to a old grown code base you might end up with ten thousand or more errors and your not going to fix those right away are you ? Figure out whats most important to you and work on those issues until your happy and then phase in the next rules giving people a chance to clean up your code bit by bit (boy scouts rule).

To get a working sample config check [phpmd-sample-config.xml][sampleConfig]

[sampleConfig]: /assets/posts/2010-05-18/phpmd-sample-config.xml
