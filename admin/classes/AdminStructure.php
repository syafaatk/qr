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
class AdminStructure extends structure
{
	/**
	 * Builds our header from all other parts.
	 * @return type
	 */
	protected function header()
	{
		$title			= settings::getValue('site-title');
		$base			= settings::getValue('site-base');
		$description	= settings::getValue('meta-description');
		
		$this->addCSSFile('css/dashboard.css');
		$this->addCSSFile('css/main.css');
		
		$javascript		= $this->buildJavascript();
		$css			= $this->buildCSS();
		$navigation		= $this->navigation();
		
		
		$html = <<<HTML
			<!DOCTYPE HTML>					
				<html lang="en">
					<head>
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
						<title>{$title} - Admin</title>
						<base href="{$base}admin/"/>
						<meta name=”description” content=”{$description}”>

						<!-- Bootstrap core CSS -->
						<link href="../css/bootstrap.min.css" rel="stylesheet">
						<!-- Custom styles for this template -->
						<link href="css/dashboard.css" rel="stylesheet">

						<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
						<!--[if lt IE 9]>
						  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
						  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
						<![endif]-->
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
		$sitebase = settings::getValue('site-base');
		
		$html = <<<SIDEBAR
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-3 col-md-2 sidebar">
						<ul class="nav nav-sidebar">
							<li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
							<li><a href="blogs.php">Blogs</a>
								<ul class="nav submenu">
									<li><a href="view-blogs.php">View Blogs</a></li>
									<li><a href="blogs.php">Add Blog</a></li>
								</ul>
							</li>
							<li><a href="pages.php">Pages</a></li>
								<ul class="nav submenu">
									<li><a href="view-pages.php">View Pages</a></li>
									<li><a href="pages.php">Add Page</a></li>
								</ul>
							<li><a href="#">Comments</a></li>
							<li><a href="#">Contacts</a></li>
							<li><a href="view-settings.php">Settings</a></li>
							<hr/>
							<li><a href="{$sitebase}">View Site</a></li>
							<li><a href="#">Logout</a></li>
						</ul>
		
					</div>
		
					<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
SIDEBAR;
		
		return $html;
	}
	
	
	
	/**
	 * Our HTML footer for the print page.
	 */
	protected function footer()
	{
		$html = <<<HTML
					</div>
				</div>
			</body>
		</html>
HTML;
		
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
