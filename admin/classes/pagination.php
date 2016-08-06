<?php

/* * *******************************************************************
 * Part of intrepid, this is not to be considered a production system
 * Development started Dec 2015
 * Author: Benjamin Faulkner
 * ******************************************************************* */

/**
 * Description of pagination
 * Handles all the pagination we use throughout the system to make sure that we
 * are consistent with our code, also because pagination has a horrible, horrible
 * habbit of breaking in complicated ways, and I only want to fix it in one place.
 *  *
 * @author Ben
 */
class pagination {
	
	// DATA
	protected	$sql;
	protected	$sql_compiled;
	protected	$params;
	protected	$start;
	protected	$limit;
	public		$data;
	public		$total;
	
	// STRUCTURE
	public		$nextPage;
	public		$thisPage;
	public		$prevPage;
	
	
	
	/**
	 * Handles setting our sql etc, gets the actual data.
	 * @param type $sql
	 * @param type $params
	 * @param type $start
	 * @param type $limit
	 */
	public function __construct($sql, $params, $start=null, $limit=null){
		$this->start		= ($start != NULL) ? $start : 0;
		$this->limit		= ($limit != NULL) ? $limit : (int) settings::getValue('admin_default_per_page');
		$this->sql			= $sql;
		
		$this->sql_compiled	= $sql . " LIMIT " . $this->start . ", " . $this->limit;
		
		$this->data			= db::query($this->sql_compiled, $params)->fetchAll(PDO::FETCH_OBJ);
		
		// I don't like doing it this way - there must be a more slick way.
		$this->total		= count(db::query($sql, $params)->fetchAll(PDO::FETCH_OBJ));
		
		$this->calculatePages();
	}
	
	
	/**
	 * Handles working out what ints our current page is and the total pages.
	 */
	public function calculatePages(){
		$this->total	= (int) ceil(($this->total / $this->limit));
		$this->thisPage	= (($this->start + $this->limit) / $this->limit);
		if($this->thisPage == 0)
			$this->thisPage++;
		
		$this->createButtons();
	}
	
	
	/**
	 * Handles creating the ints for our buttons.
	 */
	public function createButtons(){
		$this->nextPage = ($this->thisPage < $this->total) ? ($this->thisPage +1) : $this->total;
		$this->prevPage = ($this->thisPage > 1) ? ($this->thisPage -1) : 1;
	}
}
