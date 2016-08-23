<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of fields
 *
 * @author Ben
 */
class fields {
	protected $item;
	
	
	public function __construct(items $item){
		$this->item = $item;
	}
	
	
	
	
	public function getGroups(){
		return db::Query("SELECT DISTINCT(`grouping`) FROM `fields` WHERE `item` = :item", array('item' => $this->item->id))->fetch();
	}
	
	
	
	
	public function getGroup($group){
		return db::Query("SELECT * FROM `fields` WHERE `item` = :item AND `grouping` = :group", array('item' => $this->item->id, 'group' => $group))->fetch();
	}
}
