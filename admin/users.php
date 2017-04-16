<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('../classes/controller.php');
require_once('classes/user.php');
controller::setAdmin(true);


$newUser           = new stdClass('user');
$newUser->id       = NULL;
$newUser->username = NULL;

$user = (isset($_REQUEST['id'])) ? new user($_REQUEST['id']) : $newUser;


if(isset($_POST['username'])) {
	$user->username	= $_POST['username'];
	$user->password	= $_POST['password'];
	security::createLogin($_POST['username'], $_POST['password']);
	$loadMe = (db::lastInserted() == 0) ? $_POST['id'] : db::lastInserted();
	$user = new user($loadMe);
}


$content = <<<CONTENT
<section>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 main-content">
				<h4>Items</h4>
				
				<form action="admin/users.php" method="POST">
					<input type="hidden" name="id" value="{$user->id}"/>
					<fieldset class="form-group">
						<input type="text" name="username" class="form-control" placeholder="Username" value="{$user->username}" />
					</fieldset>
					<fieldset class="form-group">
						<input type="password" name="password" class="form-control" placeholder="Password" value="" />
					</fieldset>
						
					<fieldset class="form-group">
						<button type="submit" class="btn btn-primary">Save</button>
					</fieldset>					
				</form>
			</div>
		</div>
	</div>
</section>

<section>
</section>
CONTENT;



$p = AdminStructure::getInstance(true);
$p->setBootstrap(true);
$p->addJSFile('js/ckeditor/ckeditor.js');
$p->addContent($content);
$p->printPage();