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
class blogs {
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
			$this->getBlog($id);
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
	
	
	
	public function getBlog($id){
		$blog = db::query("SELECT * FROM `blogs` WHERE `id`=:id", array('id' => $id))->fetch(PDO::FETCH_OBJ);
		
		$this->id		= $blog->id;
		$this->author	= $blog->author;
		$this->title	= $blog->title;
		$this->date		= $blog->date;
		$this->content	= $blog->content;
		$this->status	= $blog->status;
		
		return $blog;
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
			$sql = "INSERT INTO `blogs` (`author`, `title`, `content`, `status`) VALUES (:author, :title, :content, :status)";
		else
		{
			$params['id'] = $this->id;
			$sql = "UPDATE `blogs` SET `author`=:author, `title`=:title, `content`=:content,`status`=:status WHERE `id`=:id";
		}
			
		if(db::query($sql, $params))
			handlers::setSuccess('Blog saved successfully.');
		else
			handlers::setError('Blog could not be saved.');
	}
}
