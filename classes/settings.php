<?php

/* * *******************************************************************
 * Part of intrepid, this is not to be considered a production system
 * Development started Dec 2015
 * Author: Benjamin Faulkner
 * ******************************************************************* */

class settings 
{	
	public $key_name;
	public $value;
	
	
	// For legacy we have both modes here, but in reality we will probably only ever use update.
	const MODE_CREATE = 1;
	const MODE_UPDATE = 2;
	
	
	/**
	 * Gets a setting value out of the database.
	 * @param string $key
	 * @return mixed
	 */
	public static function getValue($key)
	{
		return db::query('SELECT value FROM `settings` WHERE `key_name`=:key', array('key' => $key))->fetch(PDO::FETCH_ASSOC)['value'];
	}
	
	
	
	/**
	 * Gets the entire item from the database.
	 * @param type $key
	 * @return type
	 */
	public function getSetting($key){
		return db::query('SELECT * FROM `settings` WHERE `key_name`=:key', array('key' => $key))->fetch(PDO::FETCH_OBJ);
	}
	
	
	public function save(){
		$mode = SELF::MODE_UPDATE;
		

		$params = array(
			'key_name'	=> $this->key_name,
			'value'		=> $this->value
		);
		
		
		if($mode == SELF::MODE_UPDATE)
		{
			$sql = "UPDATE `settings` SET `value`=:value WHERE `key_name`=:key_name";
		}
			
		if(db::query($sql, $params))
			handlers::setSuccess('Setting saved successfully.');
		else
			handlers::setError('Setting could not be saved.');
	}
	
}
