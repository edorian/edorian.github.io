---

layout: post
title: How i judge frameworks - Or: Let me code in peace
tags: [php, frameworks]

---

**I just hate talking about frameworks!**

But as it seems not many people share that feeling so this is an attempt to write a rather short and linkable post on how i approach a new framework and by what standards i judge it.

Over the past **2 month** I've spend a lot of time in various chats talking over php. In that time **over 15 php frameworks** have been introduced to me and I'm kinda done explaining why i do or (mostly) don't like a certain framework.

I'm not going to call *any* names in this post so no need to grab your pitchforks. (For some reason people seem to get really upset when you tell them you don't like the framework the use)

# Can i still write code that meets my standards?

That's pretty much the **only** question i ask myself when reviewing a framework.

If their code looks like crap i don't really care, chances are that i don't understand it or that i just picked "the wrong file". If the API is a mess thats a different topic but how everything is implemented usually doesn't concern me at all.

What's really important to me is that the **code i write using that framework is something i want to maintain**. For me that takes care of at least 90% of the frameworks I've seen.

Those are my standards and your mileage may vary so let me explain.

# My basic approach - First contact

The front page usually will tell me that the framework is: "Fast, secure, flexible, easy, small, elegant, efficient, reboust, MVC, simple and that kittens will die when i use something else for my next project"

The amount of "positive adjectives" usually is already a good indicator where the ride will take me. The less the better and bonus points of the framework tells me what it is aiming to do and what is is NOT good at. A clear vision is a much more valid excuse for implementing something in a way i don't like than "because it's better this way" ever can be.

If the front page mentions MVC (and at least 90% of the frameworks do) i usually look up the frameworks definition of MVC and compare it to their implementation and further links explaining MVC.

Actually that part can be quite fun sometimes:

- Does it link to wikipedia and then describe something completely different?
- Does its explanation match their implementation? (Mostly judging from the code samples they provide)
- Does it tell you that the Models are just data access classes?

I'm aware that word is becoming more of an empty marketing term that lost all its original meaning in the php framework world and has become a shorthand for "Don't put sql in your template" but sometimes a framework is actually aware of what it does and gives it a proper name and provides a solid explanation for its approach.

Then I'm browsing the sample code in the docs and maybe the sample projects they include. The question I'm asking myself there is "Is that code i want to have in my project" and "Does it work like I'd expect it to work by just reading the class names"

After doing so I'll get to the main question

# Applying my standards

Everything mentioned above can be boiled down to a very basic question:

**I'd just like to be able to write unit tests for the code I WRITE**. That is a rather specific statement but it was many implications about the architecture of the framework, so in reality there is more to it than testing but I found that most of those things can be reduced to that question.

I don't care about the framework, it's nice if they do because they might know how testable code looks like but it's not a problem if the framework has no tests at all. I just expect it to work, how the people creating the framework ensure that is not my business.

Every other aspect of the framework is negotiable but not being able to write tests means that **I (personally)** have no way of making sure that the code i write actually works and is structured in a way that i know i can adapt when requirements change.

Additionally: If I'm able to mock out every part of the framework while i write tests there is also a relatively big chance I'm also able to **replace** a part of the framework (like the authentication) in case i, for whatever reason, need to do so.

Testable code is not a boolean so let me draw you a little scale:

**From "oh dear" to "hell yeah!"**

To write test for the code **I write using the framework** i need to

- rerun the application bootstrap for each test and unregistered constants with runkit
- rerun parts of the application bootstrap or interact with the frameworks "test bootstrap"
- ask the framework to create my classes or some environment for me ([no control over the object lifecycle](http://qafoo.com/blog/020_object_lifecycle_control.html) / strings with class names instead of objects)
- interact with a registry to get some "needed" classes into my code
- pull out a DIC or a service container out of some global scope
- need to interact with a DIC or service container
- just test my code. (**I** control the object lifecycle of **MY** classes and i can do DI everywhere when i see fit in the way i see fit)

I usually spend an hour or two with every php framework i encounter before passing personal judgment and usually i don't get very far down that list.

**That might not be a problem for you!**

Your values in code might differ and you might not need that for your project. I tend to focus on very long running projects that a long maintenance period and testing is the best way i know of to make my code future proof.

So please don't be mad if you don't like the framework you are using, it just might not work for me :)
