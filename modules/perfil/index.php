<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");
$content = "";


//session_start();

if (!isset($_SESSION['user'])){
	header("Location: ../../index.php");
}
else{

$specialAccess = '';

	if($_SESSION['codutil'] == 'MasterPiece'){
		$specialAccess = '<div class="divh"><button class="btn btn-primary span"> Utilizadores </button> </div>';
	}
	
	
$content .= '

		<ul class="breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="index.php">Home</a> 
				<i class="icon-angle-right"></i>
			</li>
			<li><a href="#">Perfil</a></li>
		</ul>
		
		
  
 
		<div class="divBorder" >	
		
				<div class="divimagem" >
						
							<img  src="img/teste.jpg" class="img-circle">
		
				</div>								
						'.$specialAccess.'
						
		</div>	
		

		
		
		<div class="divcust3"> 
				<div class="divcentrada"> 
						<h1>'.$_SESSION["user"].'</h1>														
				</div>
				
		 <div class="row-fluid sortable">
				<div class="box span10">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon user"></i><span class="break"></span>Dados Pessoais</h2>					
					</div>		
					
					<div class="box-content">
						<form class="form-horizontal">						
						
					<h3>'.$_SESSION["user"].'</h3>
				<h6>Cód. Util.:'.$_SESSION["codutil"].'</h6>
				<h6>Id Grupo: '.$_SESSION["grupo"].'</h6>

						</form>
				    </div>
				</div>
		</div>		
		
				
		</div>

		
		';


	
	
	echo $content;
	
}


?>