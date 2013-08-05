<?php
if (!defined('LCMS')) exit;

// We simply define the function (the second item in our request url)
// making sure it is the same name as the one in the url.
function login()
{
	$fbauth = new FBAuth();
	if($fbauth->isLoggedIn) {
		redirect(ROOT_URL);
	} else {
		$fbauth->login();
	}
}

function logout() 
{
	$fbauth = new FBAuth();
	$fbauth->logout();
}

?>
