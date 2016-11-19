<?php

/* * *******************************************************************
 * Part of intrepid, this is not to be considered a production system
 * Development started Dec 2015
 * Author: Benjamin Faulkner
 * ******************************************************************* */

/**
 * Description of security
 *
 * In this class we try and handle user accounts as securely as possible. 
 * We don't store our user in the session - this seems risky to me instead 
 * our class stores the session ID in the database and the microtime it was 
 * created. Doing it this way should make it that you cannot just refresh a
 * page to log in.
 * 
 * @author Ben
 */
class security {
	/**
	 * 
	 * @param type $username
	 * @param type $password
	 * @return boolean
	 */
	public static function checkCredentials($username, $password) {
		// Get our username and ID from the database (note we are not getting password at this point), I think this may be a redundant step.
		$count = db::query("SELECT `username`, `id` FROM `users` WHERE `username` = :username", array('username' => $username))->fetch(PDO::FETCH_ASSOC);

		// If no user, we return false.
		if(empty($count))
		{
			handlers::setError('Wrong username or password!');
			
			return false;
		}
		
		// Create our user variable.
		$user = new user($count['id']);
		//$user = $user->getUser();
		
		// Check our user isn't blocked
		if($user->failed > (int) settings::getValue('failed_login_attempts') && (int) settings::getValue('failed_login_attempts') != 0)
		{
			handlers::setError('Sorry the user account you are trying to access has been blocked due to multiple failed logins.');
			return false;
		}

		// Use our variable to check the password and salt.
		if(hash('sha512', $password . $user->salt) !== $user->password)
		{
			$user->incrementBlock();
			handlers::setError('Wrong username or password!');
			return false;
		}
		return true;
	}
	
	
	
	/**
	 * Checks whether the current session id is in the user_sessions table, if not, the login fails.
	 * @return boolean
	 */
	public static function checkLogin() {
		if(session_status() != PHP_SESSION_ACTIVE)
			session_start();
		
		if(!isset($_SESSION['INT_USER']))
			return false;
		
		// Delete all rows that are older than 20 minutes.
		db::query("DELETE FROM `user_sessions` WHERE `time` < (NOW() - INTERVAL ".settings::getValue('session_timeout_age')." MINUTE)")->fetch(PDO::FETCH_ASSOC);
		
		$params = array('session_id' => session_id(), 'salt' => $_SESSION['INT_USER']['salt']);
		
		$count	= db::query("SELECT `id`, `salt`, `user` FROM `user_sessions` WHERE `id` = :session_id AND `salt` = :salt", $params)->fetch(PDO::FETCH_ASSOC);
		
		if(empty($count) || $count == null) {
			handlers::setError('Your session timed out.');
			return false;
		}
		else {
			
			$params = array('session_id' => session_id(), 'time' => date("Y-m-d H:i:s"));
			db::query("UPDATE `user_sessions` SET `time`=:time WHERE `id` =:session_id", $params)->fetch(PDO::FETCH_ASSOC);
			return true;
		}
	}
	
	
	
	/**
	 * Takes our username and password and creates the login with it.
	 * @param type $username
	 * @param type $password
	 * @return boolean
	 */
	public static function loginUser($username, $password) {
		// If our check credentials are false, we return false - don't log in.
		if(!self::checkCredentials($username, $password))
			return false;
		
		// Get our user out of the database.
		$user_source	= db::query("SELECT `id` FROM `users` WHERE `username` = :username", array('username' => $username))->fetch(PDO::FETCH_ASSOC);
		$user			= new user($user_source['id']);
		$user			= $user->getUser();
		
		session_start();
		$_SESSION['INT_USER']['salt'] = microtime(true);
		
		$params = array(
			'id'	=> session_id(),
			'salt'	=> $_SESSION['INT_USER']['salt'],
			'user'	=> $user->id
		);
		
		// Store our session id and salt, and what user it coincides with, in the database.
		db::query("INSERT INTO `user_sessions` (`id`, `salt`, `user`) VALUES (:id, :salt, :user)", $params)->fetch(PDO::FETCH_ASSOC);
	
		return true;
	}
	
	
	

	/**
	 * creates a new user login.
	 * @param type $username
	 * @param type $password
	 * @return boolean
	 */
	public static function createLogin($username, $password) {
		// Get our username and ID from the database (note we are not getting password at this point), I think this may be a redundant step.
		$count = db::query("SELECT `username`, `id` FROM `users` WHERE `username` = :username", array('username' => $username))->fetch(PDO::FETCH_ASSOC);

		// If no user, we return false.
		if(!empty($count))
		{
			handlers::setError('Username already exists!');	
			return false;
		}
		
		
		// Hash our password
		$salt     = substr(uniqid('', true), -4);
		$password = hash('sha512', $password . $salt);
		// Create the username/password
		db::query("INSERT INTO `users` (`username`, `password`, `salt`) VALUES (:username, :password, :salt)", array('username' => $username, 'password' => $password, 'salt' => $salt));
		
		
		return true;
	}
}
