<?php

include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");
$content = "";
//session_start();

if (!isset($_SESSION['user'])){
	header("Location: ../../index.php");
}
else{

	if($_SESSION['codutil'] == 'admin'){
		$specialAccess = '<li onclick="gerirUtilizadores()"><a href="#" ><i class="halflings-icon briefcase"></i> Gestão</a></li>';
	}
	else{
		$specialAccess = '<li onclick="gerirUtilizador()"><a href="#" ><i class="halflings-icon briefcase"></i> Conta</a></li>';
	}


	//verificar o nr de lojas a que este user tem acesso
	//--------------------------------------------------
	$nrOfStores = 0;
	$sql_countnrstores = "select count(idLoja) from dash_acessos
							where codemp  = ".$_SESSION['codemp']."
							  and idgrupo = ".$_SESSION['grupo']."";
	$rsStore=$db->execute($sql_countnrstores);
	if($rsStore){
		While($rowUnique = $rsStore->FetchRow()){
			$nrOfStores = $rowUnique[0];
		}
	}
	//---------------------------------------------------

	$sql = "select vendas, compras, stocks, ccorrentes, fornecedores from dash_grupos
				where codemp = ".$_SESSION['codemp']."
				and idgrupo = ".$_SESSION['grupo']." ";

	//VAI TER O HTML QUE CONSTROI O MENU LATERAL COM BASE NAS PERMISSOES
	$htmlDataMenu = '';


	$rsX=$db->execute($sql);
	if($rsX){
		While($rowX = $rsX->FetchRow()){
			if($rowX[0] == 'S'){
				$sqlVendasQuery = "Select submenu, ordem from dash_subacessos where codemp=".$_SESSION['codemp']." and menu = 'VENDAS' and idgrupo=".$_SESSION['grupo']." order by ordem asc";
				$rsVendas=$db->execute($sqlVendasQuery);
				if($rsVendas){
					$htmlDataMenu .= '<li onclick=""><a href="" class="dropmenu"><i class="icon-bar-chart"></i><span class="hidden-tablet"> Vendas </span></a>
									<ul>
										<li onclick="vendas(1)"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet"> Diárias</span></a></li>
										<li onclick="vendas(2)"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet"> Mensais</span></a></li>
									</ul>
								</li>';

					/*$htmlDataMenu .='<ul>';
					While($rowSubAcessos = $rsVendas->FetchRow()){
						$htmlDataMenu .='<li onclick="vendas('.$rowSubAcessos[1].')"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet">'.($rowSubAcessos[0]).'</span></a></li>';
					}$htmlDataMenu .='</ul>';*/

				}$htmlDataMenu .='</li>';

			} else {
				$htmlDataMenu .= '<li onclick="semAcesso()"><a href=""><i class="icon-bar-chart"></i><span class="hidden-tablet"> Vendas </span></a></li>';
			}
			if($rowX[1] == 'S'){
				$sqlComprasQuery = "Select submenu, ordem from dash_subacessos where codemp=".$_SESSION['codemp']." and menu = 'COMPRAS' and idgrupo=".($_SESSION['grupo'])." order by ordem asc";
				$rsCompras=$db->execute($sqlComprasQuery);
				if($rsCompras){
					$htmlDataMenu .= '<li onclick="compras()"><a href="#"><i class="icon-shopping-cart"></i><span class="hidden-tablet"> Compras </span></a>';
					$htmlDataMenu .= '<ul>';
					While($rowSubAcessos = $rsCompras->FetchRow()){
						$htmlDataMenu .='<li onclick="compras('.$rowSubAcessos[1].')"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet">'.($rowSubAcessos[0]).'</span></a></li>';
					}
					$htmlDataMenu .= '</ul>';
				}
				$htmlDataMenu .='</li>';
			} else {
				$htmlDataMenu .= '<li onclick="semAcesso()"><a href="#"><i class="icon-shopping-cart"></i><span class="hidden-tablet"> Compras </span></a></li>';
			}
			if($rowX[2] == 'S'){
				$sqlArtigosQuery = "Select submenu, ordem from dash_subacessos where codemp=".$_SESSION['codemp']." and menu = 'ARTIGOS' and idgrupo=".$_SESSION['grupo']." order by ordem asc";
				$rsArtigos=$db->execute($sqlArtigosQuery);

				if($rsArtigos){
					$htmlDataMenu .= '<li onclick=""><a href="" class="dropmenu"><i class="icon-tasks"></i><span class="hidden-tablet"> Stocks </span></a>
									<ul>
										<li onclick="stocks(1)"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet"> Ficha de Artigos </span></a></li>
										<li onclick="stocks(2)"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet"> Movimentos </span></a></li>
									</ul>
								</li>';

					/*$htmlDataMenu .= '<ul>';
					While($rowSubAcessos = $rsArtigos->FetchRow()){
						$htmlDataMenu .='<li onclick="stocks('.$rowSubAcessos[1].')"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet">'.($rowSubAcessos[0]).'</span></a></li>';
					}$htmlDataMenu .= '</ul>';*/

				}$htmlDataMenu .='</li>';

			} else {
					$htmlDataMenu .= '<li onclick="semAcesso()"><a href="#"><i class="icon-bar-chart"></i><span class="hidden-tablet"> Stocks </span></a></li>';
			}


			if($rowX[3] == 'S'){
				$sqlCorrentes = "Select submenu, ordem from dash_subacessos where codemp=".$_SESSION['codemp']." and menu = 'CCORRENTES' and idgrupo=".$_SESSION['grupo']." order by ordem asc";
				$rsCC=$db->execute($sqlCorrentes);
				if($rsCC){
					$htmlDataMenu .= '<li onclick=""><a href="" class="dropmenu"><i class="icon-truck"></i><span class="hidden-tablet"> Clientes </span></a>';
					$htmlDataMenu .= '<ul>';
					/*
					While($rowSubAcessos = $rsCC->FetchRow()){
						$htmlDataMenu .='<li onclick="clientes('.$rowSubAcessos[1].')"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet">'.($rowSubAcessos[0]).'</span></a></li>';
					} */
					$htmlDataMenu .='<li onclick="clientes('.$rowSubAcessos[1].')"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet">Top clientes</span></a></li>';
					$htmlDataMenu .='<li onclick="conta_corrente()"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet">Conta corrente</span></a></li>';
					$htmlDataMenu .= '</ul>';
				}
				$htmlDataMenu .='</li>';
			} else {
				$htmlDataMenu .= '<li onclick="semAcesso()"><a href="" class="dropmenu"><i class="icon-user"></i><span class="hidden-tablet"> Clientes </span></a></li>';
			}


			if($rowX[4] == 'S'){
				$sqlFornecedoresQuery = "Select submenu, ordem from dash_subacessos where codemp=".$_SESSION['codemp']." and menu = 'FORNECEDORES' and idgrupo=".$_SESSION['grupo']." order by ordem asc";
				$rsFornecedores=$db->execute($sqlFornecedoresQuery);
				if($rsFornecedores){
					$htmlDataMenu .= '<li onclick=""><a href="" class="dropmenu"><i class="icon-list-alt"></i><span class="hidden-tablet"> Fornecedores </span></a>';
					$htmlDataMenu .= '<ul>';

					/*
					While($rowSubAcessos = $rsFornecedores->FetchRow()){
						$htmlDataMenu .='<li onclick="fornecedores('.$rowSubAcessos[1].')"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet">'.($rowSubAcessos[0]).'</span></a></li>';
					} */
					$htmlDataMenu .='<li onclick="fornecedores('.$rowSubAcessos[1].')"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet">Top fornecedores</span></a></li>';
					$htmlDataMenu .='<li onclick="conta_corrente_forn()"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet">Conta Corrente</span></a></li>';
					$htmlDataMenu .= '</ul>';
				}
				$htmlDataMenu .='</li>';
			} else {
				$htmlDataMenu .= '<li onclick="semAcesso()"><a href="" class="dropmenu"><i class="icon-list-alt"></i><span class="hidden-tablet"> Fornecedores </span></a></li>';
			}
		}
	} else {
		echo 'Not Working!';
	}




$content .= '

			<style>
			a:hover {
				background-color: rgba(42, 208, 208, 0.46);
				color: white;
			}
			</style>

			<div class="navbar" >
				<div class="navbar-inner">
					<div class="container-fluid">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<a class="brand" href="index.php?"><span> '.$_SESSION["nomeEmp"].' - Dashboard</span></a>
						<!-- start: Header Menu -->
						<div class="nav-no-collapse header-nav">
							<ul class="nav pull-right">
								<!--li>
									<a class="btn" href="#">
										<i class="halflings-icon white wrench"></i>
									</a>
								</li>-->
								<!-- start: User Dropdown -->
								<li class="dropdown" id="dropdown_user" onclick="opencloseDropdown()">
									<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
										<i class="halflings-icon white user"></i> '.$_SESSION["user"].'
										<span class="caret"></span>
									</a>
									<ul class="dropdown-menu">
										<li class="dropdown-menu-title">
											<span>Account Settings</span>
										</li>
										<!--<li onclick="perfil()"><a href="#" ><i class="halflings-icon user"></i> Perfil</a></li> -->
											'.$specialAccess.'
										<li><a href="#" onclick="logout()"><i class="halflings-icon off"></i> Logout</a></li>
									</ul>
								</li>
								<!-- end: User Dropdown -->
							</ul>
						</div>
							<!-- end: Header Menu -->
					</div>
				</div>
			</div>
			<!-- start: Header -->
			<div class="container-fluid-full" >
				<div class="row-fluid">
					<!-- start: Main Menu -->
					<div id="sidebar-left" class="span2">
						<div class="nav-collapse sidebar-nav">
							<ul class="nav nav-tabs nav-stacked main-menu">


								<!-- #################################################################################################
									   O MENU É CRIADO DINAMICAMENTE COM AS PERMISSOES QUE ESTAO NA BASEDEDADOS, COM EXCEÇOES A:
										 -> Encomendas
										 -> Lojas
										 -> Calendario
										 -> Ajuda
										PORQUE SÃO COMUNS A TODOS OS UTILIZADORES
									 ################################################################################################# -->
								'.$htmlDataMenu.'

								<!--<li onclick="openMarket()" id="mercadoUL"> <a href="" ><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> Mercados </span></a> </li>-->

								<li onclick=""><a href="" class="dropmenu"><i class="icon-truck"></i><span class="hidden-tablet"> Encomendas </span></a>
									<ul>
										<li onclick="getRelatorioCompleto(3)"><a class="submenu" href="#"><i class="icon-upload-alt"></i><span class="hidden-tablet"> De Clientes </span></a></li>
										<li onclick="getRelatorioCompleto(4)"><a class="submenu" href="#"><i class="icon-download-alt"></i><span class="hidden-tablet"> A Fornecedores </span></a></li>
										<li onclick="faseEncomenda()"><a class="submenu" href="#"><i class="icon-dashboard"></i><span class="hidden-tablet"> Fases Encomenda </span></a></li>
									</ul>



									<li onclick=""><a href="" class="dropmenu"><i class="icon-truck"></i><span class="hidden-tablet"> Contabilidade </span></a>
										<ul>
										<!-- <li onclick="getRelatorioCompleto(3)"><a class="submenu" href="#"><i class="icon-upload-alt"></i><span class="hidden-tablet"> Balancetes </span></a></li> -->
											<li onclick="mov_contas_gerais()"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet"> Mov. Diarios </span></a></li>
											<li onclick="contas_gerais()"><a class="submenu" href="#"><i class="icon-file-alt"></i><span class="hidden-tablet"> Mov. Contas Gerais </span></a></li>
										</ul>

								<!-- ######################################################### -->


								<li onclick="loja()"> <a href="#"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> Lojas / Filiais</span></a> </li>
								<li onclick="calendario()"><a href="#"><i class="icon-calendar"></i><span class="hidden-tablet"> Calendário </span></a> </li>
								<li onclick="ajuda()"><a href="#"><i class="icon-edit"></i><span class="hidden-tablet"> Ajuda </span></a></li>
							</ul>
						</div>
					</div>
					<!-- end: Main Menu -->

					<!-- start: Content -->
					<div id="content" class="span10">
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">Home</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="#">Dashboard</a></li>
						</ul>

						<div class="row-fluid ">
							<div class="span3 statbox green" onTablet="span6" onDesktop="span3">
								<div id="totalVendas" class="number">-,--<i class="icon-arrow-up"></i></div>
								<div class="title">vendas hoje</div>
								<div class="footer">
									<a href="#" onClick="getRelatorioCompleto(1)"> Ver Relatório Completo</a>
								</div>
							</div>
							<div class="span3 statbox red" onTablet="span6" onDesktop="span3">
								<div id="totalCompras" class="number">-,--<i class="icon-arrow-down"></i></div>
								<div class="title">compras hoje</div>
								<div class="footer">
									<a href="#" onClick="getRelatorioCompleto(2)"> Ver Relatório Completo</a>
								</div>
							</div>

							<div class="span3 statbox blue noMargin" onTablet="span6" onDesktop="span3">
								<div id="totalEncomendasClientes" class="number">-,--<i class="icon-arrow-up"></i></div>
								<div class="title">encomendas clientes</div>
								<div class="footer">
									<a href="#" onClick="getRelatorioCompleto(3)"> Ver Relatório Completo</a>
								</div>
							</div>
							<div class="span3 statbox yellow" onTablet="span6" onDesktop="span3">
								<div id="totalRequisicoes" class="number">-,--<i class="icon-arrow-down"></i></div>
								<div class="title">requisições fornecedores</div>
								<div class="footer">
									<a href="#" onClick="getRelatorioCompleto(4)"> Ver Relatório Completo</a>
								</div>
							</div>
						</div>



						<div id= tituloVendas>
							<h1> Vendas Diárias </h1>
						</div>

						<div id="waitingCharts" style="margin-top: -50px">
							<img src="img/loading.gif">
						</div>


						<div id="realTimeComparativeStoreChart" class="container-fluid span12">
							<div class="row">
								<div class="col span12" >
									<h2>Vendas por Hora</h2>
									<canvas id="comparativeStoreChart" height="300px" width="950px"></canvas>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="span12 message header" style="border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px" id="seriesGraficosMain">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col span12">
								<br>
									<h2>Acumulado por Hora</h2>
									<canvas id="accComparativeStoreChart" height="300px" width="950px"></canvas>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="span12 message header" style="border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px" id="seriesGraficosMain2">
								</div>
							</div>
							<br>
							<div class="row">
								<div id="realTimeComparativeStoreChartRound" class="col span12">
									<div id="containerRoundCharts" class="span11">
										<canvas id="comparativeStoreChartRound" height="250px" width="250px"></canvas>
									</div>
								</div>
							</div>

						</div>

						<div id="tituloComparativoAnual">
							<h1> Vendas Mensais	 </h1>
						</div>
					  	<div id = "comparativoAnual" class="span12" >
								<h2>Comparativo de Vendas Anual por Mês</h2>
								<canvas id="comparativeMesAno" height="300px" width="950px"></canvas>
						</div>
						<div id="seriesComparativeMesAno" class="span12 message header" style="border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px; margin-left:15px"  >
						</div>


						<div id="semVendasImg" class="tooltip-demo well" height="350px" style="display: none">
							<h1 style="text-align: center;"> Não é possível apresentar o gráfico.</h1>
							<h1 style="text-align: center;"> Ainda não existem vendas hoje. </h1>
						</div>

					</div>
					<div class="clearfix"></div>

				</div>
			</div>';

	$script="<script type='text/javascript'>


		//var datasetRT = [];
		var updatingChart = setTimeout(function() { updateChart(); }, 2000);


		$(document).ready(function () {
			$('#waitingCharts').show();
			$('#realTimeComparativeStoreChart').hide();
			$('#realTimeComparativeStoreChartRound').hide();
			$('#vendasMesAnoComparativo').hide();
			$('#semVendasImg').hide();
			$('#tituloVendas').hide();
			$('#tituloComparativoAnual').hide();
			$('#comparativoAnual').hide();


			max =0;
		});

		var max = 0;

		function updateChart(){

				//
				//  FAZ O PEDIDO AO VENDASRT
				//  A RESPOSTA É UM OBJETO JSON QUE JA VEM COM AS HORAS E COM O TOTAL
				//  PODEMOS ALTERAR O VENDASRT PARA FORNECER DADOS PARA DOISGRAFICOS COM A MESMA QUERY:
				//    -   VENDAS POR HORA	 			-> QUE GERA UM GRAFICO COM ALTOS E BAIXOS NO INTERVALO
				//	  -   VENDAS POR HORA ACUMULADAS 	-> QUE GERA UM GRAFICO CRESCENTE NO INTERVALO
				//

			$.getJSON('modules/main/VendasRT.php',{'tag':'dataHoras', 'acc':'false'}, function(dataVendasHoraDiarias){


				if(dataVendasHoraDiarias != 0 ){
					$('#semVendasImg').hide();

					setRoundCharts();

					var newMax = 'false';
					for(k=0; k< dataVendasHoraDiarias.datasets.length; k++){
						for(i=0; i<dataVendasHoraDiarias.datasets[k].data.length; i++){
							if(parseFloat(dataVendasHoraDiarias.datasets[k].data[i]) >  parseFloat(max)){
								max = dataVendasHoraDiarias.datasets[k].data[i];
								newMax = 'true';
							}
						}
					}

					var htmlToSeries = '';
					var nrOfDatasets = dataVendasHoraDiarias.datasets.length;
					var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5));

					htmlToSeries = '<table>';
					for(i=1;i<=dataVendasHoraDiarias.datasets.length;i++){
						i-=1;
						htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
						for(j=0; j< nrOfRows; j++){
							if(typeof dataVendasHoraDiarias.datasets[i] != 'undefined'){
								htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
								htmlToSeries += '<th style=\"border-spacing: 30px; width: 180px;\">';
								htmlToSeries += '<h2 style=\"color:'+dataVendasHoraDiarias.datasets[i].strokeColor+'; \"> '+dataVendasHoraDiarias.datasets[i].label+' </h2>';
								htmlToSeries += '</th>';
								htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
								i +=1;
							}
						}
						htmlToSeries += '</tr>';
					}
					htmlToSeries += '</table>';
					$('#seriesGraficosMain').html(htmlToSeries);



					var steps = 10;
					var stepSize = max / steps;
					if(newMax == 'true'){
						max = max +stepSize;
						stepSize = max / steps;
					}
					var optionsPr = {
								animation: true,
								/*graphMin: 0,
								scaleOverride: true,
								scaleStartValue: 0,*/
								scaleStepWidth: stepSize,
								scaleSteps: steps,
								scaleShowGridLines : true,
								scaleGridLineWidth : 1,
								scaleShowHorizontalLines : true,
								bezierCurve : false,
								bezierCurveTension : 0.3,
								pointDot : true,
								pointDotRadius : 1.5,
								pointDotStrokeWidth : 2,
								pointHitDetectionRadius : 10,
								datasetStroke : true,
								datasetStrokeWidth : 2,
								datasetFill : true,
								scaleLabel : \"<%= Number(value).toFixed(2).replace('.', ',') + ' €'%>\",
								showTooltips: true,
								legend : true,
								inGraphDataShow : false,
								scaleShowVerticalLines: false,
								annotateDisplay : true,
								annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
								responsive : true

					};

		/////////////////////////////////////////////////////////////////////////////////////////

					$.getJSON('modules/main/VendasRT.php',{'tag':'dataHoras', 'acc':'true'}, function(dataVendasHoraDiariasAcc){

						for(k=0; k<dataVendasHoraDiariasAcc.datasets.length; k++){
							for(l=0; l<dataVendasHoraDiariasAcc.datasets[k].data.length; l++){
								if (parseFloat(dataVendasHoraDiariasAcc.datasets[k].data[l]) >  parseFloat(max)){
									max = dataVendasHoraDiariasAcc.datasets[k].data[l];
									newMax = 'true';
								}
							}
						}

						var htmlToSeries = '';
						var nrOfDatasets = dataVendasHoraDiariasAcc.datasets.length;
						var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5));

						htmlToSeries = '<table>';
						for(i=1;i<=dataVendasHoraDiariasAcc.datasets.length;i++){
							i-=1;
							htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
							for(j=0; j< nrOfRows; j++){
								if(typeof dataVendasHoraDiariasAcc.datasets[i] != 'undefined'){
									htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
									htmlToSeries += '<th style=\"border-spacing: 30px; width: 180px;\">';
									htmlToSeries += '<h2 style=\"color:'+dataVendasHoraDiariasAcc.datasets[i].strokeColor+'; \"> '+dataVendasHoraDiarias.datasets[i].label+' </h2>';
									htmlToSeries += '</th>';
									htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
									i +=1;
								}
							}
							htmlToSeries += '</tr>';
						}
						htmlToSeries += '</table>';
						$('#seriesGraficosMain2').html(htmlToSeries);


						var steps = 10;
						var stepSize = max / steps;
						if(newMax == 'true'){
							max = max +stepSize;
							stepSize = max / steps;
						}
						var optionsPr = {
									animation: true,
									/*graphMin: 0,
									scaleOverride: true,
									scaleStartValue: 0,*/
									scaleStepWidth: stepSize,
									scaleSteps: steps,
									scaleShowGridLines : true,
									scaleGridLineWidth : 1,
									scaleShowHorizontalLines : true,
									bezierCurve : false,
									bezierCurveTension : 0.3,
									pointDot : true,
									pointDotRadius : 3,
									pointDotStrokeWidth : 2,
									pointHitDetectionRadius : 10,
									datasetStroke : true,
									datasetStrokeWidth : 2,
									datasetFill : true,
									scaleLabel : \"<%= formatNumber(value,0,',','.') + ' €'%>\",
									showTooltips: true,
									legend : true,
									inGraphDataShow : false,
									scaleShowVerticalLines: false,
									scaleShowHorizontalLines: true,
									annotateDisplay : true,
									annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €'%>\",
									responsive : true

						};



						var ctx = $('#comparativeStoreChart').get(0).getContext('2d');
						myLiveChart = new Chart(ctx).Line(dataVendasHoraDiarias, optionsPr);


						var ctx = $('#accComparativeStoreChart').get(0).getContext('2d');
						myLiveChart = new Chart(ctx).Line(dataVendasHoraDiariasAcc, optionsPr);

					});

					$('#waitingCharts').hide();
					$('#tituloVendas').show();
					$('#realTimeComparativeStoreChart').show();
					$('#realTimeComparativeStoreChartRound').show();

				} else {
					//$('#semVendasImg').show();
					$('#waitingCharts').hide();
					$('#realTimeComparativeStoreChart').hide();
					$('#realTimeComparativeStoreChartRound').hide();



				}
			});

		/////////////////////////////////////////////////////////////////////////////////////////

			var d = new Date();
			var ini = d.getFullYear()-1;
			var fim = d.getFullYear();

			$.getJSON('modules/main/VendasRT.php',{'tag':'comparativoAnoMes', 'anoini':ini, 'anofim':fim}, function(dataVendasAnuais){

				if(dataVendasAnuais != 0){

					var max = 0;
					for(k=0; k<dataVendasAnuais.datasets.length; k++){
						for(l=0; l<dataVendasAnuais.datasets[k].data.length; l++){
							if (parseFloat(dataVendasAnuais.datasets[k].data[l]) >  parseFloat(max)){
								max = dataVendasAnuais.datasets[k].data[l];
								newMax = true;
							}
						}
					}

					var steps = 10;
					var stepSize = max / steps;
					if(newMax == 'true'){
						max = max +stepSize;
						stepSize = max / steps;
					}

					var optionsPr = {
									animation: true,
									/*graphMin: 0,
									scaleOverride: true,
									scaleStartValue: 0,	*/
									scaleStepWidth: stepSize,
									scaleSteps: steps,
									scaleShowGridLines : true,
									scaleGridLineWidth : 1,
									scaleShowHorizontalLines : true,
									bezierCurve : false,
									bezierCurveTension : 0.3,
									pointDot : true,
									pointDotRadius : 1.5,
									pointDotStrokeWidth : 2,
									pointHitDetectionRadius : 10,
									datasetStroke : true,
									datasetStrokeWidth : 2,
									datasetFill : true,
									scaleLabel : \"<%= formatNumber(value,0,',','.') + ' €'%>\",
									showTooltips: true,
									legend : true,
									inGraphDataShow : false,
									scaleShowVerticalLines: false,
									scaleShowHorizontalLines: true,
									annotateDisplay : true,
									annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €'%>\",
									responsive : true
						};


					var ctxZ = $('#comparativeMesAno').get(0).getContext('2d');
					myLiveChartX = new Chart(ctxZ).Line(dataVendasAnuais, optionsPr);

					$('#vendasMesAnoComparativo').show();

					var htmlToSeries = '';
					var nrOfDatasets = dataVendasAnuais.datasets.length;
					var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5));

					htmlToSeries = '<table>';
					for(i=1;i<=dataVendasAnuais.datasets.length;i++){
						i-=1;
						htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
						for(j=0; j< nrOfRows; j++){
							if(typeof dataVendasAnuais.datasets[i] != 'undefined'){
								htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
								htmlToSeries += '<th style=\"border-spacing: 30px; width: 100px;\">';
								htmlToSeries += '<h2 style=\"color:'+dataVendasAnuais.datasets[i].strokeColor+'; \"> '+dataVendasAnuais.datasets[i].label+' </h2>';
								htmlToSeries += '</th>';
								htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
								i +=1;
							}
						}
						htmlToSeries += '</tr>';
					}
					htmlToSeries += '</table>';
					$('#seriesComparativeMesAno').html(htmlToSeries);
				}

			});

			$('#tituloComparativoAnual').show();
			$('#comparativoAnual').show();

			updatingChart = setTimeout(updateChart, 360000);
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function formatNumber(number, decimalsLength, decimalSeparator, thousandSeparator) {
		   var n = number,
			   decimalsLength = isNaN(decimalsLength = Math.abs(decimalsLength)) ? 2 : decimalsLength,
			   decimalSeparator = decimalSeparator == undefined ? ',' : decimalSeparator,
			   thousandSeparator = thousandSeparator == undefined ? '.' : thousandSeparator,
			   sign = n < 0 ? '-' : '',
			   i = parseInt(n = Math.abs(+n || 0).toFixed(decimalsLength)) + '',
			   j = (j = i.length) > 3 ? j % 3 : 0;

		   return sign +
			   (j ? i.substr(0, j) + thousandSeparator : '') +
			   i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousandSeparator) +
			   (decimalsLength ? decimalSeparator + Math.abs(n - i).toFixed(decimalsLength).slice(2) : '');
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function stopTimer(){
			clearTimeout(updatingChart);
		}

		var tempDataPie = '';

		/************************************* GRAFICO REDONDO *********************************/

		function setRoundCharts(){

			var nrOfCharts = ".$nrOfStores.";
			$.getJSON('modules/main/VendasRT.php',{'tag':'dataToPieChart'}, function(dataPie){
				if(dataPie[0][0].value != tempDataPie){
					$('#containerRoundCharts').html('');
					var htmlOfPieCharts = '';

					for(j=0; j < nrOfCharts; j++){
						var htmlOfPieCharts  = '<div id=\"store_label_'+j+'\" class=\"span4\" style=\"margin-top: 35px;margin-bottom: 50px\">';
						htmlOfPieCharts += '<canvas id=\"store_'+j+'\"  style=\"width:250px; height=250px;\" ></canvas>';
						htmlOfPieCharts += '</div>';
						$('#containerRoundCharts').append(htmlOfPieCharts);
					}

					tempDataPie = dataPie[0][0].value;
					for(k=0; k < dataPie.length; k++){
						var store = '#store_'+k;
						var storeLabel = '#store_label_'+k;
						var ctxRound = $(store).get(0).getContext('2d');
						var valueToShow = dataPie[k][0].value;

						myLiveChartRound = new Chart(ctxRound).Doughnut(dataPie[k],{
								animateRotate : true,
								graphMin: 0,
								graphMax: 100,
								percentageInnerCutout : 65,
								labelFontFamily : \"Arial\",
								labelFontStyle : \"normal\",
								labelFontSize : 72,
								labelFontColor : \"#666\",
								inGraphDataShow : true,
								inGraphDataTmpl: \"<%=v6 + ' %' %> \",
								annotateDisplay: true,
								annotateLabel:\"<%=v6 + ' %'%>\",
								responsive : true

						});
						$(storeLabel).append('<h3>'+dataPie[k][0].label+'</h3>');
					}
				}
			});
		}


		/////////////////////////////////////////////////////////////////////////////////////////

		function getRelatorioCompleto(tipo){
			$.get('modules/main/Relatorios.php',{'tipo':tipo},function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		$('.dropmenu').click(function(e){
			e.preventDefault();
			$(this).parent().find('ul').slideToggle();
		});

		$.get('modules/mercados/mercados.php', function(data){
			$('#mercadoUL').append('<ul id=mercados>');
			$('#mercados').append(data);
			$('#mercadoUL').append('</ul>');
		});

		$.get('modules/main/UltimasVendas.php', function(data){
			var valorVendas = data;
			$('#totalVendas').html($.number(valorVendas,2,',','.')+'\u20AC<i class=icon-arrow-up></i>');
		});

		$.get('modules/main/UltimasCompras.php', function(data){
			var valorCompras = data;
			$('#totalCompras').html($.number(valorCompras,2,',','.')+'\u20AC<i class=icon-arrow-down></i>');
		});

		$.get('modules/main/UltimasRequisicoes.php', function(data){
			var valorRequisicoes = data;
			$('#totalRequisicoes').html($.number(valorRequisicoes,2,',','.')+'\u20AC<i class=icon-arrow-down></i>');
		});

		$.get('modules/main/UltimasEncomendas.php', function(data){
			var valorEncomendas = data;
			$('#totalEncomendasClientes').html($.number(valorEncomendas,2,',','.')+'\u20AC<i class=icon-arrow-up></i>');
		});


		/////////////////////////////////////////////////////////////////////////////////////////

		function opencloseDropdown(){
			if ( $('#dropdown_user').hasClass('open') ){
				$('#dropdown_user').removeClass('open');
			} else{
				$('#dropdown_user').addClass('open');
			}
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function logout(){
			stopTimer();
			$.get('modules/logout/index.php',function(data){
				//$('body').html(data);
				window.location.replace('modules/main/index.php');
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function stocks(code){
			switch(code){
				case 1:
					$.get('modules/stocks/fichaArtigos.php',function(data){
						stopTimer();
						$('#content').html(data);
					});
				break;
				case 2:
					$.get('modules/stocks/index.php',function(data){
						if(data!=0){
							stopTimer();
							$('#content').html(data);
						}
					});
				break;
			}
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function compras(){
			$.get('modules/compras/index.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function openMarket(){
			$.get('modules/mercados/index.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function vendas(param){
			$.get('modules/vendas/index.php',{'op':param}, function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function ajuda(){
			$.get('modules/ajuda/index.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function underDevelopment(){
			$.get('working.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function faseEncomenda(){
			$.get('modules/encomendas/index.php',{nomeloja:null},function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function loja(){
			$.get('modules/loja/index.php',{nomeloja:null},function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		/*function perfil(){
			$.get('modules/perfil/index.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}*/


		/////////////////////////////////////////////////////////////////////////////////////////

		function calendario(){
			$.get('modules/calendar/index.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function gerirUtilizadores(){
			$.get('modules/secure/BackBoard.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		function gerirUtilizador(){
			$.get('modules/secure/BackBoardUtil.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function clientes(){
			$.get('modules/clientes/index.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function conta_corrente(){
			$.get('modules/clientes/Conta_corrente.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}
		/////////////////////////////////////////////////////////////////////////////////////////

		function mov_contas_gerais(){
			$.get('modules/contabilidade/mov_diarios.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}
		/////////////////////////////////////////////////////////////////////////////////////////
		function contas_gerais(){
			$.get('modules/contabilidade/contas_gerais.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}
		/////////////////////////////////////////////////////////////////////////////////////////

		function conta_corrente_forn(){
			$.get('modules/fornecedores/Conta_corrente.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}
		/////////////////////////////////////////////////////////////////////////////////////////

		function fornecedores(){
			$.get('modules/fornecedores/index.php',function(data){
				stopTimer();
				$('#content').html(data);
			});
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		function semAcesso(){
			alert('Atualmente, este utilizador, não tem acesso a este menu. Contacte o administrador do sistema.');
		}

		/////////////////////////////////////////////////////////////////////////////////////////

		// INICIALIZACAO DO DATEPICKER PARA FICAREM EM PORTUGUES
		jQuery(function($){
	        $.datepicker.regional['pt'] = {
	                closeText: 'Fechar',
	                prevText: '&#x3c;Anterior',
	                nextText: 'Seguinte',
	                currentText: 'Hoje',
	                monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
	                'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	                monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
	                'Jul','Ago','Set','Out','Nov','Dez'],
	                dayNames: ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','S&aacute;bado'],
	                dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
	                dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
	                weekHeader: 'Sem',
	                dateFormat: 'dd/mm/yy',
	                firstDay: 0,
	                isRTL: false,
	                showMonthAfterYear: false,
	                yearSuffix: ''};
	        $.datepicker.setDefaults($.datepicker.regional['pt']);
		});

</script>";



	echo ($content." ".$script);

}



?>
