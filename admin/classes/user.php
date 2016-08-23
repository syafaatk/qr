<?php

/**********************************************************************
 * Part of intrepid, this is not to be considered a production system
 * Development started Dec 2015
 * Author: Benjamin Faulkner
 *********************************************************************/

/**
 * Description of user
 *
 * @author Ben
 */
class user {
	protected $user;
	
	
	/**
	 * Construct - Puts a user in to the user variable.
	 * @param int $id
	 */
	public function __construct($id=false) {
		if($id) {
			$this->user = db::query('SELECT * FROM `users` WHERE id=:id', array('id' => $id))->fetch(PDO::FETCH_OBJ);
			
			foreach($this->user as $key => $value) {
				$this->$key = $value;
			}
			
			unset($this->user);
			
			return $this;
		}
	}
	
	
	
	/**
	 * Gets the user from our class variable.
	 * @return user object
	 */
	public function getUser() {
		return $this;
	}
	
	
	
	public function incrementBlock() {
		db::query('UPDATE `users` SET `failed`=`failed`+1 WHERE id=:id', array('id' => $this->id))->fetchAll();
	}
	
	
	
	/**
	 * Opens our user from the session
	 * @return boolean
	 */
	public function getUserFromSession() {
		if(!security::checkLogin())
			return false;
		
		$params = array(
			'session_id'	=> session_id($_SESSION['INT_USER']),
			'salt'			=> $_SESSION['INT_USER']['salt']
		);
		$this->user = db::query("
			SELECT * FROM `users` 
			JOIN `user_sessions`
			ON `user_sessions`.`user` = `users`.`id` 
			WHERE `user_sessions`.`session_id` = :session_id
			AND `salt` = :salt", 
			$params)->fetch();
	
		return $this->user;
	}
	
	
	
	/**
	 * Opens our user from the session or gets a specific ID.
	 * @param type $id
	 */
	public static function fetchUser($id = null) {
		// Gets our user from the session
		if($id == NULL) {
			$params = array(
				'session_id'	=> session_id(),
				'salt'			=> $_SESSION['INT_USER']['salt']
			);
			$user = db::query("
				SELECT `users`.* FROM `users` 
				JOIN `user_sessions`
				ON `user_sessions`.`user` = `users`.`id` 
				WHERE `user_sessions`.`id` = :session_id
				AND `user_sessions`.`salt` = :salt", 
				$params)->fetch(PDO::FETCH_OBJ);
			
			$user->id;
		}
		else {
			$params = array('id' => $id);
			$user	= db::query("SELECT * FROM `users` WHERE `id` = :id", $params)->fetch();
		}
		
		return $user;
	}
}
