---

layout: post
title: An introduction to PHPUnits @covers annotation
tags: [php, phpqatools, phpunit]

---

The Code Coverage reports PHPUnit can generate for you can tell you one important thing:

**What parts of your code you definitely have not tested yet!**

It can't tell you what part of your code base you have tested. Everything that is green ("covered") only shows you that that code was **"executed"**. If you where to write a small piece of code running your bootstrap and executing all your controllers in a loop than you probably can get 80% of your code to ***execute*** but you have ***tested*** **0% of it**.

One of the goals of your test suite and the coverage report is to make you trust in your code base and to remove the fear of changing something that needs to be changed. If you look at your coverage report and you see that one class or one module has 100% coverage ("which is doable and the only goal to strive for that makes sense.. but that's for another post)" you should trust your tests and your code to work properly. You shouldn't think "Well yes that a 100% but a lot of that just comes from that big integration test and I don't know if the class is really tested!".

# Let @covers keep you honest

Thankfully PHPUnit offers a way to drastically increase your confidence in what you actually have *tested*.

By using the @covers annotation above each test case you can tell PHPUnit which methods you are actually testing in that test case. That means you don't generate code coverage information by accident. Additionally the - strict switch will not generate coverage for all test cases that don't make any assertions. Of course you can "work around" that but as only as you don't lie to yourself your test suite won't ether.

You can find a [basic example of how the @covers annotation works](http://www.phpunit.de/manual/3.6/en/code-coverage-analysis.html#code-coverage-analysis.specifying-covered-methods) in the PHPUnit documentation.

The great thing about @covers is that now you have the knowledge that your tests really go into each line of your code and most likely make assertions about the outcome. When it comes to trusting your own test suite this is a big improvement.

# Protected Methods and @covers

For public methods usually one test tests one method. Your **testAddingStuffFailsWhenIInvalidProductIsPassed** covers your `addStuff`, your `removeStuffIAdded` mainly covers your remove stuff as the `addStuff` method should already be tested before.

While you could also `@covers` your `addStuff` method when testing the `removeStuff` method I'd say usually that isn't needed and you should **only generate coverage for the methods that correlate with your assertions**. Not the methods you call to "set up" your object.

When it comes to protected methods there are two ways you can go

- List all protected methods, each with a @covers annotation
- Tell the tests to always generate code coverage for all protected methods

In my opinion the second one makes more sense. First of you don't have to write +10 lines of @covers annotations when you have many small protected methods in your class and secondly i think it goes better along with the ""We don't test protected methods because they are an implementation detail"" way of thinking. I don't want to adjust my @covers annotations every time i refactor. This can be achieved quite easily by adding @covers to the test classes doc block.

# An example

{:.prettyprint .lang-php}
	/**
	 * @covers Calculator::
	 */
	class CalculatorTest extends PHPUnit_Framework_TestCase {
	    protected function setUp() { /* ... */ }
	    /**
	     * @covers Calculator::add
	     */
	    public function testAddTwoIntegers() { /* ... */ }
	    /**
	     * @covers Calculator::multiply
	     */
	    public function testMultiplyTwoIntegers() { /* ... */ }
	}

# Conclusion

The @covers annotation help you increase the quality of your test suite by making sure that your unit tests really only test one class by only generating code coverage for that class. When you have this knowledge you can be a lot more confident that your tests really cover every class of your project. Especially that the one you are currently working on is properly tested.

While there are other ways to achieve that, for example running on the one test case at a time for each of your classes and then looking at that coverage, the @covers annotation offers a nice, clean and fast way to improve your tests. Try it!
