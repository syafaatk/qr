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
		
		require_once('database.php');
		require_once('settings.php');
		require_once('structure.php');
		require_once('handlers.php');
		require_once('admin/classes/user.php');
		require_once('libs/phpqrcode/qrlib.php');
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
			require_once('classes/AdminStructure.php');
			require_once('classes/security.php');
		}
	}
	
}

new controller;