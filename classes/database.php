<?php
/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/
class db {
    const   DB_NAME     = 'qr';
    const   DB_USERNAME = 'root';
    const   DB_PASSWORD = '';
    
	
	
	/**
	 * Our database handler
	 */
    private static function handler()
	{
		try 
		{
			$dbh = new PDO('mysql:dbname=' . SELF::DB_NAME . ';host=127.0.0.1', SELF::DB_USERNAME, SELF::DB_PASSWORD, array(
				PDO::ATTR_PERSISTENT	=> true));
			$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} 
		catch (PDOException $e) 
		{
			echo 'Connection failed: ' . $e->getMessage();
			exit;
		}
		
		return $dbh;
	}
	
	
	
	/**
	 * Does our basic query
	 * @param string $sql
	 * @param array $parameters
	 */
	public static function query($sql, array $parameters=array())
	{
		// Get our values
		$dbh = self::handler();
		
		// Create our statement
		$statement = $dbh->prepare($sql);
		
		// Associate the parameters
		if(!empty($parameters))
		{
			foreach($parameters as $token => $value)
			{
				// We use bind parameters 
				$statement->bindValue(':' . $token, $value);
			}
		}
		
		// Execute our query
		try {
			$statement->execute();
		}
		catch (PDOException $e) {
			handlers::setError($e->getMessage());
		}
		catch (Exception $e) {
			handlers::setError($e->getMessage());
		}
		
		// Return our statement
		return $statement;
	}
	
	
	
	public static function lastInserted(){
		$dbh = self::handler();
		return $dbh->lastInsertId();
	}
    
	
	
}
