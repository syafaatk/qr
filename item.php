<?php

/*********************************************************************
* Part of intrepid, this is not to be considered a production system
* Development started Dec 2015
* Author: Benjamin Faulkner
*********************************************************************/

require_once('classes/controller.php');
require_once('admin/classes/items.php');


$item = (isset($_REQUEST['id'])) ? new items($_REQUEST['id']) : false;

if($item === false){
	die ('No Item ID provided!');
}


$hackable = ($item->hackable) ? 'Yes' : 'No';


$content = <<<CONTENT
<section>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 main-content">
				<fieldset class="form-group">
					<h1>{$item->title}</h1>
				</fieldset>
				<fieldset class="form-group">
					{$item->content}
				</fieldset>
				<fieldset class="form-group">
					<label for="hackable">Hackable</label>
					{$hackable}
				</fieldset>
				
					

					
				<div class="panel-group" id="accordion">
					<div class="panel panel-default">
					  <div class="panel-heading">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Manuals</a>
					  </div>
					  <div id="collapse1" class="panel-collapse collapse">
						<div class="panel-body">
							{$item->manual}
						</div>
					  </div>
					</div>
					<div class="panel panel-default">
					  <div class="panel-heading">
						  <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">PAT</a>
					  </div>
					  <div id="collapse2" class="panel-collapse collapse">
						<div class="panel-body">
							{$item->pat}
						</div>
					  </div>
					</div>
				</div> 

				<fieldset class="form-group">
					<a href="print.php?id={$item->id}" target="_new" class="btn btn-default pull-right">Print Label</a>
				</fieldset>
			</div>
		</div>
	</div>
</section>

<section>
</section>
CONTENT;



$p = structure::getInstance();
$p->setBootstrap(true);
$p->addContent($content);
$p->printPage();