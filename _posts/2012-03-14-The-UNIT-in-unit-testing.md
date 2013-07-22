---

layout: post
title: The UNIT in unit testing
tags: [php, phpqatools, phpunit]

---

## What does the word **UNIT** in unit testing stand for?

Think of an answer and read on!

![weightsAndMeasurements]

*[Photo credit - Wikipedia - Free content licence](http://en.wikipedia.org/wiki/File:Weights_and_Measures_office.jpg)

------

So? Did you say **"A method"?** Because we test methods!?</p>

If so let me offer another perspective.

Wikipedia tells us that the answer is:

> A unit is the smallest testable part of an application

but what does mean? Is method the right answer because it is the smallest testable part?

### It's about class behaviors

To be more precise, using my own words:

> Unit testing, in PHP, is about testing the observable behaviors of a class!

Observable from the outside! Nobody cares about the internal state of a class if it never changes the outcome of a method call.

But let's take a step back.

Can we test methods in isolation?

A method on it's own is hardly testable. It might seem counter intuitive at first but maybe let us look at some examples before i try to make a general point:

{:.prettyprint .lang-php}
	private $count;

	public function getCount() {
	    return $this->count;
	}

This is maybe the most obvious case but it shows that without the ability to manipulate `$this->count` from the outside writing a test is pointless.

We could make `$count` public (using reflection), change its value and test if the getter works but then all we did was test the implementation of the getter method not that "it does the right thing".

If you later change the function to:

{:.prettyprint .lang-php}
	public function getCount() {
		return count($this->list);
	}

our test would fail even so the class might still work as expected!

**Another example**
	
{:.prettyprint .linenums .lang-php}
	public function setValue($value) {
	    $this->value = $value;
	}
	
	public function execute() {
	    if (!$this->value) {
	        throw new Exception("No Value, no good");
	    }
	    return $value * 10; // business logic
	}


The example may be a little constructed but it should show you that testing "execute" can't be done "in isolation" as we need another method.

**Well DO'H!**

It sounds trivial but the distinction is important and more easy to overlook when looking at bigger classes.

What do we test there?

 - IF we don't `setValue` AND then call `execute` an exception is thrown!
 - IF we DO `setValue` AND then call `execute` we get an computed result


So **we are testing two behaviors of your class** and not the methods in isolation!

This is very important as the "test methods" mindset might lead you to adding a `getValue` function just for the tests so you have "at least those methods covered" but you end up with rather worthless tests that don't tell you if the class actually works!

## Testing behaviors

> OOP is about passing messages between objects

When I call a method on an object I ask it do something. Another way of saying that is "I tell an object to exhibit a certain behavior"

**So what "behaviors" does a class have?


- return values
- calling other methods
- modifying global state (writing to files, the db, $GLOBALS)

**What we care about when unit testing**

Return values - The answer we get to our questions!

{:.prettyprint .lang-php}
	assertSame($expectedResult, $object->methodCall);

Calls to other methods - Does it pass the message along?

{:.prettyprint .lang-php}
	$loggerMock->expects($this->once())->method('log')->with('ERROR');

We don't really care about testing global state. We try to avoid it inside the application and for testing the database interactions we use integration or system tests.

## And whats your point?

If you want one sentence out of this: **Having one test case per method is usually a bad thing!**

What you want is: **One test case per behavior**

You want to have a list of **"If you do this, it should react like that"** sentences in executable form.

Why? Because there is value in specifying how a class should react to certain inputs. It's why we do testing all along and we should express that as clearly as possible to make writing tests worthwhile.

## It's about having reliable tests

When you change a class and the tests fail you should be sure that it now **actually works differently** and if that wasn't your intention (because you were just refactoring) then you just saved yourself from a bug.

When your tests fail but the class "still works" and you need to "fix the tests" the your tests are worth a lot less as they don't really give you that cozy safety net that they should provide you with.

## The same goes for testing private methods

They don't have any observable behavior. You test them implicitly through public API of the class.

They are an implementation detail and it is not important if you have 0 or 100 private methods! For the test cases this should make no difference at all. Nobody cares how the work gets done just that the result is right.

## Conclusion

I hope this helped a little to clarify what I mean by saying

> We don't test methods, we test classes/behaviors!

and that it helps you create more meaningful tests suites.

[weightsAndMeasurements]: /assets/posts/2012-03-14/weights-and-heights.jpg
