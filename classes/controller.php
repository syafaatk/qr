<?php

/***********************************************************************
 * Part of intrepid, this is not to be considered a production system
 * Development started Dec 2015
 * Author: Benjamin Faulkner
 **********************************************************************/


class controller
{
	private static	$admin = false;
	
	/**
	 * Includes all our required files
	 */
	function __construct()
	{
		
		require_once(__DIR__.'/database.php');
		require_once(__DIR__.'/settings.php');
		require_once(__DIR__.'/structure.php');
		require_once(__DIR__.'/handlers.php');	
		require_once(__DIR__.'/../admin/classes/user.php');
		require_once(__DIR__.'/../libs/phpqrcode/qrlib.php');

	}
	
	
	/**
	 * Sets if we're an admin or not
	 * @param boolean $admin
	 */
	public static function setAdmin($admin)
	{
		self::$admin = $admin;
		
		if(session_status() != PHP_SESSION_ACTIVE)
			session_start();
		
		if($admin == true)
		{
			require_once(__DIR__.'/../admin/classes/AdminStructure.php');
			require_once(__DIR__.'/../admin/classes/security.php');
			require_once(__DIR__.'/../admin/classes/fields.php');
		}
		
		if(security::checkLogin() == FALSE)
			header('Location: login.php');
	}
	
}

new controller;