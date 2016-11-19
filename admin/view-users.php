<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('../classes/controller.php');
controller::setAdmin(true);

$start = (isset($_GET['p']) && $_GET['p'] > 1) ? (($_GET['p'] -1) * settings::getValue('admin_default_per_page')) : 0;



if(isset($_POST['title'])) {
	
	$item->title	= $_POST['title'];
	$item->content	= $_POST['content'];
	$item->save();
	$loadMe = (db::lastInserted() == 0) ? $_POST['id'] : db::lastInserted();
	$item = new items($loadMe);
}

require_once('classes/pagination.php');
$pagination = new pagination("SELECT * FROM `users` ORDER by `id` DESC, `id` DESC", array(), $start);


$tablerows = '';
foreach($pagination->data as $data)
{
	$tablerows .= <<<TABLEROW
		<tr class="link-row" data-link="admin/users.php?id={$data->id}">
			<td>{$data->id}</td>
			<td>{$data->username}</td>
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
				<h4>Users</h4>
				
				<table class="table table-hover table-auto">
					<thead>
						<th>#</th>
						<th>Username</th>
					</thead>
					<tbody>
						{$tablerows}
					</tbody>
				</table>
				<div class="text-center">
					<ui class="pagination">
						<li{$disabled['prev']}><a href="admin/view-users.php?p={$pagination->prevPage}">Prev</a></li>
						<li{$disabled['next']}><a href="admin/view-users.php?p={$pagination->nextPage}">Next</a></li>
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
$p->addJSFile('js/ckeditor/ckeditor.js');
$p->addJSFile('admin/js/admin.js');

$p->addContent($content);
$p->printPage();