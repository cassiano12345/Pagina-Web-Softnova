<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");
$content = "";


if (!isset($_SESSION['state'])){
	header("Location: ../../index.php");
}
else{
	


	
$content .= '	

			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.php">Home</a> 
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="#">Ajuda</a></li>
			</ul>
	
			<div class="box-contentCust3">
				<div>
				 <img src="/intranet/img/11.png"> 
				</div>
			</div>		
								
			<div class="well" >															
					<br>
						<div  class="divcust">
								<h1> Contatos </h1>
								Softnova - Software Empresarial e Sistemas, Lda <br>
								Rua Dr. Pompeu de Melo Cardoso <br>
								Nº9 Loja A/B Edificio Colombo II<br>
								3810-106 Aveiro<br>
								<br>								
								Telefone Geral: 234 198 183<br>
								Comercial: 961 540 418<br>
								Software: 961 540 419 - 961 540 420 - 926 887 270<br>
								Técnica: 961 540 420 - 961 540 422<br>
								<br>
								<br>								
								<p><a href="mailto:geral@softnova.pt">E-mail: geral@softnova.pt</a></p>
								Website: <a href=http://www.softnova.pt>www.softnova.pt</a>
						
						</div>
						<div style="text-align:center;">
							<font size="1">&nbsp &nbsp Coordenadas GPS: Latitude: N 40º 37\' 34" (40.6263144°) Longitude: W 8º 38\' 45" (-8.6458969°)</font>						
						</div>
						<div style="text-align:center;">
							<iframe width="600" height="350" id="gmap_canvas" src="https://maps.google.com/maps?q=softnova&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="" marginwidth="" ></iframe>
						</div>									
			</div>
		';

echo $content;
}


?>