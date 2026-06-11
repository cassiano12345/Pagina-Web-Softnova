<?php

include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = '
			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.html">Home</a> 
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="#">Mercados</a></li>
			</ul>
			
			<div class="row-fluid">
					
				<div>
				 <img src="/intranet/img/underdevelopment.gif"> 
				</div>
				
			</div>		
			
			';
echo $content;

?>