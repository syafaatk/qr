<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('../classes/controller.php');
require_once('classes/items.php');
controller::setAdmin(true);

$item = (isset($_REQUEST['id'])) ? new items($_REQUEST['id']) : new items();


if(isset($_POST['title'])) {
	
	$item->title	= $_POST['title'];
	$item->content	= $_POST['content'];
	$item->pat		= $_POST['pat'];
	$item->manual	= $_POST['manual'];
	$item->hackable	= (isset($_POST['hackable'])) ? $_POST['hackable'] : 0;
	$item->save();
	$loadMe = (db::lastInserted() == 0) ? $_POST['id'] : db::lastInserted();
	$item = new items($loadMe);
}

$hackable = ($item->hackable) ? ' checked' : '';

$printButton = (isset($_REQUEST['id'])) ? '<a href="admin/print.php?id='.$item->id.'" target="_new" class="btn btn-default pull-right">Print Label</a>' : '';

$content = <<<CONTENT
<section>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 main-content">
				<h4>Items</h4>
				
				<form action="admin/items.php" method="POST">
					<input type="hidden" name="id" value="{$item->id}"/>
					<fieldset class="form-group">
						<input type="text" name="title" class="form-control" placeholder="Title" value="{$item->title}" />
					</fieldset>
					<fieldset class="form-group">
						<textarea id="ckeditor_content" name="content">{$item->content}</textarea>
					</fieldset>
					<fieldset class="form-group">
						<label for="hackable">Hackable</label>
						<input type="checkbox"{$hackable} value="1" name="hackable"/>
					</fieldset>
				
					

					
					<div class="panel-group" id="accordion">
						<div class="panel panel-default">
						  <div class="panel-heading">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Manuals</a>
						  </div>
						  <div id="collapse1" class="panel-collapse collapse">
							<div class="panel-body">
								<textarea id="ckeditor_manual" name="manual">{$item->manual}</textarea>
							</div>
						  </div>
						</div>
						<div class="panel panel-default">
						  <div class="panel-heading">
							  <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">PAT</a>
						  </div>
						  <div id="collapse2" class="panel-collapse collapse">
							<div class="panel-body">
								<textarea id="ckeditor_pat" name="pat">{$item->pat}</textarea>
							</div>
						  </div>
						</div>
					</div> 
						
					<fieldset class="form-group">
						<button type="submit" class="btn btn-primary">Save</button>
						{$printButton}
					</fieldset>
						
						
				</form>
			</div>
		</div>
	</div>
</section>
<script>
	CKEDITOR.replace('ckeditor_content');
	CKEDITOR.replace('ckeditor_manual');
	CKEDITOR.replace('ckeditor_pat');
</script>
<section>
</section>
CONTENT;



$p = AdminStructure::getInstance(true);
$p->setBootstrap(true);
$p->addJSFile('js/ckeditor/ckeditor.js');
$p->addContent($content);
$p->printPage();