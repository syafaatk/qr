<?php

/* * *******************************************************************
 * Part of intrepid, this is not to be considered a production system
 * Development started Dec 2015
 * Author: Benjamin Faulkner
 * ******************************************************************* */

/**
 * Description of handlers
 *
 * @author Ben
 */
class handlers {
	/**
	 * Sets the message in to our session variable.
	 * @param type $message
	 * @param type $level
	 */
	public static function setMessage($message, $level) {
		$_SESSION['INT_USER']['messages'][] = array(
			'level'		=> $level,
			'message'	=> $message
		);
	}
	
	
	
	/**
	 * Sets an error using setMessage.
	 * @param type $message
	 */
	public static function setError($message) {
		self::setMessage($message, 'danger');
	}
	
	
	
	/**
	 * Sets an info message using setMessage.
	 * @param type $message
	 */
	public static function setInfo($message) {
		self::setMessage($message, 'info');
	}
	
	
	
	/**
	 * Sets a warning using setMessage
	 * @param type $message
	 */
	public static function setWarning($message) {
		self::setMessage($message, 'warning');
	}
	
	
	
	/**
	 * Sets a success message using setMessage.
	 * @param type $message
	 */
	public static function setSuccess($message) {
		self::setMessage($message, 'success');
	}
	
	
	
	/**
	 * Output messages from the sessions array.
	 * @return type
	 */
	public static function outputMessages() {
		if(empty($_SESSION['INT_USER']['messages']))
			return false;
		
		$messages	= $_SESSION['INT_USER']['messages'];
		$html		= '';
		
		foreach($messages as $message) {
			$html .= <<<HTML
			<div class="alert alert-{$message['level']}" role="alert">{$message['message']}</div>
HTML;
		}
		
		$_SESSION['INT_USER']['messages'] = array();
		
		return $html;
	}
}
