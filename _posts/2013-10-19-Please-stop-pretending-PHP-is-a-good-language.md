---

layout: post
title: Please stop pretending PHP is a good language
tags: [php, rant]

---

> The first step to fixing a problem is admitting that there is one.

Please keep that in mind when read this post.

## Admitting the problem

I'm currently observing two kinds of discussions around the core PHP language. A couple of folks say **"Sure the language sucks but look at all the amazing stuff we build with it!"** and the other camp goes **"Look at all the amazing stuff we build - The language can't be that bad!"**.

The main point here is that the PHP applications that have been created over the years are incredible. Maybe not technically but in the fathomable amount of value they created for their users. The astonishing dominance of PHP in the Web doesn't come from the fact that it is a good language, it comes from the fact that it allowed people to create and maintain things that are really useful. You might not like to core in Wordpress, Drupal or phpBB but image the amount of sharing and collaboration they facilitated in the last 10 years.

All build on a language that is really easy to get into but incredibly spiteful and frustrating at times.

## It's not ok

It's just not ok to run applications that have 5 millions lines of code in a language that just dies when a method doesn't exist when it's trying to call it.

Let me say that again: It's NOT OK that you present your user with a white page and your developer with an E_ERROR **without a stackstrace** because $user is null in some edge case somewhere. The amount of waste and technical debt this single decision causes is NOT OK for a language that runs the damn internet. 

It's not 1995 anymore where nobody cared that your guest book was not available. It's 2014 soon and the least you could do is to present your users with a generic nice error that apologies for what happened and mails your dev a stacktrace of what happened. There are at least 3 other wildly used web scripting languages that don't just throw a blank page in your face. Arguing that there are reasons for this behavior and that it's "consistent with the language" is throwing a fixable problem back into the face of people that then go and spend hours debugging to find things that just a darn stacktrace from production could have told them.

**And that's just not ok.**

- It's not ok that you can't reliably get the first element of an array using less than 4 lines of code without causing side effects.*[1]
- It's not ok that the output of `echo 5/3` might depend on the country live in if you don't know the fine details of configuring PHP.
- It's not ok that you won't be able can't call "array\_map" or just "$iterator->reduce" on an iterator in 2014.
- It's not ok to ignore the simple fact that most of the PHP world currently relies on parsing function and class comments for it's code to function because people can't get their shit together on mailing lists.
- It's not ok to run around shouting "type hinting for literals would mean that passing an int to float hint would fatal PHP" and calling that an reasonable argument while people just write `$x = (float)$x;` in the cases where it actually does matter anyways.
- It's not ok to be not able to talk to 2 back end data sources in parallel, using "promises" or whatever, in a language that has "pull stuff out of database and put it into the internet" as a proclaimed core competency.
- It's not ok that `echo 0.000001;` produces `1.0E-6` and that casting it to string doesn't help but putting quotes around it does.
- It's not ok that you have to [clear the error buffer by generating a suppressed undefined variable error just to be able to sanely use token\_get\_all()](https://github.com/nikic/PHP-Parser/blob/master/lib/PHPParser/Lexer.php#L44).

No single point of this breaks the ""PHP Platform"" in any way, even though it's really pathetic that you have to deal with PHP just completely falling over without a stacktrace in a lot of cases, but things are getting to the point where the sheer amount of weird, arcane cruft that has to be worked around every day can't be explained or justified (for a cost/gain perspective) anymore.

So please, please stop pretending that "everything is fine and you just have to learn PHP". It's not, things have to be fixed.

## Great, another rant, so now what?

The first thing you could do is **not** write an angry snarky mail to @internals complaining about something. That would be amazing. There are way to few people contributing to PHP core anyways and not bothering those who do is a good first step. Especially if you, like me, are not providing coded solutions.

If you **can** muster a great deal of patience and you are a nice person do deal with on the internet then, and only then, should you go and write RFCs to improve things and stick with them for the year or longer it might take to actually get them in. It's not a really nice system but since we don't have the benefit of one benevolent dictator things will just take a little longer. 

In it's current state PHP is more and more becoming a liability and not a good choice and this has to be fixed. The first step would be to just accept the fact that there are real, existing issues within PHP core that have to be taken care of and then have some reasonable discourse. None of these problems are, as far as i can tell or have been told, really though technical problems. It's people disagreeing over what PHP should do or be and drifting off into unconstructive and unfocused banter.   

Claiming that **"It's open source! Just fix it"** won't solve any problem either. You don't attract people to contribute to a project but being a massive mess and pretending that your issues are not real. There is choice on the web and you don't have to spend half a year fixing a broken car when you can get a new, working, one for the same amount of money. Java grew projects like [Coin](http://openjdk.java.net/projects/coin/) to fix the little things.

## It's ok that it's broken. Just please stop pretending it's not.

The claim that "PHP is this awesome enabling language that let's you focus on doing awesome things" doesn't hold up when all of the gains are wasting dealing with the obtuse errors.

You can't have your cake and E_ERROR method call on a non object 

------------

Thank you

[1] : Yes I know `$first = reset((array_values($list)));` would work. But `$first = reset(array_values($list));` doesn't. And are you really trying to tell me this a solution because it kinda looks like part of the problem. We're not golfing here.