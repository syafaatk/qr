<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('classes/controller.php');
require_once('admin/classes/items.php');

$start = (isset($_GET['p']) && $_GET['p'] > 1) ? (($_GET['p'] -1) * settings::getValue('admin_default_per_page')) : 0;



if(isset($_POST['title'])) {
	
	$item->title	= $_POST['title'];
	$item->content	= $_POST['content'];
	$item->save();
	$loadMe = (db::lastInserted() == 0) ? $_POST['id'] : db::lastInserted();
	$item = new items($loadMe);
}

require_once('admin/classes/pagination.php');

$search       = (isset($_GET['search'])) ? $_GET['search'] : '';
$searchString = (isset($_GET['search'])) ? '%' . $_GET['search'] . '%' : '';
if($search == '') {
	$query  = "SELECT * FROM `items` ORDER by `date` DESC, `id` DESC";
	$params = array();
}
else {
	$query  = "SELECT * FROM `items` WHERE `content` LIKE :search OR `title` LIKE :search ORDER by `date` DESC, `id` DESC";
	$params = array('search' => $searchString);
}

$pagination = new pagination($query, $params, $start);


$tablerows = '';
foreach($pagination->data as $data)
{
	
	$desc = (strlen($data->content) > 50) ? substr(strip_tags($data->content), '50') . '...' : strip_tags($data->content);
	$date = date('d-m-Y', strtotime($data->date));
	$tablerows .= <<<TABLEROW
		<tr class="link-row" data-link="item.php?id={$data->id}">
			<td>{$data->id}</td>
			<td>{$data->title}</td>
			<td>{$desc}</td>
			<td class="text-right">{$date}</td>
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
				<h4>Items</h4>
				
				<table class="table table-hover table-auto">
					<thead>
						<th>#</th>
						<th>Name</th>
						<th>Description</th>
						<th class="text-right">Date</th>
					</thead>
					<tbody>
						{$tablerows}
					</tbody>
				</table>
				<div class="text-center">
					<ui class="pagination">
						<li{$disabled['prev']}><a href="search.php?search={$search}&p={$pagination->prevPage}">Prev</a></li>
						<li{$disabled['next']}><a href="search.php?search={$search}&p={$pagination->nextPage}">Next</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>
<section>
</section>
CONTENT;




$p = structure::getInstance();
$p->setBootstrap(true);
$p->addJSFile('../js/ckeditor/ckeditor.js');
$p->addJSFile('admin/js/admin.js');

$p->addContent($content);
$p->printPage();