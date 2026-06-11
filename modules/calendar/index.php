<?php

include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if (!isset($_SESSION['state'])){
	header("Location: ../../index.php");
}
else{
	

$content = '
		<ul class="breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="index.php">Home</a> 
				<i class="icon-angle-right"></i>
			</li>
			<li><a onclick="calendars()">Calendário</a></li>
		</ul>

		<div class="row-fluid sortable ui-sortable">
				<div class="box span12">
				 	<div data-original-title="" class="box-header">
						<h2><i class="halflings-icon calendar"></i><span class="break"></span>Calendário</h2>
				  	</div>
				  	<div class="box-content">
						<div class="span/*span9*/ fc" id="calendar"> </div>
						<div class="clearfix"></div>
					</div>
				</div>
		</div>



		';



$script = '
<script src="js/calendario_softnova.js"></script>
';


echo ($content.$script);


}
?>