<?php
if (!defined('LCMS')) exit;

class Auth extends Database {
	private $sitekey;

	public function __construct() {
		parent::__construct();
		$this->siteKey = 'YV6bTWP9vDn6X86iTlkVc67YDoUcB5Z1';
	}

	private function randomString($length = 50)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$string = '';    
			
		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters))];
		}
			
	      	return $string;
	}

	protected function hashData($data)
    {
		return hash_hmac('sha512', $data, $this->_siteKey);
	}

	public function isAdmin()
	{	
		if(isset($_SESSION['user_id'])) {
			$this->query('SELECT id,admin,enabled FROM users WHERE id = :id');
			$this->bind(':id', $_SESSION['user_id']);
			$selection = $this->single(); // get the record

			//$selection being the array of the row returned from the database.
			if($selection['admin'] == 1) {
				return true;
			}
		}
		
			
		return false;
	}

	public function restrictAccess($admin=FALSE) {
		if(isset($_SESSION['user_id'])) {
			// logged in
			if($admin == TRUE && $this->isAdmin()) {
				// authorized at admin level
			} 
			elseif($admin==TRUE) {
				// unauthorized access
				require('templates/403.html');
				die();
				return false;
			}

		} else {
			// force log in
			redirect(ROOT_URL . 'auth/login/?r=' . urlencode(url()) );
			return false;
		}
	}

	public function createUser($email, $password, $is_admin = 0)
	{			
		//Generate users salt
		$user_salt = $this->randomString();
				
		//Salt and Hash the password
		$password = $user_salt . $password;
		$password = $this->hashData($password);
				
		//Create verification code
		$code = $this->randomString();

		//Commit values to database here.
		$this->query('INSERT INTO users (email, password, salt, admin) VALUES (:email, :pass, :salt, :admin)');
		$this->bind(':email', $email);
		$this->bind(':pass', $password);
		$this->bind(':salt', $user_salt);
		$this->bind(':admin', $is_admin);

		$created = $this->execute();

		if($created != false){
			return true;
		}
				
		return false;
	}



	public function loginUser($email,$pass) {
		// load the user record at $email
		$this->query('SELECT id,email,password,salt,admin,enabled,lastlogin FROM users WHERE email = :email');
		$this->bind(':email', $email);
		$user = $this->single(); // get the record

		$comp_pass = $this->hashData($user['salt'].$pass); // generate a comparison password

		if($this->rowCount() == 1) { // if the user exists
			if($user['password'] == $comp_pass) { // if password matches
				if($user['enabled']==true) { // check the account is enabled
					
 					$random = $this->randomString();
					//Build the token
					$token = $_SERVER['HTTP_USER_AGENT'] . $random;
					$token = $this->hashData($token);
		            
		            session_start();
					$_SESSION['token'] = $token;
					$user_id = preg_replace("/[^0-9]+/", "", $user[id]); // XSS protection 
		            $_SESSION['user_id'] = $user['id']; 
						
					//Delete old logged_in records for user
		            $this->query('DELETE FROM logged_in WHERE user_id = :id');
		            $this->bind(':id', $user['id']);
		            $this->execute();
					
					//Insert new logged_in record for user
					$this->query('INSERT INTO logged_in(user_id,session_id,token) VALUE (:user_id,:session_id,:token)');
					$this->bind(':user_id', $user['id']);
					$this->bind(':session_id', session_id());
					$this->bind(':token',$token);

					$inserted = $this->execute();

					//Logged in
					if($inserted != false) {
						security_log("LOGIN USER:" . $user['id'] . "/" . $user['email'] . " IP:" . $_SERVER['REMOTE_ADDR'] );
						return 0;
					} 
				} else {
					// account disabled

					// TODO log attempt

					return 1;
				}
			} else {
				// password incorrect
				return 2;
			}
		} else {
			// user doesnt exist
			return 3;
		}

	}

	public function checkSession()
	{
		$this->query('SELECT session_id,user_id,token FROM logged_in WHERE user_id = :id');
		$this->bind(':id', $_SESSION['user_id']);
		$selection = $this->single(); // get the record
		
		if($selection) {
			//Check ID and Token

			if(session_id() == $selection['session_id'] && $_SESSION['token'] == $selection['token']) {
				//Id and token match, refresh the session for the next request
				$this->refreshSession();
				return true;
			}
		}
			
		return false;
	}

	private function refreshSession()
	{
		//Regenerate id
		session_regenerate_id();
		
		//Regenerate token
		$random = $this->randomString();
		//Build the token
		$token = $_SERVER['HTTP_USER_AGENT'] . $random;
		$token = $this->hashData($token); 
			
		//Store in session
		$_SESSION['token'] = $token;

		//Delete old logged_in records for user
		$uid = $_SESSION['user_id'];
        $this->query('DELETE FROM logged_in WHERE user_id = :id');
        $this->bind(':id', $uid);
        $this->execute();
		
		//Insert new logged_in record for user
		$this->query('INSERT INTO logged_in(user_id,session_id,token) VALUE (:user_id,:session_id,:token)');
		$this->bind(':user_id', $uid);
		$this->bind(':session_id', session_id());
		$this->bind(':token',$token);
		$this->execute();
	}

	public function logout() 
	{

		// eradicate logged_in records
        $this->query('DELETE FROM logged_in WHERE user_id = :id');
        $this->bind(':id', $_SESSION['user_id']);
        $this->execute();

		// destroy session
		return session_destroy();
		
	}
}

 
?>