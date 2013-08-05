<?php
if (!defined('LCMS')) exit;

// Template class
class Template
{
	// We setup $pageVars to hold all our pages
	// variables.
	private $pageVars = array();

	// We setup $template to define what file is our
	// template and were to get it.
	private $template;
	private $blank;

	// When a new Template object is instantiated we want to make sure
	// we pass it a shortened path to the file we want it to use
	// as our template.
	// example: $template = new Template("helloworld/templates/helloworld.php");

	public function __construct($template,$blank=false)
	{

		// We setup our action directory
		$actionsDirectory =  'actions';

		// Let's build and set our class var $template to the
		// value of $template that was passed into our __construct method
		$this->template = $actionsDirectory . '/' . $template;
		$this->blank = $blank;
	}

	// Now we create out set method to allow use to set variables that
	// we want in the template.
	// So in our page action we would do:
	// $template->set("title", "hello world");
	// and in our template:
	// <h1>
	public function set($var, $val)
	{
		// This may look weird, but it's not to bad;
		// what we are doing is setting the index name
		// of our associative array pageVars
		// (we setup earlier at the top of the class)
		// to the value that we pass. so $pageVars["yourVar"] = "yourValue"
		// is basically what it's doing.
		$this->pageVars[$var] = $val;
	}

	// To render we will need to do a couple of things.
	// First we will need to extract those pageVars then
	// include the template, populate the values in the template
	// and return a rendered page to the browser
	//
	// This is far more easy than you think it is
	// trust me!
	public function render()
	{
		// The extract function is a weird bird
		// when you call it on an associative array
		// it creates regular vars.
		// For instance:
		// 		$this->pageVars["yourVar"]
		// becomes:
		// 		$yourVar
		// so basically we are converting all the
		// index keys (with their values), in pageVars to
		// their own respected variables
		extract($this->pageVars);

		// Now that we have all the variables extracted, the vars we set
		// in the template will be replaced by the value of the pageVars variables.
		// Now we start up our output buffer, grab our template and return the
		// buffer with it's "rendered" template

		ob_start();
		if($this->blank == false) {
			require('templates/header.html');
		}
			require($this->template);
		if($this->blank == false) {
			require('templates/footer.html');
		}
		return ob_get_clean();
	}
}
