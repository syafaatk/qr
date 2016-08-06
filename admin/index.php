<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('../classes/controller.php');
controller::setAdmin(true);

$test = db::query('SELECT * FROM `settings`')->fetchAll(PDO::FETCH_ASSOC);

$content = <<<CONTENT
<section>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 main-content">
				<h4>Intrepid Test Bed</h4>
				<p>Lorem ipsum dolor sit amet invictus dolartius</p>
			</div>
		</div>
	</div>
</section>
<section>
</section>
CONTENT;



$p = AdminStructure::getInstance(true);
$p->setBootstrap(true);
$p->addContent($content);
$p->printPage();