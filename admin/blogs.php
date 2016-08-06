<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('../classes/controller.php');
require_once('classes/blogs.php');
controller::setAdmin(true);

$blog = (isset($_REQUEST['id'])) ? new blogs($_REQUEST['id']) : new blogs();

if(isset($_POST['title'])) {
	
	$blog->title	= $_POST['title'];
	$blog->content	= $_POST['content'];
	$blog->save();
	$loadMe = (db::lastInserted() == 0) ? $_POST['id'] : db::lastInserted();
	$blog = new blogs($loadMe);
}

$content = <<<CONTENT
<section>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 main-content">
				<h4>Blogs</h4>
				
				<form action="blogs.php" method="POST">
					<input type="hidden" name="id" value="{$blog->id}"/>
					<fieldset class="form-group">
						<input type="text" name="title" class="form-control" placeholder="Title" value="{$blog->title}" />
					</fieldset>
					<fieldset class="form-group">
						<textarea id="ckeditor_textarea" name="content">{$blog->content}</textarea>
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