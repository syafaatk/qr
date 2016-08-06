<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('../classes/controller.php');
require_once('classes/pages.php');
controller::setAdmin(true);

$page = (isset($_REQUEST['id'])) ? new pages($_REQUEST['id']) : new pages();

if(isset($_POST['title'])) {
	
	$page->title	= $_POST['title'];
	$page->content	= $_POST['content'];
	$page->save();
	$loadMe = (db::lastInserted() == 0) ? $_POST['id'] : db::lastInserted();
	$page = new pages($loadMe);
}

$content = <<<CONTENT
<section>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 main-content">
				<h4>Pages</h4>
				
				<form action="blogs.php" method="POST">
					<input type="hidden" name="id" value="{$page->id}"/>
					<fieldset class="form-group">
						<input type="text" name="title" class="form-control" placeholder="Title" value="{$page->title}" />
					</fieldset>
					<fieldset class="form-group">
						<textarea id="ckeditor_textarea" name="content">{$page->content}</textarea>
						<script>
							CKEDITOR.replace('ckeditor_textarea');
						</script>
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
$p->addJSFile('../js/ckeditor/ckeditor.js');

$p->addContent($content);
$p->printPage();