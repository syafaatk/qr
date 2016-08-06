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
class pages {
	public $id;
	public $author;
	public $title;
	public $date;
	public $content;
	public $status;
	
	const MODE_CREATE = 1;
	const MODE_UPDATE = 2;
	
	
	
	public function __construct($id=null){
		if($id != null){
			$this->getPage($id);
		}
		else {
			$this->id		= null;
			$this->author	= user::fetchUser()->id;
			$this->title	= '';
			$this->date		= date("Y-m-d H:i:s");
			$this->content	= '';
			$this->status	= '1';
		}
	}
	
	
	
	public function getPage($id){
		$page = db::query("SELECT * FROM `pages` WHERE `id`=:id", array('id' => $id))->fetch(PDO::FETCH_OBJ);
		
		$this->id		= $page->id;
		$this->author	= $page->author;
		$this->title	= $page->title;
		$this->date		= $page->date;
		$this->content	= $page->content;
		$this->status	= $page->status;
		
		return $page;
	}
	
	
	
	public function save(){
		$mode = SELF::MODE_UPDATE;
		
		if($this->id == null)
			$mode = SELF::MODE_CREATE;
		

		$params = array(
			'author'	=> $this->author,
			'title'		=> $this->title,
			'content'	=> $this->content,
			'status'	=> $this->status
		);
		
		
		if($mode == SELF::MODE_CREATE)
			$sql = "INSERT INTO `pages` (`author`, `title`, `content`, `status`) VALUES (:author, :title, :content, :status)";
		else
		{
			$params['id'] = $this->id;
			$sql = "UPDATE `pages` SET `author`=:author, `title`=:title, `content`=:content,`status`=:status WHERE `id`=:id";
		}
			
		if(db::query($sql, $params))
			handlers::setSuccess('Page saved successfully.');
		else
			handlers::setError('Page could not be saved.');
	}
}
