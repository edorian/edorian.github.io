---

layout: post
title: Book review - Instant Hands on Testing with PHPUnit
tags: [php, phpunit, phpqatools, book]

---

## Preface

A couple of weeks ago I've received a mail asking if I wanted to review [@mlively](https://twitter.com/mlively)'s book and since I've been looking around for a book to recommend to people getting started with testing anyways I've taken the opportunity after the Publisher assured me that I can be be dead honest and that there would be no "proof reading" or anything of my review (Big +1 there PacktPub).

The Book is subtitled "A practical guide to getting started with PHPUnit to improve
code quality." and since it was written by Michael, the author of [Phake](https://github.com/mlively/Phake), I've been going in with high expectations. If you haven't given that mocking library a try get I'd strongly urge you to check it out.

## Scope of the book

The contents of the book walk the reader through the basics of PHPUnit. From installing, project setup and basic features over to testing database interaction, exceptions and understanding code coverage reports while showing off all the little features PHPUnit has in the meanwhile.

It moves slowly through its 67 pages and explains each point in detail noting a couple of common pitfalls. 

## Contents

I've pondered a while how to describe the content and the focus of the book and I think the best way is to say that "It takes your hand and walks you through the documentation in a straight line that everyone can follow".

The value it adds over reading the docs, for me, is that it's a lot easier to follow and builds on one concrete example that gets expanded and worked with as the book goes on.

It doesn't feature much in terms of "when to use which feature" but all the examples and use cases are well explained and build a solid foundation.

I was very happy to see examples like **"Stubbing with callbacks"** and other easily overlooked methods of reaching ones goal.

The explanation of stubbing and mocking also featured Mockery and Phake examples which I find quite nice as they have been all well introduced and the differences are explained in a matter that allows readers to make a good decision moving forward.  

The section on testing database interaction is extensive and features DBUnit and despite the fact that I personally don't like the extension all that much it provides a nice overview of how all the complexity of DB testing can be contained.

## Target audience

I'd say the book is aimed at folks that have some experience with PHP and want to start writing unit tests. It's not a good introduction into "the why and what of unit testing" per see but it teaches the tool "PHPUnit" quite well and might be more digestible than just reading the documentation.   

It's always hard to judge what other folks might get from reading something but I don't assume that the book can teach you much if you already used PHPUnit regularly for a year.


## Where to get it

If you want to read it yourself it's available for around 5€ or your regional equivalent from PacktPub [http://www.packtpub.com/how-to-hands-on-testing-with-phpunit/book](http://www.packtpub.com/how-to-hands-on-testing-with-phpunit/book)
