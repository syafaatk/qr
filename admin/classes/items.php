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
class items {
	public $id;
	public $author;
	public $title;
	public $date;
	public $content;
	public $hackable;
	public $pat;
	public $manual;
	public $status;
	
	const MODE_CREATE = 1;
	const MODE_UPDATE = 2;
	
	
	
	public function __construct($id=null){
		if($id != null){
			$this->getItem($id);
		}
		else {
			$this->id		= null;
			$this->author	= user::fetchUser()->id;
			$this->title	= '';
			$this->date		= date("Y-m-d H:i:s");
			$this->content	= '';
			$this->manual   = null;
			$this->pat      = null;
			$this->hackable = null;
			$this->status	= '1';
		}
	}
	
	
	
	public function getItem($id){
		$item = db::query("SELECT * FROM `items` WHERE `id`=:id", array('id' => $id))->fetch(PDO::FETCH_OBJ);
		
		$this->id		= $item->id;
		$this->author	= $item->author;
		$this->title	= $item->title;
		$this->date		= $item->date;
		$this->content	= $item->content;
		$this->pat	    = $item->pat;
		$this->manual	= $item->manual;
		$this->hackable = $item->hackable;
		$this->status	= $item->status;
		
		return $item;
	}
	
	
	
	public function save(){
		$mode = SELF::MODE_UPDATE;
		
		if($this->id == null)
			$mode = SELF::MODE_CREATE;
		

		$params = array(
			'author'	=> $this->author,
			'title'		=> $this->title,
			'content'	=> $this->content,
			'status'	=> $this->status,
			'pat'       => $this->pat,
			'manual'    => $this->manual,
			'hackable'  => $this->hackable
		);
		
		if($mode == SELF::MODE_CREATE)
			$sql = "INSERT INTO `items` (`author`, `title`, `content`, `pat`, `manual`, `hackable`, `status`) VALUES (:author, :title, :content, :pat, :manual, :hackable, :status)";
		else
		{
			$params['id'] = $this->id;
			$sql = "UPDATE `items` SET `author`=:author, `title`=:title, `content`=:content, `pat`=:pat, `manual`=:manual, `hackable`=:hackable, `status`=:status WHERE `id`=:id";
		}
			
		if(db::query($sql, $params))
			handlers::setSuccess('Item saved successfully.');
		else
			handlers::setError('Item could not be saved.');
	}
}
