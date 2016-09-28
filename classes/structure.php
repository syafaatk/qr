<?php

/* * *******************************************************************
 * Part of intrepid, this is not to be considered a production system
 * Development started Dec 2015
 * Author: Benjamin Faulkner
 * ******************************************************************* */

/**
 * This class handles our page structure
 * I appreciate most people say singletons are a bad idea
 * I believe in this situation it is a good purpose.
 * We want only one instance of it.
 *
 * @author Ben
 */
class structure {
	protected		$javascriptFiles	= array();
	protected		$javascriptInline	= array();
	protected		$cssFiles			= array();
	protected		$cssInline			= array();
	protected		$content			= '';
	private static	$_instance			= null;
	protected		$bootstrap			= false;
	protected		$structure			= true;
	protected		$navigation			= true;
	private static	$admin				= false;


	private function  __construct() { }
	private function  __clone() { } 

	public static function getInstance($admin=false)
	{
		if(!is_object(self::$_instance)) {
			self::$admin = $admin;
			
			if(self::$admin == true && !security::checkLogin()) {
				header('Location: login.php');
				die();
			}
			$className			= get_called_class();
			self::$_instance	= new $className;
			
		}
		
		return self::$_instance;
	}	
	


	/**
	 * Adds a CSS file
	 * @param type $file
	 */
	public function addCSSFile($file)
	{
		$this->cssFiles[] = $file;
	}
	
	
	
	/**
	 * Adds a CSS Inline
	 * @param type $inline
	 */
	public function addCSSInline($inline)
	{
		$this->cssInline[] = $inline;
	}
	
	
	
	/**
	 * Adds a JS file
	 * @param type $file
	 */
	public function addJSFile($file)
	{
		
		// We include JQuery if there are any JS files, because we pretty much never use anything else.
		if(empty($this->javascriptFiles))
			$this->javascriptFiles[] = 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js';
		
		$this->javascriptFiles[] = $file;
	}
	
	
	
	/**
	 * Adds a JS Inline
	 * @param type $inline
	 */
	public function addJSInline($inline)
	{
		$this->javascriptInline[] = $inline;
	}
	
	
	
	/**
	 * Builds a string from our array of javascripts
	 * @return type
	 */
	protected function buildJavascript()
	{
		$html = '';
		
		// Foreach javascript file add it in as "script"
		if(!empty($this->javascriptFiles))
		{	
			foreach($this->javascriptFiles as $file)
			{
				$html .= <<<JSFILES
					<script src="{$file}"></script>
JSFILES;
			}
		}
		
		// Do the same for our inline javascript
		if(!empty($this->javascriptInline))
		{
			foreach($this->javascriptInline as $inline)
			{
				$html .= <<<JSINLINE
					<script>
						{$inline}
					</script>
JSINLINE;
			}
		}
		
		return $html;
	}
	
	
	
	/**
	 * Builds our CSS string from the CSS array.
	 * @return type
	 */
	protected function buildCSS()
	{
		$html = '';
		
	
		// We always want our main css files
		if(!in_array('css/main.css', $this->cssFiles) && !self::$admin)
			$this->addCSSFile('css/main.css');
		
		// Foreach javascript file add it in as "script"
		if(!empty($this->cssFiles))
		{
			
			foreach($this->cssFiles as $file)
			{
				$html .= <<<CSSFILES
					<link rel="stylesheet" href="{$file}"/>
CSSFILES;
			}
		}
		
		// Do the same for our inline javascript
		if(!empty($this->cssInline))
		{
			foreach($this->cssInline as $inline)
			{
				$html .= <<<CSSINLINE
					<style>
						{$inline}
					</style>
CSSINLINE;
			}
		}
		
		return $html;
	}
	
	
	
	/**
	 * Add content to our page.
	 * @param type $content
	 */
	public function addContent($content)
	{
		$this->content = $this->content . $content;
	}
	
	
	
	/**
	 * Sets the structure variable.
	 * @param type $structure
	 */
	public function setStructure($structure = true) {
		$this->structure = $structure;
	}
	
	
	
	/**
	 * Get structure variable from this class
	 * @return type
	 */
	public function getStructure() {
		return $this->structure;
	}
	
	
	
	/**
	 * Sets the structure variable.
	 * @param type $structure
	 */
	public function setNavigation($navigation = true) {
		$this->navigation = $navigation;
	}
	
	
	
	/**
	 * Get structure variable from this class
	 * @return type
	 */
	public function getNavigation() {
		return $this->navigation;
	}
	
	
	
	/**
	 * Sets whether or not we are using bootstrap.
	 * @param boolean $bootstrap
	 */
	public function setBootstrap($bootstrap=true)
	{
		$this->bootstrap = $bootstrap;
	}
	
	
	
	public function getBootstrap()
	{
		if($this->bootstrap == false)
			return false;
		
		$this->addJSFile('js/bootstrap.min.js');
		
		if(!in_array('css/bootstrap.min.css', $this->cssFiles))
			$this->addCSSFile('css/bootstrap.min.css');
		
		if(!in_array('css/bootstrap-theme.css', $this->cssFiles))
			$this->addCSSFile('css/bootstrap-theme.css');
		
		$this->addCSSFile('https://fonts.googleapis.com/css?family=Open+Sans:400,800');
		$this->addCSSFile('https://fonts.googleapis.com/css?family=Lobster');
		
		return true;
	}
	
	
	
	/**
	 * Builds our header from all other parts.
	 * @return type
	 */
	protected function header()
	{
		$title			= settings::getValue('site-title');
		$base			= settings::getValue('site-base');
		$description	= settings::getValue('meta-description');
		$javascript		= $this->buildJavascript();
		$css			= $this->buildCSS();
		$navigation		= ($this->getNavigation()) ? $this->navigation() : '';
		
		
		$html = <<<HTML
			<!DOCTYPE HTML>

			<html>
				<head>
					<title>{$title}</title>
					<base href="{$base}"/>
					<meta name=”description” content=”{$description}”>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					{$javascript}
					{$css}
				</head>
				
				<body>
					{$navigation}
HTML;
		return $html;
	}
	
	
	
	
	protected function navigation()
	{
		$html = <<<NAVIGATION
			<nav class="navbar navbar-default navbar-fixed-top">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#">Inventory</a>
					</div>
					<div id="navbar" class="nav navbar-nav navbar-right navbar-collapse collapse" style="box-shadow: none;">
						
							<form class="navbar-form navbar-left" action="./search/" method="GET">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Search for..." name="search" style="height: 33px;">
									<span class="input-group-btn ">
										<button class="btn btn-default" type="button">Go!</button>
									</span>
								</div>
							</form>
						
						<ul class="nav navbar-nav">
							<li class="active"><a href="./">Items</a></li>
							<li><a href="./admin/">Admin</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</nav>
NAVIGATION;
		
		return $html;
	}
	
	
	
	/**
	 * Our HTML footer for the print page.
	 */
	protected function footer()
	{
		$html = <<<HTML
			</body>
		</html>
HTML;
		
		return $html;
	}
	
	
	
	/**
	 * puts our content in to the container.
	 * @param type $content
	 */
	public function content()
	{
		$html  = handlers::outputMessages();
		$html .= $this->content;
		return $html;
	}
	
	
	
	/**
	 * Prints our actual structure.
	 */
	public function printPage()
	{
		// We check if bootstrap should be used.
		$this->getBootstrap();
		
		if($this->getStructure()) {
			$html = <<<HTML
				{$this->header()}
				{$this->content()}
				{$this->footer()}
HTML;
		}
		else {
			$html = $this->content();
		}
		
		echo $html;
	}
}
