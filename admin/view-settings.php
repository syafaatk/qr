<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Feb 2016
* Author: Benjamin Faulkner
*********************************************************************/

require_once('../classes/controller.php');
controller::setAdmin(true);

$start = (isset($_GET['p']) && $_GET['p'] > 1) ? (($_GET['p'] -1) * settings::getValue('admin_default_per_page')) : 0;


if(isset($_POST) && !empty($_POST)) {
	$setting = new settings();
	$setting->key_name	= $_POST['key_name'];
	$setting->value		= $_POST['value'];
	$setting->save();
	
}

require_once('classes/pagination.php');
$pagination = new pagination("SELECT `key_name`, `value` FROM `settings`", array(), $start, 100);


$tablerows = '';
foreach($pagination->data as $data)
{
	$tablerows .= <<<TABLEROW
		<tr>
			<td style="vertical-align:middle">{$data->key_name}</td>
			<td>
				<form class="form-inline" method="POST">
					<div class="form-group col-xs-10">
						<input type="hidden" name="key_name" value="{$data->key_name}"/>
						<input name="value" value="{$data->value}" class="form-control" style="width: 100%;" />
					</div>
					<button type="submit" class="btn btn-default">Save Change</button>
				</form>
			</td>
		</tr>
TABLEROW;
}

$disabled['prev']	= ($pagination->prevPage != $pagination->thisPage)	? "" : " class='disabled'";
$disabled['next']	= ($pagination->thisPage != $pagination->total)		? "" : " class='disabled'";


$content = <<<CONTENT
<section>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 main-content">
				<h4>Settings</h4>
				
				<table class="table table-hover table-auto">
					<thead>
						<th>Key</th>
						<th>Value</th>
					</thead>
					<tbody>
						{$tablerows}
					</tbody>
				</table>
				<div class="text-center">
					<ui class="pagination">
						<li{$disabled['prev']}><a href="view-blogs.php?p={$pagination->prevPage}">Prev</a></li>
						<li{$disabled['next']}><a href="view-blogs.php?p={$pagination->nextPage}">Next</a></li>
					</ul>
				</div>
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
$p->addJSFile('js/admin.js');

$p->addContent($content);
$p->printPage();