<?php
if (!defined('LCMS')) exit;

class FBAuth {
	private $sitekey;

	public function __construct() {
		$this->appId = '450513841712576';
		$this->secret = '93e8e65c06f8e6df58f51552c46193d2';

		$this->facebook = new Facebook(array(
		  'appId'  => '450513841712576',
		  'secret' => '93e8e65c06f8e6df58f51552c46193d2',
		));

		$this->user = $this->facebook->getUser();

	}

	public function isAdmin()
	{	
		/*if(isset($_SESSION['user_id'])) {
			$this->query('SELECT id,admin,enabled FROM users WHERE id = :id');
			$this->bind(':id', $_SESSION['user_id']);
			$selection = $this->single(); // get the record

			//$selection being the array of the row returned from the database.
			if($selection['admin'] == 1) {
				return true;
			}
		}
		
			*/
		return false;
	}

	public function login() {
		$url = $this->facebook->getLoginUrl( array(
                'scope'         => 'email,user_birthday,user_location,user_work_history,user_about_me,user_hometown',
                'redirect_uri'  => ROOT_URL
            )
);
		redirect($url);
	}

	public function logout() 
	{
		$url = $this->facebook->getLogoutUrl(array(
			'redirect_uri' => ROOT_URL
			));
		redirect($url);

		// destroy session
		return session_destroy();
		
	}

	public function isLoggedIn()
	{
		if ($this->user) {
		  try {
		    // Proceed knowing you have a logged in user who's authenticated.
		    $user_profile = $this->facebook->api('/me');
		    //return $user_profile;
		    return true;
		  } catch (FacebookApiException $e) {
		    error_log($e);
		    $this->user = null;
		    return false;
		  }
		}
	}

	public function getUser()
	{
		if ($this->user) {
		  try {
		    // Proceed knowing you have a logged in user who's authenticated.
		    $user_profile = $this->facebook->api('/me');
		    return $this->user;
		  } catch (FacebookApiException $e) {
		    error_log($e);
		    $this->user = null;
		    return false;
		  }
		}
	}

	public function getUserProfile()
	{
		if ($this->user) {
		  try {
		    // Proceed knowing you have a logged in user who's authenticated.
		    $user_profile = $this->facebook->api('/me');
		    return $user_profile;
		    return true;
		  } catch (FacebookApiException $e) {
		    error_log($e);
		    $this->user = null;
		    return false;
		  }
		}
	}
}

 
?>