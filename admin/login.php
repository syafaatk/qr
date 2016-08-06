<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('../classes/controller.php');



if(isset($_POST['username']))
{
	require_once('classes/security.php');
	if(security::checkCredentials($_POST['username'], $_POST['password'])) {
		security::loginUser($_POST['username'], $_POST['password']);
		
		handlers::setSuccess('Logged in successfully.');
		header('Location: index.php');
	}
}

$content = <<<CONTENT
	<div class="container">
		<div class="col-xs-3"></div>
		<div class="col-xs-6">
			<form class="form-signin" action="admin/login.php" method="POST">
				<h2 class="form-signin-heading">Please sign in</h2>
				<label for="inputUsername" class="sr-only">Username</label>
				<input type="username" id="inputUsername" name="username" class="form-control" placeholder="Username" required autofocus>
				<label for="inputPassword" class="sr-only">Password</label>
				<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>

				<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
			</form>
		</div>
		<div class="col-xs-3"></div>
	</div> <!-- /container -->

CONTENT;


$p = structure::getInstance();
$p->setNavigation(false);
$p->setBootstrap(true);
$p->addContent($content);
$p->printPage();