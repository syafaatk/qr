<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('/classes/controller.php');

$test = db::query('SELECT * FROM `settings`')->fetchAll(PDO::FETCH_ASSOC);

$path = settings::getValue('qr-save-path').'/test.png';
$concept = QRcode::png('PHP QR Code :)', $path);
$content = <<<CONTENT
<section>
	Proof of concept! <img src="http://{$_SERVER['HTTP_HOST']}/qr/{$path}">
</section>
<section>
	<div class="container-fluid header">
		<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<h1>Intrepid Test Bed</h1>
				<p>Lorem ipsum dolor sit amet invictus dolartius</p>
			</div>
			<div class="col-md-1"></div>
		</div>
	</div>
</section>
<section>
	<div class="container-fluid content">
		<article>
			<div class="row">
				<div class="col-md-1"></div>
				<div class="col-md-10">
					<h1>Test</h1>
					<p>Test 123 lorem ipsum etc etc</p>
				</div>
				<div class="col-md-1"></div>
			</div>
		</article>
	</div>
</section>
CONTENT;





$p = structure::getInstance();
$p->setBootstrap(true);
$p->addContent($content);
$p->printPage();