<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('classes/controller.php');
require_once('admin/classes/items.php');


$item = new items($_REQUEST['id']);

$path    = settings::getValue('qr-save-path').'/'.$_REQUEST['id'].'.png';
$pathRel = $path;
//var_dump($path);die();



if(!$item->hackable) {
	$concept = QRcode::png("http://".$_SERVER['HTTP_HOST']."/qr/admin/items.php?id=".$_REQUEST['id'], $pathRel, QR_ECLEVEL_L, 5);
	$content = <<<CONTENT
		<div class="col-md-2 col-xs-4">
				<img src="http://{$_SERVER['HTTP_HOST']}/qr/{$path}"/>
		</div>
		<div class="col-md-10 col-xs-8">
			<h1 class="hackable-note">do not hack!</h1>
			<h2 class="hackable-sub">{$item->title}</h2>
			<p>http://{$_SERVER['HTTP_HOST']}/qr/admin/items.php?id={$_REQUEST['id']}</p>
		</div>	
CONTENT;
}
else {
	$concept = QRcode::png("http://".$_SERVER['HTTP_HOST']."/qr/admin/items.php?id=".$_REQUEST['id'], $pathRel);
	$content = <<<CONTENT
		<div class="col-md-1 col-xs-2">
			<div class="row">
				<img src="http://{$_SERVER['HTTP_HOST']}/qr/{$path}"/>
			</div>
		</div>
		<div class="col-md-11 col-xs-10">
			<h2>{$item->title}</h2>
			<p>http://{$_SERVER['HTTP_HOST']}/qr/admin/items.php?id={$_REQUEST['id']}</p>
		</div>	
CONTENT;
}

$content .= <<<CONTENT
<section>
	<div class="container-fluid">
		<div class="row">
			
		</div>
	</div>
</section>
<section>
</section>
CONTENT;



$p = structure::getInstance();


$p->addCSSFIle('css/bootstrap.min.css');
$p->addCSSFIle('css/bootstrap-theme.css');
$p->addCSSFIle('css/main.css');
$p->addCSSFIle('admin/css/print.css');

$p->setBootstrap(true);
$p->addContent($content);
$p->printPage();