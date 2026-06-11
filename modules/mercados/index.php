<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");
$content = "";

$contentOptions ='';

if (!isset($_SESSION['user'])){
	echo "";
	header("Location: ../../index.php");
}
else{

	if (isset($_GET['mercado'])){
		$mercado = $_GET['mercado'];
	}
	else
	{
		$mercado = "";
	}
	
	$total = 0;
	
	try{
		$rs=$db->execute("select sum(TOTAL) from vendas_vdcab where codemp=".$_SESSION['codemp']." and mercado=".$mercado);
			if($rs){
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$total = 0;
					else
						$total = $row[0];
				}
			}
	} catch(Exception $e) {
		echo $e;
	}


	
	
	
	$content .= '
			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.html">Home</a> 
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="#">Mercados</a></li>
			</ul>
			
			<!--<div class="row-fluid">
				<div class="span3 statbox green" onTablet="span6" onDesktop="span3">
					<div class="boxchart">
					</div>
					<div id="totalVendas" class="number">'.$total.'<i class="icon-arrow-up"></i></div>
					<div class="title">vendas</div>
					<div class="footer">
						<a href="#"> Ver Relatório Completo</a>
					</div>	
				</div>
				<div class="span3 statbox red" onTablet="span6" onDesktop="span3">
					<div class="boxchart">210,220,200</div>
					<div id="totalCompras" class="number">0<i class="icon-arrow-down"></i></div>
					<div class="title">compras</div>
					<div class="footer">
						<a href="#"> Ver Relatório Completo</a>
					</div>
				</div>
				<div class="span3 statbox blue noMargin" onTablet="span6" onDesktop="span3">
					<div class="boxchart">100,150,220</div>
					<div id="totalEncomendasClientes" class="number">0<i class="icon-arrow-up"></i></div>
					<div class="title">encomendas clientes</div>
					<div class="footer">
						<a href="#"> Ver Relatório Completo</a>
					</div>
				</div>
				<div class="span3 statbox yellow" onTablet="span6" onDesktop="span3">
					<div class="boxchart">300,250,200</div>
					<div id="totalEncomendasFornecedores" class="number">0<i class="icon-arrow-down"></i></div>
					<div class="title">encomendas efetuadas</div>
					<div class="footer">
						<a href="#"> Ver Relatório Completo</a>
					</div>
				</div>				
			</div>	-->	
			
			';
	
	
	
	echo $content;
}
?>