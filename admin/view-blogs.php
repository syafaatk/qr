<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('../classes/controller.php');
require_once('classes/blogs.php');
controller::setAdmin(true);

$start = (isset($_GET['p']) && $_GET['p'] > 1) ? (($_GET['p'] -1) * settings::getValue('admin_default_per_page')) : 0;



if(isset($_POST['title'])) {
	
	$blog->title	= $_POST['title'];
	$blog->content	= $_POST['content'];
	$blog->save();
	$loadMe = (db::lastInserted() == 0) ? $_POST['id'] : db::lastInserted();
	$blog = new blogs($loadMe);
}

require_once('classes/pagination.php');
$pagination = new pagination("SELECT * FROM `blogs`", array(), $start);


$tablerows = '';
foreach($pagination->data as $data)
{
	
	$desc = (strlen($data->content) > 50) ? substr(strip_tags($data->content), '50') . '...' : strip_tags($data->content);
	$date = date('d-m-Y', strtotime($data->date));
	$tablerows .= <<<TABLEROW
		<tr class="link-row" data-link="blogs.php?id={$data->id}">
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
				<h4>Blogs</h4>
				
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