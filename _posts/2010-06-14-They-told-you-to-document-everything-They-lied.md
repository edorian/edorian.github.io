---

layout: post
title: They told you to document everything? They lied!
tags: [php, cleancode]

---

I'm starting a little series about "stuff that physically hurts me if i see it in source code" that might get put together into a talk. So if you have any feedback or examples that hurt you I'd really love to hear them !

So what causes me pain? Mostly stuff that **wastes time**, it wasted your time writing it and much more important: It wastes my time (and everyone else's time too!).

    <?php
    class myClass {
        /**
		 * Constructor
	 	 */
	    public function __construct() {}
	    // methods... 
    }

If you write comments like that you just wasted 5 seconds of my life, not counting the time it took to poison your coffee. If your doing that in every class that sums up to quite some time... but let me explain.

### This is wrong on at least 2 levels!

First off: Why on earth does anyone think it is necessary to write "Constructor" over a function that clearly can serve no other purpose that to restate the obvious?

"*But our Coding guidelines say that every function has to be documented*": Ok. Let's see. If your working on an open source project that states those rules i can somewhat see your point. It's easy to check if everything has a docblock and it might help people to their job. Do the rules allow you to use an empty one? Please to so.

If you're not allowed to have a function without documentation your asking people to write pointless stuff just to fulfill some code sniffer rule that shouldn't exist in the first place. People are less likely to care about a good, speaking method name if the need to write an explanation for it anyways. From that standpoint it's much easier to document `getId` then `getCustomerId` because you have something to say about the first name.. and that hurts.

Oh and btw: "Creates an instance of object $classname" is not better, it's WAY (way) worse. It's equally useless, wastes more time and it even needs to be changed when the class is renamed.

Ok. After we're done with the docblock let's ask (no really, find the guy that did that and go ask him, i wanna know!) why that method exists in the first place.

There is no point in wasting 5LOC for something that does absolutely no good. An empty constructor is pointless and maybe even hurtful in some cases (not calling parent).

"*But i might need it later*": If you hear that explanation go find a Book by Robert C. Martin or a big YAGNI sign and .. you know..

"*Oh that code is in my template, i can't be bothered with deleting it every time*": If your projects template looks like this GET IT CHANGED, if not: why isn't he using the default template ?
