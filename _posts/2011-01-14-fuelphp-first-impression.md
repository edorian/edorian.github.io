---

layout: post
title: FuelPHP first impression
tags: [php, phpqatools, phpunit]

---

Two days ago someone told me about the "new kid on the block" [FuelPHP](http://fuelphp.com) and i was pretty excited!

A new fresh PHP 5.3 only OOP Framework!

Yay! Something to play around with until Symfony 2 is released? Some fresh ideas?

It was really easy to install and then i looked into the docs under ["**Step #3: Create sexy code**"](http://fuelphp.com/docs/general/controllers/base.html)


	class Controller_Example extends Controller {
	
	  public function action_index()
	  {
	    $data['css'] = Asset::css(array('reset.css','960.css','main.css'));
	    $this->output = View::factory('test/index', $data);
	  }
	}

Oh body our definitions of sexy seem to differ greatly.

To keep this short: I don't see any way to unit test those controllers and then why again should i care?

Maybe i missed something so i went around asking different people about it and everyone seems to have come to the same conclusion.

I browsed around in their GitHub repo and i didn't find any tests there ether.. kinda confusing.

Nevertheless those are only _first impression_. Go see for your self and correct me if I'm wrong :)
