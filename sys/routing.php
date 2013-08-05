<?php
if (!defined('LCMS')) exit;

// Let's handle that url
function dispatch()
{

	 // NORMAL PAGE RETRIEVAL
	// First we are going to grab the url path and trim the / off
	// the left and the right.
	$url = trim($_GET['_url'], '/');

	// We changed this from before to make it easier to read and also so we can
	// work with easier variables.  using the list (http://php.net/list) function
	// will map the array indexes to the respected order of the var names inside
	// the list function call (better explanation at php.net/lists).
	list($directory, $action, $tertiary) = explode('/', $url);

	if($directory == "" && $action == "") {
		$directory = 'pages';
		$action = 'home';
	}

	if(file_exists('actions/' . $directory . '/actions.php')) {
		// normal page via directory/action/tertiary
		runPage($directory,$action,$tertiary);
	} else {

		// load static pages through slug (e.g. www.website.com/about -> www.website.com/pages/view/about/)
		runPage('pages','view',$directory);

		//require('templates/404.html');
		
	}
	
}

function runPage($directory,$action,$tertiary='') {
	// Now we drop in our respected actions & models file from the directory in the url
		require('actions/' . $directory . '/models.php');
		require('actions/' . $directory . '/actions.php');

		// if function isn't set, try index() function
		if($action == '') {
			$action = 'index';
		}

		if(function_exists($action)) {
			// And call the action from inside our actions.php file
			// and since we want to end our request with the return value of our requested method
			// let's just die on the return.
			$page = FALSE;
			try {
				if($tertiary == "") {
					$page = $action();
				} else {
					$page = $action($tertiary); // if tertiary value in url, send it through function
				}
			} 
			catch (Exception $e) {
				error_log_msg($e->getMessage());
				require('templates/500.html');
				die();
			}

			if($page === FALSE) {
				require('templates/404.html');
			} else {
				die($page);	
			}
		} else {
			require('templates/404.html');
		}
}
