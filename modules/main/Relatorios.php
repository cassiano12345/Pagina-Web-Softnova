<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");
$content = "";
$contentOptions ='';

//session_start();

if (!isset($_SESSION['user'])){
	echo "";
	header("Location: ../../index.php");
}
else{
		
	
	$tipo = $_GET['tipo'];
	
	switch($tipo){
	
		case 1:
			$content = '
					<ul class="breadcrumb">
						<li>
							<i class="icon-home"></i>
							<a href="index.php">Home</a> 
							<i class="icon-angle-right"></i>
						</li>
						<li><a href="#">Dashboard</a></li>
					</ul>
		
		
					<h1 > Vendas </h1>	
					<br>
					<div id="contentMain">		
						<div class="box span12">
							<div class="box-header" data-original-title="">
								<h2><i class="halflings-icon edit"></i><span class="break"></span>Opções</h2>
							</div>
							
							<div style="display: block;" class="box-content">
								<nav role="navigation" class="navbar navbar-default ">
									<div>
										<ul class="nav navbar-nav">											
											<li id="todos"  class="active">
												<a onclick="setActiveNav1(0)" style="cursor:pointer">Vendas de Hoje</a>
											</li>
											<!--*******************************************************************************
											<li id="com_data"  class="">
												<a onclick="setActiveNav1(1)" style="cursor:pointer">Ver Por Data</a>
											</li>-->
										</ul>
									</div>
								</nav>
								<br>
							</div>
							
							<br>
							<br>
							
							<div id="div_data"  >
								<form class="form-horizontal" id="formSearch">
									<fieldset>
										<br>
										<div class="control-group">
											<label class="control-label" for="datainicial">Data Inicial</label>
											<div class="controls">
												<input class="input-small datepicker" id="datainicial" type="text">
											</div>
											<br>
										</div>	
										<div class="control-group">
											<label class="control-label" for="datafinal">Data Final</label>
											<div class="controls">
												<input class="input-small datepicker" id="datafinal" type="text">
											</div>
										</div>
										
										<div class="control-group">
											<div class="controls">
												<button type="submit" class="btn btn-success"  onclick="com_dataFunc()" > Pesquisar </button>
											</div>
										</div>
												
									</fieldset>
								</form>
							</div>
						</div>
					</div>
					
					<div id="waitingBar" class="progress progress-striped progress-success active" style="display:inherit">
						<div id="waitingBarColor" class="bar" style="width:0%;"></div>
					</div>	
				
					<div id="waitingData" style="margin-top: -50px">
						<img src="img/loading.gif">
					</div>

						
					<div id="titulo">  </div>
					
					<div id="tabela">
						<table id="tabelavendas" class="display hover" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Vendedor</th>
									<th>Cliente</th>
									<th>Documento</th>
									<th>Data</th>
									<th>Total</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Vendedor</th>
									<th>Cliente</th>
									<th>Documento</th>
									<th>Data</th>
									<th>Total</th>
								</tr>
							</tfoot>
						</table>
					</div>
					
						
					<div id="titulo_intervalo_tempo">  </div>
					<div class="span11" id="divChart">
				</div>	';

			$script  =" 
			<script>
					var d = new Date();
					var month = d.getMonth()+1;
					var day = d.getDate();

					var output = (day<10 ? '0' : '') + day+ '/' +(month<10 ? '0' : '') + month + '/' + d.getFullYear();
					
					$(function() {
						$('#datainicial').datepicker();
						$('#datainicial').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
					});
					
					$(function() {
						$('#datafinal').datepicker();
						$('#datafinal').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
					});
					
					$(document).ready(function(){    
						$.fn.dataTable.TableTools.defaults.aButtons = [ \"csv\", \"xls\", \"pdf\" ];
						$('#datainicial').val(output);
						$('#datafinal').val(output);
						$('#div_data').hide();
						todos();
						$('#waitingData').hide();
					});
					
					var tipo_qry;
					
					function setActiveNav1(param){
						$('#todos').removeAttr('class');
						$('#com_data').removeAttr('class');	
						
						if(param == '0'){
							$('#todos').addClass('active');
							$('#div_data').hide();
							todos();
							
						}else if(param == '1'){
							com_dataFunc();
							$('#com_data').addClass('active');	
							$('#div_data').show();			
						}
					}

					
					$('#tabelavendas').DataTable( {
						\"createdRow\": function ( row, data, index ) {
							//if ( data[5].replace(/[\$,]/g, '') * 1 > 150000 ) {
								$('tr', row).eq(0)
							//}
						},
						\"fnRowCallback\": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
							if(aData[5] =='A'){
								$(nRow).addClass('alert alert-error');
							}
						}
					});
					
					function todos(){
						
						$('#tabela').hide();
						$('#divChart').hide();
						$('#titulo_intervalo_tempo').html('');
						$('#titulo').html('');



						var html = '<canvas id=\"graficoVendasPorVendedor\" height=\"350px\" width=\"950px\"></canvas> <br><br><h4>Vendedores:</h4><div class=\"span12 message header\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\" id=\"seriesGraficoVendasPorVendedor\"></div>'; 
						$('#divChart').html(html);
					
					
						var d = new Date();
						var month = d.getMonth()+1;
						var day = d.getDate();
						var output = (day<10 ? '0' : '') + day + '/'+ (month<10 ? '0' : '') + month + '/' +d.getFullYear() ;
						
						tipo_qry = 2;
						//tipo_qry = 1;*************************************************************						
						$.getJSON('modules/main/RelatoriosDB.php',{'tipo':".$tipo.",'tipo_qry': tipo_qry},function(data){
							
							$('#waitingBarColor').css('width', '100%' );
							$('#waitingBarColor').html('Completo');		
							if(data.length!=0){
								$('#tabela').show();
								$('#titulo_intervalo_tempo').show();
								$('#titulo_intervalo_tempo').append('<br><br><h1 align=\"center\"> Vendas por Vendedor </h1><h3 align=\"center\"> (Dia: '+output+')</h3> ');
								
							
											
								var table = $('#tabelavendas').dataTable({
									bRetrieve : true,
									bDestroy : true,
									bProcessing: true,
									sPaginationType: \"full_numbers\",
									sDom: 'TC<\"clear\">lfrtip',
									aoColumnDefs: [
											{ sClass: \"text-right\", aTargets: [ 2 ] },
											{ sClass: \"text-right\", aTargets: [ 3 ] },
											{ sClass: \"text-right\", aTargets: [ 4 ] }
									],
									oTableTools: {
										sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
									}
								});
								
								$(\"#tabelavendas\").tabs( {
									\"active\": function(event, ui) {
										var jqTable = $('table.display', ui.panel);
										if ( jqTable.length > 0 ) {
												jqTable.dataTable().fnAdjustColumnSizing();
										  }
									   }
								});

								oSettings = table.fnSettings();
								table.fnClearTable(this);
								
								for(var i=0; i<data.length; i++){
									var infodoc = data[i].tipodoc + ' / ' + data[i].serie + ' / ' +data[i].nroficial;	
									var obj = new Array();
									var total = (parseFloat(data[i].total)).toFixed(2);
									obj[0]  = data[i].vendedor;
									obj[1]  = data[i].nomecliente;
									obj[2]  = '<a data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+infodoc+'\',\''+data[i].anodoc+'\', \'detalhesVendas\')\">'+infodoc+'</a>';
									obj[3]  = data[i].datadoc;	
									obj[4]  = total + ' ' + data[i].codmoeda ;
									obj[5]  = data[i].situacao;
									table.oApi._fnAddData(oSettings, obj);
								}
								 
								oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
								table.fnDraw();
								
								table.fnSort([[3,'desc']]);
							}
							else{
								$('#titulo').show();
								$('#titulo').append('<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>');	
							}
						});
							
						$.getJSON('modules/main/RelatoriosChart.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry},function(datasetRT){
							if(datasetRT.datasets.length > 0){ 
								$('#divChart').show();
								var ctx = $('#graficoVendasPorVendedor').get(0).getContext('2d');
								var optionsPr = {
										animation: true,
										//graphMin: 0,										
										inGraphDataShow: true,
										inGraphDataTmpl: \"<%= v3 + ' €'%>\",
										scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
										showTooltips: true,
										legend : true,
										annotateDisplay : true,
										annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
										responsive : true
									};
								myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
								
								$('#seriesGraficoVendasPorVendedor').html('');
								
								var htmlToSeries = '<br><br>';
								var nrOfDatasets = datasetRT.datasets.length;
								var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
								
								htmlToSeries = '<table>';
								
								for(i=1;i<=datasetRT.datasets.length;i++){
									i-=1;
									htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
									for(j=0; j< nrOfRows; j++){
										if(typeof datasetRT.datasets[i] != 'undefined'){
											htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
											htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\">';
											htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+'; \"> '+datasetRT.datasets[i].label+' </h2>';
											htmlToSeries += '</th>';
											htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
											i +=1;
										}
									}
									htmlToSeries += '</tr>';
								}
								htmlToSeries += '</table>';
							
								
								$('#seriesGraficoVendasPorVendedor').html(htmlToSeries);
								
								
								//---------------//

								var ctxLine = $('#graficoVendasPorVendedorLinhas').get(0).getContext('2d');
								var optionsPr = {
										animation: true,
										graphMin: 0,										
										inGraphDataShow: true,										
										inGraphDataTmpl: \"<%= v3 + ' €'%>\",
										scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
										showTooltips: true,
										legend : true,
										annotateDisplay : true,
										annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
										responsive : true
									};
								myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
							} else {
								$('#divChart').hide();
								$('#myModalDiv').hide();
								
							}
						});
					}
						
					function com_dataFunc(){
						
						$('#tabela').hide();
						$('#divChart').hide();
						$('#titulo_intervalo_tempo').html('');
						$('#titulo').html('');
						
						var html = '<canvas id=\"graficoVendasPorVendedor\" height=\"350px\" width=\"950px\"></canvas> <br><br><h4>Vendedores:</h4><div class=\"span12 message header\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\" id=\"seriesGraficoVendasPorVendedor\"></div>'; 
						$('#divChart').html(html);
						
						tipo_qry = 2;
						var dataini 		= $('#datainicial').datepicker( 'option', 'dateFormat', 'yymmdd').val();
						var datafim 		= $('#datafinal').datepicker( 'option', 'dateFormat', 'yymmdd').val();
					
						var intervalo_tempo;
					
						if(!dataini || !datafim){
							$('#titulo').show();
							$('#titulo').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Existem campos por preencher!</div>');
							$('#waitingBarColor').css('width', '100%' );
							$('#waitingBarColor').html('Completo');
						}else
						if(dataini > datafim){
							$('#titulo').show();
							$('#titulo').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Data Inicial superior à data final!</div>');
								$('#waitingBarColor').css('width', '100%' );
								$('#waitingBarColor').html('Completo');
						} else {
							
							var dataini 		= $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
							var datafim 		= $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
							
							if(dataini==datafim)
								intervalo_tempo= 'Dia: ' +datafim;
							else
								intervalo_tempo= 'De: ' + dataini + ' até: ' + datafim;
							
								
							$.getJSON('modules/main/RelatoriosDB.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry , 'dataini':dataini, 'datafim':datafim},function(data){
								if(data.length!=0){
									$('#tabela').show();
									$('#divChart').show();
									
									$('#titulo_intervalo_tempo').show();

									$('#titulo_intervalo_tempo').append('<br><br><h1 align=\"center\"> Vendas por Vendedor </h1><h3 align=\"center\">('+intervalo_tempo+')</h3> <br>');


									table = $('#tabelavendas').dataTable({
											bRetrieve : true,
											bDestroy : true,
											bProcessing: true,
											sPaginationType: \"full_numbers\",
											sDom: 'TC<\"clear\">lfrtip',
											aoColumnDefs: [
													{ sClass: \"text-right\", aTargets: [ 2 ] },
													{ sClass: \"text-right\", aTargets: [ 3 ] },
													{ sClass: \"text-right\", aTargets: [ 4 ] }
													
											],
											oTableTools: {
												sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
											}
									});
											
									$(\"#tabelavendas\").tabs( {
										\"active\": function(event, ui) {
											var jqTable = $('table.display', ui.panel);
											if ( jqTable.length > 0 ) {
													jqTable.dataTable().fnAdjustColumnSizing();
											  }
										   }
									});
											
									oSettings = table.fnSettings();

									table.fnClearTable(this);
									
									for(var i=0; i<data.length; i++){
										var infodoc = data[i].tipodoc + ' / ' + data[i].serie + ' / ' +data[i].nroficial;	
										var obj = new Array();
										var total = (parseFloat(data[i].total)).toFixed(2);
										obj[0]  = data[i].vendedor;
										obj[1]  = data[i].nomecliente;
										obj[2]  = '<a data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+infodoc+'\',\''+data[i].anodoc+'\', \'detalhesVendas\')\">'+infodoc+'</a>';
										obj[3]  = data[i].datadoc;	
										obj[4]  = total + ' ' + data[i].codmoeda ;
										obj[5]  = data[i].situacao;
										table.oApi._fnAddData(oSettings, obj);
									}
									 
									oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
									table.fnDraw();
									table.fnSort([[3,'desc']]);


									$.getJSON('modules/main/RelatoriosChart.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry, 'dataini':dataini, 'datafim':datafim},function(datasetRT){
										var ctx = $('#graficoVendasPorVendedor').get(0).getContext('2d');
										var optionsPr = {
											animation: true,
											graphMin: 0,
											inGraphDataShow: true,
											inGraphDataTmpl: \"<%= v3 + ' €'%>\",
											scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
											showTooltips: true,
											legend : true,
											annotateDisplay : true,
											annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
											responsive : true
										};
										myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
										$('#seriesGraficoVendasPorVendedor').html('');
										
										var htmlToSeries = '<br><br>';
										var nrOfDatasets = datasetRT.datasets.length;
										var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
										
										htmlToSeries = '<table>';
										
										for(i=1;i<=datasetRT.datasets.length;i++){
											i-=1;
											htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
											for(j=0; j< nrOfRows; j++){
												if(typeof datasetRT.datasets[i] != 'undefined'){
													htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
													htmlToSeries += '<th style=\"border-spacing: 30px; width: 180px;\">';
													htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+'; \"> '+datasetRT.datasets[i].label+' </h2>';
													htmlToSeries += '</th>';
													htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
													i +=1;
												}
											}
											htmlToSeries += '</tr>';
										}
										htmlToSeries += '</table>';
										
										$('#seriesGraficoVendasPorVendedor').html(htmlToSeries);
										
										
										var ctxLine = $('#graficoVendasPorVendedorLinhas').get(0).getContext('2d');
										var optionsPr = {
											animation: true,
											graphMin: 0,
											inGraphDataShow: true,
											inGraphDataTmpl: \"<%= v3 + ' €'%>\",
											scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
											showTooltips: true,
											legend : true,
											annotateDisplay : true,
											annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
											responsive : true
										};
										myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
									});
								}
								else{
									$('#titulo').show();
									$('#titulo').append('<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>');	
									$('#waitingBarColor').css('width', '100%' );
									$('#waitingBarColor').html('Completo');
								}
							});	
						}
						
						var dataini 		= $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
						var datafim 		= $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
					}
					";
			//</script>";
		break;
		
		case 2:
			$content = '
				<ul class="breadcrumb">
					<li>
						<i class="icon-home"></i>
						<a href="index.php">Home</a> 
						<i class="icon-angle-right"></i>
					</li>
					<li><a href="#">Dashboard</a></li>
				</ul>
				
				<h1> Compras </h1>		
				<br>
					<div id="contentMain">		
						<div class="box span12">
							<div class="box-header" data-original-title="">
								<h2><i class="halflings-icon edit"></i><span class="break"></span>Opções</h2>
							</div>
							
							<div style="display: block;" class="box-content">
								<nav role="navigation" class="navbar navbar-default ">
									<div>
										<ul class="nav navbar-nav">											
											<li id="todos"  class="active">
												<a onclick="setActiveNav1(0)" style="cursor:pointer">Todas de Hoje</a>
											</li>
											<!--****************************************************************************
											<li id="com_data"  class="">
												<a onclick="setActiveNav1(1)" style="cursor:pointer">Ver Por Data</a>
											</li>-->
										</ul>
									</div>
								</nav>
								<br>
							</div>
							
							<br>
							<br>
							
							<div id="div_data"  >
								<form class="form-horizontal" id="formSearch">
									<fieldset>
										<br>
										<div class="control-group">
											<label class="control-label" for="datainicial">Data Inicial</label>
											<div class="controls">
												<input class="input-small datepicker" id="datainicial" type="text">
											</div>
											<br>
										</div>	
										<div class="control-group">
											<label class="control-label" for="datafinal">Data Final</label>
											<div class="controls">
												<input class="input-small datepicker" id="datafinal" type="text">
											</div>
										</div>
										
										<div class="control-group">
											<div class="controls">
												<button type="submit" class="btn btn-success" onclick="com_dataFunc()" > Pesquisar </button>
											</div>
										</div>
												
									</fieldset>
								</form>
							</div>
						</div>
					</div>
						
					<div id="waitingBar" class="progress progress-striped progress-success active" style="display:inherit">
						<div id="waitingBarColor" class="bar" style="width:0%;"></div>
					</div>	
					
					<div id="waitingData" style="margin-top: -50px">
						<img src="img/loading.gif">
					</div>

					
					<div id="titulo">  </div>
				
					<div id="tabela">
						<table id="tabelacompras" class="display hover" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Fornecedor</th>
									<th>Documento</th>
									<th>Data</th>
									<th>Total</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Fornecedor</th>
									<th>Documento</th>
									<th>Data</th>
									<th>Total</th>
								</tr>
							</tfoot>
						</table>
					</div>
					
					<div id="titulo_intervalo_tempo">  </div>
					<div class="span11" id="divChart">
						
						<canvas id="graficoComprasProduto" height="350px" width="950px" ></canvas><br><br>
						<h4>Fornecedores:</h4>
						<div class="span12 message header" style="border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px" id="seriesGraficoVendasPorVendedor"></div>
					</div>

				</div>	';

			$script  =" 
				<script>
					var d = new Date();
					var month = d.getMonth()+1;
					var day = d.getDate();

					var output = (day<10 ? '0' : '') + day+ '/' +(month<10 ? '0' : '') + month + '/' + d.getFullYear();
					
					$(function() {
						$('#datainicial').datepicker();
						$('#datainicial').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
					});
					
					$(function() {
						$('#datafinal').datepicker();
						$('#datafinal').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
					});
					
					$(document).ready(function(){
						$.fn.dataTable.TableTools.defaults.aButtons = [ \"csv\", \"xls\", \"pdf\" ];
						$('#datainicial').val(output);
						$('#datafinal').val(output);
						$('#div_data').hide();
						$('#waitingData').hide();
						todos();
					});
					
					var tipo_qry;
					
					function setActiveNav1(param){
						$('#todos').removeAttr('class');
						$('#com_data').removeAttr('class');
					
						if(param == '0'){
							$('#todos').addClass('active');
							$('#div_data').hide();	
							todos();							
							
						}else if(param == '1'){
							com_dataFunc();
							$('#com_data').addClass('active');	
							$('#div_data').show();	
						}
					}
					
					var tipo_qry;
						
					function todos(){
						$('#tabela').hide();
						$('#divChart').hide();
						$('#titulo_intervalo_tempo').html('');
						$('#titulo').html('');
						
						tipo_qry = 1;
						var cont=0;
						
						var html = '<canvas id=\"graficoComprasProduto\" height=\"350px\" width=\"950px\"></canvas> <br><br><h4>Fornecedores:</h4><div class=\"span12 message header\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\" id=\"seriesGraficoVendasPorVendedor\"></div>'; 
						$('#divChart').html(html);
						
						$.getJSON('modules/main/RelatoriosDB.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry},function(data){
							
							$('#waitingBarColor').css('width', '100%' );
							$('#waitingBarColor').html('Completo');	
								
							if(data.length!=0){
								$('#tabela').show();
								$('#divChart').show();
								
								$('#titulo_intervalo_tempo').show();
								$('#titulo_intervalo_tempo').append('<br><br><h1 align=\"center\"> Compras por Fornecedor </h1><h3 align=\"center\">(Dia: ".date('d/m/Y').")</h3><br>');
							
								
									
								
								table = $('#tabelacompras').dataTable({
									bProcessing: true,
									sPaginationType: \"full_numbers\",
									bRetrieve : true,
									bDestroy : true,
									aoColumnDefs: [
													{ sClass: \"text-center\", aTargets: [ 1 ] },
													{ sClass: \"text-center\", aTargets: [ 2 ] },
													{ sClass: \"text-right\", aTargets: [ 3 ] }
												],
									sDom: 'TC<\"clear\">lfrtip',
									oTableTools: {
										sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
									}
								});
									
								$(\"#tabelacompras\").tabs( {
									\"active\": function(event, ui) {
										var jqTable = $('table.display', ui.panel);
										if ( jqTable.length > 0 ) {
												jqTable.dataTable().fnAdjustColumnSizing();
										  }
									   }
								});
									
								oSettings = table.fnSettings();

								table.fnClearTable(this);
								
								for(var i=0; i<data.length; i++){
									var obj = new Array();
									obj[0]  = data[i].nome;
									obj[1]  = '<a data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+data[i].tipodoc+'\',\''+data[i].anodoc+'\', \'detalhesCompras\')\">'+data[i].tipodoc+'</a>';
									obj[2]  = data[i].datadoc;
									obj[3]  = $.number(data[i].totalmercli,2,',','.')+' EUR';
									obj[5]  = data[i].situacao;
									
									table.oApi._fnAddData(oSettings, obj);
								}
								 
								oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
								table.fnDraw();
								table.fnSort([[2,'desc']]);
							}
							else{
								$('#titulo').append('<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>');	
								$('#divChart').hide();
								$('#myModalDiv').hide();
							}
						});
						
						$.getJSON('modules/main/RelatoriosChart.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry},function(datasetRT){
						
							if(datasetRT.datasets.length >0){
								$('#divChart').show();	
								var ctx = $('#graficoComprasProduto').get(0).getContext('2d');
								var optionsPr = {
										animation: true,
										//graphMin: 0,
										inGraphDataShow: true,
										inGraphDataTmpl: \"<%= v3 + ' €'%>\",
										scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
										showTooltips: true,
										legend : true,
										annotateDisplay : true,
										annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
										responsive : true
									};
								myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
								$('#seriesGraficoVendasPorVendedor').html('');
								
								var htmlToSeries = '<br><br>';
								var nrOfDatasets = datasetRT.datasets.length;
								var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/4)); 
								
								htmlToSeries = '<table>';
								
								for(i=1;i<=datasetRT.datasets.length;i++){
									i-=1;
									htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
									for(j=0; j< nrOfRows; j++){
										if(typeof datasetRT.datasets[i] != 'undefined'){
											htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
											htmlToSeries += '<th style=\"border-spacing: 30px; width: 180px;\">';
											htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+'; \"> '+datasetRT.datasets[i].label+' </h2>';
											htmlToSeries += '</th>';
											htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
											i +=1;
										}
									}
									htmlToSeries += '</tr>';
								}
								htmlToSeries += '</table>';
								
								$('#seriesGraficoVendasPorVendedor').html(htmlToSeries);
								
								
								var ctxLine = $('#graficoVendasPorVendedorLinhas').get(0).getContext('2d');
								var optionsPr = {
									animation: true,
									graphMin: 0,
									inGraphDataShow: true,
									inGraphDataTmpl: \"<%= v3 + ' €'%>\",
									scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
									showTooltips: true,
									legend : true,
									annotateDisplay : true,
									annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
									responsive : true
								};
								myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
							}
						});
					}

					function com_dataFunc(){
						tipo_qry = 2;
						
						$('#tabela').hide();
						$('#divChart').hide();
						$('#titulo_intervalo_tempo').hide();
						$('#titulo').html('');
												
						var dataini 		= $('#datainicial').datepicker( 'option', 'dateFormat', 'yymmdd').val();
						var datafim 		= $('#datafinal').datepicker( 'option', 'dateFormat', 'yymmdd').val();
			
						var intervalo_tempo;
					
						if(!dataini || !datafim){
							$('#titulo').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Existem campos por preencher!</div>');
							$('#waitingBarColor').css('width', '100%' );
							$('#waitingBarColor').html('Completo');
						} else if(dataini > datafim){
								$('#titulo').html('');
								$('#titulo').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Data Inicial superior à data final!</div>');
								$('#waitingBarColor').css('width', '100%' );
								$('#waitingBarColor').html('Completo');
						} else {
							var dataini 		= $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
							var datafim 		= $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
							
							if(dataini==datafim)
								intervalo_tempo= 'Dia: ' +datafim;
							else
								intervalo_tempo= 'De: ' + dataini + ' até: ' + datafim;
							
							var html = '<canvas id=\"graficoComprasProduto\" height=\"350px\" width=\"950px\"></canvas> <br><br><h4>Fornecedores:</h4><div class=\"span12 message header\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\" id=\"seriesGraficoVendasPorVendedor\"></div>'; 
							$('#divChart').html(html);

								
							$.getJSON('modules/main/RelatoriosDB.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry , 'dataini':dataini, 'datafim':datafim},function(data){
								if(data.length!=0){
							
									$('#tabela').show();		
									$('#divChart').show();	
									$('#titulo_intervalo_tempo').html('');	
									$('#titulo_intervalo_tempo').show();	
									
							
									$('#titulo_intervalo_tempo').append('<br><br><h1 align=\"center\"> Compras por Fornecedor </h1><h3 align=\"center\">('+intervalo_tempo+')</h3><br>');
								
									table = $('#tabelacompras').dataTable({
										bProcessing: true,
										sPaginationType: \"full_numbers\",
										bRetrieve : true,
										bDestroy : true,
										sDom: 'TC<\"clear\">lfrtip',
										oTableTools: {
											sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
										}
									});
									
									$(\"#tabelacompras\").tabs( {
										\"active\": function(event, ui) {
											var jqTable = $('table.display', ui.panel);
											if ( jqTable.length > 0 ) {
													jqTable.dataTable().fnAdjustColumnSizing();
											  }
										   }
									});
										
									oSettings = table.fnSettings();

									table.fnClearTable(this);
									
									for(var i=0; i<data.length; i++){
										var obj = new Array();
										obj[0]  = data[i].nome;
										obj[1]  = '<a data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+data[i].tipodoc+'\',\''+data[i].anodoc+'\', \'detalhesCompras\')\">'+data[i].tipodoc+'</a>';
										obj[2]  = data[i].datadoc;
										obj[3]  = $.number(data[i].totalmercli,2,',','.');
										
										table.oApi._fnAddData(oSettings, obj);
									}
									 
									oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
									table.fnDraw();
									table.fnSort([[2,'desc']]);

								
									$.getJSON('modules/main/RelatoriosChart.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry, 'dataini':dataini, 'datafim':datafim},function(datasetRT){
										$('#divChart').show();	
										var ctx = $('#graficoComprasProduto').get(0).getContext('2d');
										myChart = new Chart(ctx).Bar(datasetRT);
										var optionsPr = {
											animation: true,
											graphMin: 0,
											inGraphDataShow: true,
											inGraphDataTmpl: \"<%= v3 + ' €'%>\",
											scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
											showTooltips: true,
											legend : true,
											annotateDisplay : true,
											annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
											responsive : true
										};
										myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
										$('#seriesGraficoVendasPorVendedor').html('');
										
										var htmlToSeries = '<br><br>';
										var nrOfDatasets = datasetRT.datasets.length;
										var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
										
										htmlToSeries = '<table>';
										
										for(i=1;i<=datasetRT.datasets.length;i++){
											i-=1;
											htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
											for(j=0; j< nrOfRows; j++){
												if(typeof datasetRT.datasets[i] != 'undefined'){
													htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
													htmlToSeries += '<th style=\"border-spacing: 30px; width: 180px;\">';
													htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+'; \"> '+datasetRT.datasets[i].label+' </h2>';
													htmlToSeries += '</th>';
													htmlToSeries += '<th style=\"border-spacing: 10px; width: 10px;\"></th>';
													i +=1;
												}
											}
											htmlToSeries += '</tr>';
										}
										htmlToSeries += '</table>';
										
										$('#seriesGraficoVendasPorVendedor').html(htmlToSeries);
										
										var ctxLine = $('#graficoVendasPorVendedorLinhas').get(0).getContext('2d');
										
										var optionsPr = {
											animation: true,
											graphMin: 0,
											inGraphDataShow: true,
											inGraphDataTmpl: \"<%= v3 + ' €'%>\",
											scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
											showTooltips: true,
											legend : true,
											annotateDisplay : true,
											annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
											responsive : true
										};
										myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
									});
								}
								else{
									$('#titulo').append('<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>');	
									$('#waitingBarColor').css('width', '100%' );
									$('#waitingBarColor').html('Completo');
								}
							});
								
						}
						
						var dataini 		= $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
						var datafim 		= $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
					} ";
				//</script>";
		break;
		
		case 3:
			$content = ' 
			
				<ul class="breadcrumb">
					<li>
						<i class="icon-home"></i>
						<a href="index.php">Home</a> 
						<i class="icon-angle-right"></i>
					</li>
					<li><a href="#">Dashboard</a></li>
				</ul>
						
				<h1> Encomendas de Clientes </h1>		
				<br>		
				<div id="contentMain">		
					<div class="box span12">
						<div class="box-header" data-original-title="">
						
							<h2><i class="halflings-icon edit"></i><span class="break"></span>Opções</h2>
						</div>
						
						<div style="display: block;" class="box-content">
							<nav role="navigation" class="navbar navbar-default ">
								<div>
									<ul class="nav navbar-nav">											
										<li id="todos1"  class="active">
											<a onclick="setActiveNav1(0)" style="cursor:pointer">Ver Todas Pendentes</a>
										</li>
										<li id="pend_data1"  class="">
											<a onclick="setActiveNav1(1)" style="cursor:pointer">Ver Hoje</a>
										</li>
										<!--<li id="artigo"  class="">
											<a onclick="setActiveNav1(2)" style="cursor:pointer">Ver Por Artigo</a>
										</li>-->
									</ul>
								</div>
							</nav>
							<br>
						</div>
						
						<br>
						
						<div id="div_data"  >
							<form class="form-horizontal" id="formSearch">
								<fieldset>
									<br>
									<div class="control-group">
										<label class="control-label" for="datainicial1">Data Inicial</label>
										<div class="controls">
											<input class="input-small datepicker" id="datainicial1" type="text">
										</div>
										<br>
									</div>	
									<div class="control-group">
										<label class="control-label" for="datafinal1">Data Final</label>
										<div class="controls">
											<input class="input-small datepicker" id="datafinal1" type="text">
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<button type="submit" class="btn btn-success" onclick="rel_cli()" > Pesquisar </button>
										</div>
									</div>
											
								</fieldset>
							</form>
						</div>
						
						<div id="div_data2"  >
							<form class="form-horizontal" id="formSearch">
								<fieldset>
									<br>
									
									<div class="control-group">
										<label class="control-label" for="datainicial_art">Data Inicial</label>
										<div class="controls">
											<input class="input-small datepicker" id="datainicial_art" type="text">
										</div>
										<br>
									</div>	
									<div class="control-group">
										<label class="control-label" for="datafinal_art">Data Final</label>
										<div class="controls">
											<input class="input-small datepicker" id="datafinal_art" type="text">
										</div>
									</div>
									<div class="control-group">
									<label class="control-label" for="typeahead"> Nome </label>
										<div class="controls">
											<input class="span6 typeahead" id="typeahead" data-provide="typeahead"  placeholder="Pesquisar Artigo para visualizar dados" data-items="4" data-source="[]" type="text" onclick="this.select()">
										</div>				
																
										<div class="controls">
										<br><button onclick="porArtigo()"  type="button" class="btn btn-success">Pesquisar <i class="halflings-icon search"></i></button>
									</div>
									</div>
											
								</fieldset>
							</form>
						</div>
						<br>
					</div>
				</div>
							
				<div id="waitingBar" class="progress progress-striped progress-success active" style="display:inherit">
					<div id="waitingBarColor" class="bar" style="width:0%;"></div>
				</div>	
				
				<div id="waitingData" style="margin-top: -50px">
					<img src="img/loading.gif">
				</div>
				
			</div>
				
			<div id="titulo">  </div>
			<br>
			<div id="tabela">
				<table id="tabelaEncomendas" class="display hover" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Cliente</th>
							<th>Documento</th>
							<th>Data</th>
							<th>Total</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Cliente</th>
							<th>Documento</th>
							<th>Data</th>
							<th>Total</th>
						</tr>
					</tfoot>
				</table>
			</div>
			
			<div id="tabelaArtigo">
				<table id="tabelaArtigoEncomenda" class="display hover" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Cliente</th>
							<th>Artigo</th>
							<th>Documento</th>
							<th>Data</th>
							<th>Qtd Pedida</th>
							<th>Qtd Entregue</th>
							<th>Qtd Falta</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Cliente</th>
							<th>Artigo</th>
							<th>Documento</th>
							<th>Data</th>
							<th>Qtd Pedida</th>
							<th>Qtd Entregue</th>
							<th>Qtd Falta</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<div id="titulo_intervalo_tempo">  </div>
			<div class="span11" id="divChart"> </div>
		</div>			';
		
			$script  =" 
				<script>

					var d = new Date();
					var month = d.getMonth()+1;
					var day = d.getDate();

					var output = (day<10 ? '0' : '') + day+ '/' +(month<10 ? '0' : '') + month + '/' + d.getFullYear();

					var autocomplete = $('#typeahead').typeahead().on('keyup', function(ev){
						ev.stopPropagation();
						ev.preventDefault();
					}); 

					jQuery('#typeahead').on('input', function() {
						if($('#typeahead').val().length >= 3){
							var expr = $('#typeahead').val();
							$.getJSON('modules/main/detalheArtigo.php',{'exp':expr},function(infoArtigo){
								$('#typeahead').data('typeahead').source = infoArtigo;
							 });
						} else {
								$('#typeahead').data('typeahead').source = [];
						}
					});
					
					$('#datainicial1').val(output);
					$('#datafinal1').val(output);
					$('#datainicial_art').val(output);
					$('#datafinal_art').val(output);
					
					$(function() {
						$('#datainicial1').datepicker();
						$('#datainicial1').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
					});
					
					$(function() {
						$('#datainicial_art').datepicker();
						$('#datainicial_art').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
					});
					
					$(function() {
						$('#datafinal_art').datepicker();
						$('#datafinal_art').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
					});
					
					$(function() {
						$('#datafinal1').datepicker();
						$('#datafinal1').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
					});
					
					$(document).ready(function(){    
						$.fn.dataTable.TableTools.defaults.aButtons = [ \"csv\", \"xls\", \"pdf\" ];
						document.getElementById('div_data').style.display = 'none';	
						document.getElementById('div_data2').style.display = 'none';
						$('#waitingData').hide();
						todos_cli();
					});
					
					var tipo_qry;
					
					function setActiveNav1(param){
						$('#todos1').removeAttr('class');
						$('#pend_data1').removeAttr('class');
						$('#artigo').removeAttr('class');

						if(param == '0'){
							$('#todos1').addClass('active');
							clean();
							todos_cli();
							document.getElementById('div_data').style.display = 'none';	
							document.getElementById('div_data2').style.display = 'none';							
							
						}else if(param == '1'){
							$('#pend_data1').addClass('active');	
							rel_cli();
							document.getElementById('div_data').style.display = 'none';//****************'block';
							document.getElementById('div_data2').style.display = 'none';
							clean();
						}else if(param == '2'){
							$('#artigo').addClass('active');	
							porArtigo();
							clean();
							document.getElementById('div_data').style.display = 'none';
							document.getElementById('div_data2').style.display = 'block';
						}
					}
					
					function clean(){
						$('#tabela').hide();
						$('#tabelaArtigo').hide();
						$('#titulo_intervalo_tempo').hide();
						$('#divChart').hide();
					}

					
					
					
					function todos_cli(){

						$('#titulo_intervalo_tempo').html('');
						$('#titulo').html('');
						clean();
						tipo_qry = 1;
						$('#titulo').show();
					
						var html = '<canvas id=\"graficoTotalEncomendasCliente\" height=\"350px\" width=\"950px\"></canvas> <br><br><h4>Clientes:</h4><div class=\"span12 message header\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\" id=\"seriesGraficoClientes\"></div>'; 
						$('#divChart').html(html);
						
						
						$.getJSON('modules/main/RelatoriosDB.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry},function(data){
							if (data.length == 0){
									$('#titulo').append('<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>');	
									$('#waitingBarColor').css('width', '100%' );
									$('#waitingBarColor').html('Completo');
									$('#tabelaEncomendas').hide();
									$('#titulo_intervalo_tempo').hide();
									$('#divChart').hide();
							} else {
									$('#waitingBarColor').css('width', '100%' );
									$('#waitingBarColor').html('Completo');
									$('#titulo').hide();
										$('#titulo_intervalo_tempo').show();
										$('#titulo_intervalo_tempo').append('<br><br><h1 align=\"center\"> Encomendas por Cliente  </h1><h3 align=\"center\">(Dia ".date('d/m/Y').")</h3><br>');

									
									$('#divChart').show();
									$('#tabelaEncomendas').show();
									$('#tabela').show();
									
									
									table = $('#tabelaEncomendas').dataTable({
										bProcessing: true,
										sPaginationType: \"full_numbers\",
										bRetrieve : true,
										bDestroy : true,
										sDom: 'TC<\"clear\">lfrtip',
										aoColumnDefs: [
												{ sClass: \"text-right\", aTargets: [ 1 ] },
												{ sClass: \"text-right\", aTargets: [ 2 ] },
												{ sClass: \"text-right\", aTargets: [ 3 ] }
												
										],
										oTableTools: {
											sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
										}
									});
									
									$(\"#tabelaEncomendas\").tabs({
										\"active\": function(event, ui) {
										var jqTable = $('table.display', ui.panel);
										if ( jqTable.length > 0 ) {
												jqTable.dataTable().fnAdjustColumnSizing();
										  }
									   }
									});		
									
									
											
									oSettings = table.fnSettings();

									table.fnClearTable(this);
									
									for(var i=0; i<data.length; i++){
										var obj = new Array();
										obj[0]  = data[i].cliente;
										//obj[1]  = data[i].doc;
										obj[1] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+data[i].doc+'\',\''+data[i].anodoc+'\',\'detalhesEncomendas\')\">'+data[i].doc+'</a>';
										obj[2]  = data[i].datadoc;
										var total = (parseFloat(data[i].total)).toFixed(2);
										obj[3]  = $.number(total,2,',','.') + ' ' + data[i].codmoeda ;
										table.oApi._fnAddData(oSettings, obj);
									}
									 
									oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
									table.fnDraw();
							
									table.fnSort([[2,'desc']]);
								
								
								$.getJSON('modules/main/RelatoriosChart.php',{'tipo':".$tipo.",'tipo_qry': tipo_qry},function(datasetRT){
								
									var ctx = $('#graficoTotalEncomendasCliente').get(0).getContext('2d');
									var optionsPr = {
											animation: true,
											graphMin: 0,
											inGraphDataShow: true,
											inGraphDataTmpl: \"<%= v3 + ' €'%>\",
											scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
											showTooltips: true,
											legend : true,
											annotateDisplay : true,
											annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
											responsive : true
										};
									myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
									
									$('#seriesGraficoClientes').html('');
									
									var htmlToSeries = '<br><br>';
									var nrOfDatasets = datasetRT.datasets.length;
									var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
									
									htmlToSeries = '<table>';
	
									for(i=1;i<=datasetRT.datasets.length;i++){
										i-=1;
										htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
										for(j=0; j< nrOfRows; j++){
											if(typeof datasetRT.datasets[i] != 'undefined'){
												htmlToSeries += '<th style=\"border-spacing: 10px; width: 30px;\"></th>';
												htmlToSeries += '<th style=\"border-spacing: 20px; width: 150px;\">';
												htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+'; \"> '+datasetRT.datasets[i].label+' </h2>';
												htmlToSeries += '</th>';
												htmlToSeries += '<th style=\"border-spacing: 10px; width: 30px;\"></th>';
												i +=1;
											}
										}
										htmlToSeries += '</tr>';
									}
									htmlToSeries += '</table>';
									
									$('#seriesGraficoClientes').html(htmlToSeries);
								});
							}
						});
					}
					
					function rel_cli(){

							tipo_qry = 2;
							$('#titulo').html('');
							$('#titulo').show();
							$('#titulo_intervalo_tempo').html('');	
							
							
							var dataini 		= $('#datainicial1').datepicker( 'option', 'dateFormat', 'yymmdd').val();
							var datafim 		= $('#datafinal1').datepicker( 'option', 'dateFormat', 'yymmdd').val();
					
							var intervalo_tempo;
							var html = ' <canvas id=\"graficoTotalEncomendasCliente\" height=\"350px\" width=\"950px\"></canvas><br><br><h4>Clientes:</h4><div class=\"span12 message header\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\" id=\"seriesGraficoClientes\"></div>'; 
							$('#divChart').html(html);
						

						if(!dataini || !datafim){
							$('#titulo').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Existem campos por preencher!</div>');
							$('#waitingBarColor').css('width', '100%' );
							$('#waitingBarColor').html('Completo');
						}else{
							if(dataini > datafim){
								$('#titulo').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Data Inicial superior à data final!</div>');
								$('#waitingBarColor').css('width', '100%' );
								$('#waitingBarColor').html('Completo');
							} else {
								var dataini 		= $('#datainicial1').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
								var datafim 		= $('#datafinal1').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
								
								if(dataini==datafim)
									intervalo_tempo= 'Dia: ' +datafim;
								else
									intervalo_tempo= 'De: ' + dataini + ' até: ' + datafim;
								
								$.getJSON('modules/main/RelatoriosDB.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry, 'dataini':dataini, 'datafim':datafim},function(data){
									if (data.length == 0){
											
											document.getElementById('titulo').innerHTML = '<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>';
											$('#waitingBarColor').css('width', '100%' );
											$('#waitingBarColor').html('Completo');
											$('#tabela').hide();
											$('#titulo_intervalo_tempo').hide();
											$('#divChart').hide();
										
									} else {
										$('#tabela').show();
										$('#titulo').hide();
										$('#titulo_intervalo_tempo').show();	
										$('#titulo_intervalo_tempo').append('<br><br><h1 align=\"center\"> Encomendas por Cliente  </h1><h3 align=\"center\">('+intervalo_tempo+')</h3><br>');
									
										$('#divChart').show();
										$('#tabelaEncomendas').show();
										
										table = $('#tabelaEncomendas').dataTable({
										bProcessing: true,
											sPaginationType: \"full_numbers\",
											bRetrieve : true,
											bDestroy : true,
											sDom: 'TC<\"clear\">lfrtip',
											aoColumnDefs: [
													{ sClass: \"text-right\", aTargets: [ 1 ] },
													{ sClass: \"text-right\", aTargets: [ 2 ] },
													{ sClass: \"text-right\", aTargets: [ 3 ] }
													
											],
											oTableTools: {
												sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
											}
										});
										
										$(\"#tabelaEncomendas\").tabs({
											\"active\": function(event, ui) {
											var jqTable = $('table.display', ui.panel);
											if ( jqTable.length > 0 ) {
													jqTable.dataTable().fnAdjustColumnSizing();
											  }
										   }
										});		
										
										
										oSettings = table.fnSettings();

										table.fnClearTable(this);
										
										for(var i=0; i<data.length; i++){
											var obj = new Array();
											obj[0]  = data[i].cliente;
											//obj[1]  = data[i].doc;
											obj[1] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+data[i].doc+'\',\''+data[i].anodoc+'\',\'detalhesEncomendas\')\">'+data[i].doc+'</a>';
											obj[2]  = data[i].datadoc;
											var total = (parseFloat(data[i].total)).toFixed(2);
											obj[3]  = $.number(total,2,',','.') + ' ' + data[i].codmoeda ;
											table.oApi._fnAddData(oSettings, obj);
										}
										 
										oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
										table.fnDraw();
								
										table.fnSort([[2,'desc']]);

									
										$.getJSON('modules/main/RelatoriosChart.php',{'tipo':".$tipo.",'tipo_qry': tipo_qry,'dataini':dataini, 'datafim':datafim},function(datasetRT){
										
										
										
											var ctx = $('#graficoTotalEncomendasCliente').get(0).getContext('2d');
											
											var optionsPr = {
													animation: true,
													graphMin: 0,
													inGraphDataShow: true,
													inGraphDataTmpl: \"<%= v3 + ' €'%>\",
													scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
													showTooltips: true,
													legend : true,
													annotateDisplay : true,
													annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
													responsive : true
											};
											myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
								
											$('#seriesGraficoClientes').html('');
											
											var htmlToSeries = '<br><br>';
											var nrOfDatasets = datasetRT.datasets.length;
											var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
											
											htmlToSeries = '<table>';
											
											for(i=1;i<=datasetRT.datasets.length;i++){
												i-=1;
												htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
												for(j=0; j< nrOfRows; j++){
													if(typeof datasetRT.datasets[i] != 'undefined'){
														htmlToSeries += '<th style=\"border-spacing: 10px; width: 30px;\"></th>';
														htmlToSeries += '<th style=\"border-spacing: 20px; width: 150px;\">';
														htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+'; \"> '+datasetRT.datasets[i].label+' </h2>';
														htmlToSeries += '</th>';
														htmlToSeries += '<th style=\"border-spacing: 10px; width: 30px;\"></th>';
														i +=1;
													}
												}
												htmlToSeries += '</tr>';
											}
											htmlToSeries += '</table>';
											
											
											$('#seriesGraficoClientes').html(htmlToSeries);
										
										});
									}
								});
							}
								
						}
						$('#datainicial1').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
						$('#datafinal1').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
					}
					
					//***************************************************************************
					function porArtigo(){
					
						$('#titulo').html('');
						$('#titulo').show();
						$('#titulo_intervalo_tempo').html('');	
						
						tipo_qry = 3;
						
						var procurar=document.getElementById('typeahead').value;

						var dataini 		= $('#datainicial_art').datepicker( 'option', 'dateFormat', 'yymmdd').val();
						var datafim 		= $('#datafinal_art').datepicker( 'option', 'dateFormat', 'yymmdd').val();
				
						var intervalo_tempo;
						var html = ' <canvas id=\"graficoTotalEncomendasCliente\" height=\"350px\" width=\"950px\"></canvas> <br><br><h4>Artigos:</h4><div class=\"span12 message header\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\" id=\"seriesGraficoClientes\"></div>'; 
						$('#divChart').html(html);

						if(!dataini || !datafim){
							$('#titulo').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Existem campos por preencher!</div>');
							$('#waitingBarColor').css('width', '100%' );
							$('#waitingBarColor').html('Completo');
						}else{
							if(dataini > datafim){
								$('#titulo').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Data Inicial superior à data final!</div>');
								$('#waitingBarColor').css('width', '100%' );
								$('#waitingBarColor').html('Completo');
						} else {
							var dataini 		= $('#datainicial_art').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
							var datafim 		= $('#datafinal_art').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
							
							if(dataini==datafim)
								intervalo_tempo= 'Dia: ' +datafim;
							else
								intervalo_tempo= 'De: ' + dataini + ' até: ' + datafim;
							
							$.getJSON('modules/main/RelatoriosDB.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry, 'dataini':dataini, 'datafim':datafim , 'procurar':procurar},function(data){
								if (data.length == 0){
									document.getElementById('titulo').innerHTML = '<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>';
		
										$('#waitingBarColor').css('width', '100%' );
										$('#waitingBarColor').html('Completo');
									
										$('#tabelaArtigo').hide();
										$('#tabelaArtigoEncomenda').hide();
										$('#titulo_intervalo_tempo').hide();
										$('#divChart').hide();
									
								} else {
										$('#tabelaArtigo').show();
										$('#titulo').hide();
										$('#titulo_intervalo_tempo').show();	
										$('#titulo_intervalo_tempo').append('<br><br><h1 align=\"center\"> Encomendas por Artigo </h1><h3 align=\"center\">('+intervalo_tempo+')</h3><br>');
									
										
										$('#divChart').show();
										$('#tabelaArtigoEncomenda').show();
										
										table = $('#tabelaArtigoEncomenda').dataTable({
										bProcessing: true,
											sPaginationType: \"full_numbers\",
											bRetrieve : true,
											bDestroy : true,
											sDom: 'TC<\"clear\">lfrtip',
											aoColumnDefs: [
													{ sClass: \"text-right\", aTargets: [ 2 ] },
													{ sClass: \"text-right\", aTargets: [ 3 ] }
											],
											oTableTools: {
												sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
											}
										});
										
										$(\"#tabelaArtigoEncomenda\").tabs({
											\"active\": function(event, ui) {
											var jqTable = $('table.display', ui.panel);
											if ( jqTable.length > 0 ) {
													jqTable.dataTable().fnAdjustColumnSizing();
											  }
										   }
										});		
												
										oSettings = table.fnSettings();

										table.fnClearTable(this);
										
										for(var i=0; i<data.length; i++){
											var obj = new Array();
											obj[0]  = data[i].cliente;
											obj[1]  = data[i].desart;
											obj[2]  = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+data[i].doc+'\',\''+data[i].anodoc+'\',\'detalhesEncomendas\')\">'+data[i].doc+'</a>';
											obj[3]  = data[i].datadoc;
											//var total = (parseFloat(data[i].total)).toFixed(2);
											//obj[4]  = $.number(total,2,',','.') + ' ' + data[i].codmoeda ;
											obj[4]  = $.number(data[i].qtd,2,',','.');
											obj[5]  = $.number(data[i].qtde,2,',','.');
											obj[6]  = $.number(data[i].qtdf,2,',','.');
											
											
											
											
											table.oApi._fnAddData(oSettings, obj);
										}
										 
										oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
										table.fnDraw();
								
										table.fnSort([[2,'desc']]);

									
									$.getJSON('modules/main/RelatoriosChart.php',{'tipo':".$tipo.",'tipo_qry': tipo_qry,'dataini':dataini, 'datafim':datafim, 'procurar':procurar},function(datasetRT){
									
										var ctx = $('#graficoTotalEncomendasCliente').get(0).getContext('2d');
										var optionsPr = {
												animation: true,
												graphMin: 0,
												inGraphDataShow: true,
												inGraphDataTmpl: \"<%= v3 + ' €'%>\",
												scaleLabel : \"<%= formatNumber(value,2,',','.') + ' '%>\",
												showTooltips: true,
												legend : true,
												annotateDisplay : true,
												annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
												responsive : true
										};
										myChart = new Chart(ctx).Bar(datasetRT, optionsPr);

										$('#seriesGraficoClientes').html('');
										
										var htmlToSeries = '<br><br>';
										var nrOfDatasets = datasetRT.datasets.length;
										var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
										
										htmlToSeries = '<table>';
										
										for(i=1;i<=datasetRT.datasets.length;i++){
											i-=1;
											htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
											for(j=0; j< nrOfRows; j++){
												if(typeof datasetRT.datasets[i] != 'undefined'){
													htmlToSeries += '<th style=\"border-spacing: 10px; width: 30px;\"></th>';
													htmlToSeries += '<th style=\"border-spacing: 20px; width: 150px;\">';
													htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+'; \"> '+datasetRT.datasets[i].label+' </h2>';
													htmlToSeries += '</th>';
													htmlToSeries += '<th style=\"border-spacing: 10px; width: 30px;\"></th>';
													i +=1;
												}
											}
											htmlToSeries += '</tr>';
										}
										htmlToSeries += '</table>';
										
										
										$('#seriesGraficoClientes').html(htmlToSeries);
									
									});
								}
							});
							}
						}
						var dataini 		= $('#datainicial_art').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
						var datafim 		= $('#datafinal_art').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
					} ";
			break;
		
		case 4:
			$content = '<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">Home</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="#">Dashboard</a></li>
						</ul>
						
						<h1> Requisições Fornecedores </h1>		
						<br>
						<div id="contentMain">		
							<div class="box span12">
								<div class="box-header" data-original-title="">
								
									<h2><i class="halflings-icon edit"></i><span class="break"></span>Opções</h2>
								</div>
								
								<div style="display: block;" class="box-content">
									<nav role="navigation" class="navbar navbar-default ">
										<div>
											<ul class="nav navbar-nav">											
												<li id="todos"  class="active">
													<a onclick="setActiveNav(0)" style="cursor:pointer">Ver Todas Pendentes</a>
												</li>
												<li id="pend_data"  class="">
													<a onclick="setActiveNav(1)" style="cursor:pointer">Ver Hoje</a>
												</li>
											</ul>
										</div>
									</nav>
								</div>
								
								<br>
								<div id="div_data"  >
									<form class="form-horizontal" id="formSearch">
									<br>
										<fieldset>
											<br>
											<div class="control-group">
												<label class="control-label" for="datainicial">Data Inicial</label>
												<div class="controls">
													<input class="input-small datepicker" id="datainicial" type="text">
												</div>
												<br>
											</div>	
											<div class="control-group">
												<label class="control-label" for="datafinal">Data Final</label>
												<div class="controls">
													<input class="input-small datepicker" id="datafinal" type="text">
												</div>
											</div>
											
											<div class="control-group">
												<div class="controls">
													<button type="submit" class="btn btn-success" onclick="rel_req()" > Pesquisar </button>
												</div>
											</div>
													
										</fieldset>
									</form>
								</div>
								<br>
							</div>
						</div>
									
						<div id="waitingBar" class="progress progress-striped progress-success active" style="display:inherit">
								<div id="waitingBarColor" class="bar" style="width:0%;"></div>
						</div>	
						
						<div id="waitingData" style="margin-top: -50px">
							<img src="img/loading.gif">
						</div>
						
						<div id="titulo">  </div>
						
						<br>
						<div id="tabela">
							<table id="tabelaRequisicoes" class="display hover" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Fornecedor</th>
										<th>Tipo Doc.</th>
										<th>Data Doc.</th>
										<th>Total</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Fornecedor</th>
										<th>Tipo Doc.</th>
										<th>Data Doc.</th>
										<th>Total</th>
									</tr>
								</tfoot>
							</table>
						</div>
						<div id="titulo_intervalo_tempo">  </div>
						<div class="span11" id="divChart">
						
							
						</div>';

			$script  =" <script>
			
					var d = new Date();
					var month = d.getMonth()+1;
					var day = d.getDate();

					var output = (day<10 ? '0' : '') + day+ '/' +(month<10 ? '0' : '') + month + '/' + d.getFullYear();

					$('#datainicial').val(output);
					$('#datafinal').val(output);
					
					$(function() {
						$('#datainicial').datepicker();
						$('#datainicial').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
					});
					
					$(function() {
						$('#datafinal').datepicker();
						$('#datafinal').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
					});
					
					
					$(document).ready(function(){    
						$.fn.dataTable.TableTools.defaults.aButtons = [ \"csv\", \"xls\", \"pdf\" ];
						document.getElementById('div_data').style.display = 'none';	
						todos_pend();
						$('#waitingData').hide();
					});
						
					var tipo_qry;
					function setActiveNav(param){
						
						$('#todos').removeAttr('class');
						$('#pend_data').removeAttr('class');


						if(param == '0'){
							$('#todos').addClass('active');
							todos_pend();
							document.getElementById('div_data').style.display = 'none';							
							
						}else if(param == '1'){
							$('#pend_data').addClass('active');	
							rel_req();
							document.getElementById('div_data').style.display = 'none';//************************'block';
						}
					}
					
					
					
					function todos_pend(){
			
						tipo_qry = 1;
						$('#titulo').show();
						$('#titulo_intervalo_tempo').html('');
						var intervalo_tempo;
						var html = '<canvas id=\"graficoVendasPorFornecedor\" height=\"350px\" width=\"950px\"></canvas> <br> <br><h4>Fornecedores:</h4><div class=\"span12 message header\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\" id=\"seriesGraficoComprasFornecedor\"></div>'; 
						$('#divChart').html(html);
							
						$.getJSON('modules/main/RelatoriosDB.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry},function(data){
						if (data.length == 0){
								$('#titulo').append('<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>');	
								$('#waitingBarColor').css('width', '100%' );
								$('#waitingBarColor').html('Completo');
								$('#tabelaRequisicoes').hide();
								$('#titulo_intervalo_tempo').hide();
								$('#divChart').hide();
						} else {
								$('#waitingBarColor').css('width', '100%' );
								$('#waitingBarColor').html('Completo');
								$('#titulo').hide();
								$('#titulo_intervalo_tempo').show();
								document.getElementById('titulo_intervalo_tempo').innerHTML = '<br><br><h1 align=\"center\">Requisições a Fornecedores </h1><h3 align=\"center\">(Dia: ".date('d/m/Y').")</h3> <br>';
								
								$('#divChart').show();
								$('#tabelaRequisicoes').show();
								$('#tabela').show();
								
								
								table = $('#tabelaRequisicoes').dataTable({
								bProcessing: true,
									sPaginationType: \"full_numbers\",
									bRetrieve : true,
									bDestroy : true,
									sDom: 'TC<\"clear\">lfrtip',
									aoColumnDefs: [
											{ sClass: \"text-right\", aTargets: [ 1 ] },
											{ sClass: \"text-right\", aTargets: [ 2 ] },
											{ sClass: \"text-right\", aTargets: [ 3 ] }
									],
									oTableTools: {
										sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
									}
								});
								
								$(\"#tabelaRequisicoes\").tabs({
									\"active\": function(event, ui) {
									var jqTable = $('table.display', ui.panel);
									if ( jqTable.length > 0 ) {
											jqTable.dataTable().fnAdjustColumnSizing();
									  }
								   }
								});		
										
								oSettings = table.fnSettings();
								table.fnClearTable(this);
								
								for(var i=0; i<data.length; i++){
									var obj = new Array();
									obj[0]  = data[i].nrfornecedor +' - '+data[i].nome;
									obj[1] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+data[i].doc+'\',\''+data[i].anodoc+'\',\'detalhesRequisicoes\')\">'+data[i].doc+'</a>';
									obj[2]  = data[i].datadoc;
									var total = (parseFloat(data[i].total)).toFixed(2);
									obj[3]  = $.number(total,2,',','.') + ' ' + data[i].codmoeda ;
									table.oApi._fnAddData(oSettings, obj);
								}
								 
								
								oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
								table.fnDraw();
						
								table.fnSort([[2,'desc']]);

							
								$.getJSON('modules/main/RelatoriosChart.php',{'tipo':".$tipo.",'tipo_qry': tipo_qry},function(datasetRT){
								
									var ctx = $('#graficoVendasPorFornecedor').get(0).getContext('2d');
									var optionsPr = {
											animation: true,
											graphMin: 0,
											inGraphDataShow: true,
											inGraphDataTmpl: \"<%= v3 + ' €'%>\",
											scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
											showTooltips: true,
											legend : true,
											annotateDisplay : true,
											annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
											responsive : true
									};
									myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
								
									$('#seriesGraficoComprasFornecedor').html('');
									
									var htmlToSeries = '<br><br>';
									var nrOfDatasets = datasetRT.datasets.length;
									var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
									
									htmlToSeries = '<table>';

									for(i=1;i<=datasetRT.datasets.length;i++){
										i-=1;
										htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
										for(j=0; j< nrOfRows; j++){
											if(typeof datasetRT.datasets[i] != 'undefined'){
												htmlToSeries += '<th style=\"border-spacing: 10px; width: 30px;\"></th>';
												htmlToSeries += '<th style=\"border-spacing: 20px; width: 150px;\">';
												htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+'; \"> '+datasetRT.datasets[i].label+' </h2>';
												htmlToSeries += '</th>';
												htmlToSeries += '<th style=\"border-spacing: 10px; width: 30px;\"></th>';
												i +=1;
											}
										}
										htmlToSeries += '</tr>';
									}
									htmlToSeries += '</table>';
									
									
									$('#seriesGraficoComprasFornecedor').html(htmlToSeries);
								
								});
							}
						});
						
					}
					
					function rel_req(){
						$('#titulo_intervalo_tempo').html('');
						tipo_qry = 2;
						$('#titulo').html('');
						$('#titulo').show();
						
						
						var dataini 		= $('#datainicial').datepicker( 'option', 'dateFormat', 'yymmdd').val();
						var datafim 		= $('#datafinal').datepicker( 'option', 'dateFormat', 'yymmdd').val();
				
						var intervalo_tempo;
						var html = '<canvas id=\"graficoVendasPorFornecedor\" height=\"350px\" width=\"950px\"></canvas> <br><br><h4>Fornecedores:</h4><div class=\"span12 message header\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\" id=\"seriesGraficoComprasFornecedor\"></div>'; 
						$('#divChart').html(html);
		
						if(!dataini || !datafim){
							$('#titulo').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Existem campos por preencher!</div>');
							$('#waitingBarColor').css('width', '100%' );
							$('#waitingBarColor').html('Completo');
						}else{
							if(dataini > datafim){
									$('#titulo').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Data Inicial superior à data final!</div>');
									$('#waitingBarColor').css('width', '100%' );
									$('#waitingBarColor').html('Completo');
							} else {
								var dataini 		= $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
								var datafim 		= $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
								
								if(dataini==datafim)
									intervalo_tempo= 'Dia: ' +datafim;
								else
									intervalo_tempo= 'De: ' + dataini + ' até: ' + datafim;
								
								$.getJSON('modules/main/RelatoriosDB.php',{'tipo':".$tipo.", 'tipo_qry': tipo_qry, 'dataini':dataini, 'datafim':datafim},function(data){
									if (data.length == 0){

											document.getElementById('titulo').innerHTML = '<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>';
											$('#waitingBarColor').css('width', '100%' );
											$('#waitingBarColor').html('Completo');
											$('#tabela').hide();
											$('#titulo_intervalo_tempo').hide();
											$('#divChart').hide();
										
									} else {
											$('#tabela').show();
											$('#titulo').hide();
											$('#titulo_intervalo_tempo').show();
											document.getElementById('titulo_intervalo_tempo').innerHTML = '<br><br><h1 align=\"center\">Requisições a Fornecedores </h1><h3 align=\"center\">('+intervalo_tempo+')</h3><br>';
											
											$('#divChart').show();
											$('#tabelaRequisicoes').show();
											
											table = $('#tabelaRequisicoes').dataTable({
												bProcessing: true,
												sPaginationType: \"full_numbers\",
												bRetrieve : true,
												bDestroy : true,
												sDom: 'TC<\"clear\">lfrtip',
												aoColumnDefs: [
														{ sClass: \"text-right\", aTargets: [ 1 ] },
														{ sClass: \"text-right\", aTargets: [ 2 ] },
														{ sClass: \"text-right\", aTargets: [ 3 ] }
												],
												oTableTools: {
													sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
												}												
											});
											
											$(\"#tabelaRequisicoes\").tabs({
												\"active\": function(event, ui) {
												var jqTable = $('table.display', ui.panel);
												if ( jqTable.length > 0 ) {
														jqTable.dataTable().fnAdjustColumnSizing();
												  }
											   }
											});		
											
											oSettings = table.fnSettings();

											table.fnClearTable(this);
											
											for(var i=0; i<data.length; i++){
												var obj = new Array();
												var tempDocInfo = data[i].doc;
												obj[0]  = data[i].nrfornecedor +' - '+data[i].nome;
												obj[1] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+tempDocInfo+'\',\''+data[i].anodoc+'\',\'detalhesRequisicoes\')\">'+tempDocInfo+'</a>';
												obj[2]  = data[i].datadoc;
												var total = (parseFloat(data[i].total)).toFixed(2);
												obj[3]  = $.number(total,2,',','.') + ' ' + data[i].codmoeda ;
												table.oApi._fnAddData(oSettings, obj);
											}
											 
											oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
											table.fnDraw();
									
											table.fnSort([[2,'desc']]);

										
										$.getJSON('modules/main/RelatoriosChart.php',{'tipo':".$tipo.",'tipo_qry': tipo_qry,'dataini':dataini, 'datafim':datafim},function(datasetRT){
										
											var ctx = $('#graficoVendasPorFornecedor').get(0).getContext('2d');
											
											var optionsPr = {
													animation: true,
													graphMin: 0,
													inGraphDataShow: true,
													inGraphDataTmpl: \"<%= v3 + ' €'%>\",	
													scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
													showTooltips: true,
													legend : true,
													annotateDisplay : true,
													annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
													responsive : true
											};
											myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
								
											$('#seriesGraficoComprasFornecedor').html('');
											
											var htmlToSeries = '<br><br>';
											var nrOfDatasets = datasetRT.datasets.length;
											var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
											
											htmlToSeries = '<table>';
											
											for(i=1;i<=datasetRT.datasets.length;i++){
												i-=1;
												htmlToSeries += '<tr style=\"border-spacing: 10px;\">';
												for(j=0; j< nrOfRows; j++){
													if(typeof datasetRT.datasets[i] != 'undefined'){
														htmlToSeries += '<th style=\"border-spacing: 10px; width: 30px;\"></th>';
														htmlToSeries += '<th style=\"border-spacing: 20px; width: 150px;\">';
														htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+'; \"> '+datasetRT.datasets[i].label+' </h2>';
														htmlToSeries += '</th>';
														htmlToSeries += '<th style=\"border-spacing: 10px; width: 30px;\"></th>';
														i +=1;
													}
												}
												htmlToSeries += '</tr>';
											}
											htmlToSeries += '</table>';
											
											
											$('#seriesGraficoComprasFornecedor').html(htmlToSeries);
										
										});
									}	
								});
							}
						}
						$('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
						$('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
					}
						
					";
		break;
	
	}
	
	$modal = '<!-- Modal -->
			<div id="myModalDiv" style="display: none">
				<div id="modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-lg">
					<div class="modal-content">
					  
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<div id="infoTitulo">
								<h4 class="modal-title" id="myModalLabel">Documento Nº: 1505 T1    -  Venda a Dinheiro </h4>
							</div>
						</div>
						<div id="documentView">
							<div class="modal-body">
						  
								<div id="infoEmpresa">
									<p> Empresa de Testes, Lda </p>
								</div>
								<br>
								
								<div id="infoCliente">
									<p> José Freitas Almaro dos Santos	</p>
									<p> Rua do Sol Posto, 1785-951 - Láabeira </p>
								</div>
								
								<div id="infoDocumento">
									<br>
									
									<table id="detalhesDocumentoTBL" class="compact" style="-moz-column-gap: 140px;"> 
										<thead>
											<tr>
												<th>Artigos</th>
												<th>Quantidade</th>
												<th>Preço Unit.</th>
												<th>Valor</th>
												<th>IVA</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>Pizza Camponesa Pequena</th>
												<th> 2 </th>
												<th>10.00</th>
												<th>20.00</th>
												<th>23%</th>
											</tr>
										</tbody>
									</table>
								
									<br>
									<h2 class="pull-right"> Total : 20.00€ </h2>
									<br><br>
								
								</div>
							</div>			
						</div>
					
						<div id="footerModal" class="modal-footer">
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe> ';

		$scriptModal = "
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
		
							function preencheModal(infodoc, anodoc, fl){ 	
								$('#infoTitulo').empty();
								$('#infoEmpresa').empty();
								$('#infoCliente').empty();
								$('#infoDocumento').empty();
								
								$('#myModalDiv').css('display', 'block');
										
								
								var infoArray = infodoc.split('/');
								//detalhesVenda
								$.getJSON('modules/main/'+fl+'.php',{'nroficial': infoArray[2].trim(), 'serie': infoArray[1].trim(),'tipodoc': infoArray[0].trim(), 'anodoc' : anodoc},function(infoDoc){
									
									if(infoDoc[0].situacao == 'A')
										$('#infoTitulo').html('<h2 class=\"alert alert-error\"> Documento Nr. '+ infodoc +' (Anulado) </h2>');
									else
										$('#infoTitulo').html('<h2> Documento Nr. '+ infodoc +'</h2>');
									
									$('#infoEmpresa').html('".$_SESSION['nomeEmp']."');
									//$('#infoEmpresa').append('<p id=\"idvendedor\" class=\"pull-right\">Nome: '+infoDoc[0].nome+'</p>');
									
									$('#infoCliente').html('<h3> Documento Nr. '+ infodoc +'</h3>');
									
									if(infoDoc[0].morada != null)
										{
											$('#infoCliente').append('<p>Exmo(s). Sr(s).:  '+infoDoc[0].nome +'</p><p> '+infoDoc[0].morada+' </p>');
										}
									else
										{
											$('#infoCliente').append('<p>Exmo(s). Sr(s).:  '+infoDoc[0].nome +'</p><p></p>');
										}
									
									var infoc = '<br><br><table id=\"detalhesDocumentoTBL\" class=\"table table-striped\" ><thead><tr><th>Artigos</th><th>Qtd</th><th>Preço Unit.</th><th>IVA</th><th>Desconto</th><th>Total</th></tr></thead><tbody>';
									
									
									
									for(i=0; i<infoDoc.length;i++){
										infoc += '<tr>';
										infoc += '<td> '+infoDoc[i].desart+'</td>';
										infoc += '<td style=\"text-align:center\"> '+$.number(infoDoc[i].qtd, 3,',','.')+'</td>';
										infoc += '<td style=\"text-align:center\"> '+$.number(infoDoc[i].pvn, 2,',','.')+' €</td>';
										infoc += '<td style=\"text-align:center\"> '+$.number(infoDoc[i].taxa, 2,',','.')+'% </td>';
										
										if(infoDoc[i].dsc1!='' || infoDoc[i].dsc1!='0'
										 ||infoDoc[i].dsc2!='' || infoDoc[i].dsc2!='0'
										 ||infoDoc[i].dsc3!='' || infoDoc[i].dsc3!='0')
										{
											//Desconto
											if (infoDoc[i].dsc1 !== '0'){ var auxDesc1 = infoDoc[i].dsc1+'%|';}
											else{auxDesc1=''}
											if (infoDoc[i].dsc2 !== '0'){ var auxDesc2 = infoDoc[i].dsc2+'%|';}
											else{auxDesc2=''}
											if (infoDoc[i].dsc3 !== '0'){ var auxDesc3 = infoDoc[i].dsc3+'%|';}
											else{auxDesc3=''}
											
											infoc += '<td style=\"text-align:center\">'+auxDesc1+auxDesc2+auxDesc3+'</td>';
										}								
										else{
											infoc += '<td style=\"text-align:center\"> 0,00% </td>';										
										}
									
										//Total											
										var Desc1 =  infoDoc[i].pvn*infoDoc[i].qtd*(-infoDoc[i].dsc1/100+1);
										var Desc2 = Desc1 * (-infoDoc[i].dsc2/100+1);
										var Desc3 = Desc2 * (-infoDoc[i].dsc3/100+1);		

										infoc += '<td style=\"text-align:center\"> '+$.number(Desc3, 2,',','.')+' €</td>';
										infoc += '</tr>';

									/*old
										infoc += '<td style=\"text-align:center\"> '+$.number(infoDoc[i].pvn*infoDoc[i].qtd, 2,',','.')+' €</td>';
										infoc += '</tr>';
									*/
											
									}
									
									infoc += '</tbody>';
									infoc += '</table>';
									
									infoc += '<table class=\"pull-right\">';
										
									infoc += '<tr>';
									infoc += '<td> Total Ilíquido </td>';
									infoc += '<td> &nbsp </td>';
									infoc += '<td class=\"pull-right\">'+ $.number(infoDoc[0].totalmercil, ".$_SESSION['decimaispv'].",',','.')  +'</td>';
									infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
									infoc += '</tr>';

									infoc += '<tr>';
									infoc += '<td> Desconto </td>';
									infoc += '<td> &nbsp </td>';
									infoc += '<td class=\"pull-right\">'+ $.number(infoDoc[0].totaldescln, ".$_SESSION['decimaispv'].",',','.')  +'</td>';
									infoc += '<td >'+ infoDoc[0].codmoeda +'</td>';
									infoc += '</tr>';
									
									var valiva = $.number(parseFloat(infoDoc[0].iva01val) + parseFloat(infoDoc[0].iva02val) + parseFloat(infoDoc[0].iva03val) + parseFloat(infoDoc[0].iva04val),2,',','.');
									
									var mercli = parseFloat(infoDoc[0].totalmercli);
									if(infoDoc[0].isentoiva == 'I'){
										mercli = $.number(parseFloat(infoDoc[0].totalmercli) - parseFloat(parseFloat(infoDoc[0].iva01val) + parseFloat(infoDoc[0].iva02val) + parseFloat(infoDoc[0].iva03val) + parseFloat(infoDoc[0].iva04val)), ".$_SESSION['decimaispv'].",',','.');
									}
									
									infoc += '<tr>';
									infoc += '<td> Total Líquido </td>';
									infoc += '<td> &nbsp </td>';
									infoc += '<td class=\"pull-right\">'+ $.number(mercli, ".$_SESSION['decimaispv'].",',','.')  +'</td>';
									infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
									infoc += '</tr>';
									
									/*
									var sujeito = $.number((parseFloat(infoDoc[0].iva01suj) + parseFloat(infoDoc[0].iva02suj) + parseFloat(infoDoc[0].iva03suj) + parseFloat(infoDoc[0].iva04suj)),2,',','.');
									infoc += '<tr>';
									infoc += '<td> Sujeito </td>';
									infoc += '<td> &nbsp </td>';
									infoc += '<td class=\"pull-right\">'+ sujeito +'</td>';
									infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
									infoc += '</tr>';
									*/
									
									
									infoc += '<tr>';
									infoc += '<td> Valor IVA </td>';
									infoc += '<td> &nbsp </td>';
									infoc += '<td class=\"pull-right\">'+ valiva  +'</td>';
									infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
									infoc += '</tr>';
									
									infoc += '</table>';
											
									$('#infoDocumento').html(infoc);
									
									var footerModalHtml = '<h2 class=\"pull-right\"> Total : '+$.number(infoDoc[0].total,2,',','.')+' '+infoDoc[0].codmoeda+' </h2>'; 
									$('#footerModal').html(footerModalHtml);
												
								});
							}
							
						</script>";
	
	echo ($content.$modal.$script.$scriptModal);
}


?>
