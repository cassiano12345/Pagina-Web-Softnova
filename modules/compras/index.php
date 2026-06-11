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

	try{
		//$rs = $db->execute('Select dl.Nome from dash_lojas dl, dash_acessomercados am where dl.codemp= '.$_SESSION["codemp"].' and am.codemp = dl.codemp and am.idmercado   = dl.mercado and am.idgrupo  = '.$_SESSION["grupo"].'');	

		$rs = $db->execute('Select dl.id, dl.Nome from dash_lojas dl, dash_acessos am where dl.codemp   = '.$_SESSION["codemp"].' and am.codemp   = dl.codemp and am.idloja   = dl.id and am.idgrupo  = '.$_SESSION["grupo"].'');

		
		if($rs){
			While($row = $rs->FetchRow()){
				$contentOptions .= '<option value='.$row[0].'>'.$row[1].'</option>';
			}
		}
	} catch(Exception $e) {
		echo $e;
	}
	
	//$id_loja = $_GET["id_loja"];
	//$id_loja = "id_loja";
	//$id_loja 	= "";
	if (isset($_GET["id_loja"])){
		$id_loja = $_GET["id_loja"];
	}
	else{
		$id_loja = "";
	}

	$loja=0;

	if ($id_loja != 0){
		$loja=1;
		$htmlButtonVoltar = '<button class="btn btn-info icon-arrow-left" id="imagemV" onClick="voltar()"> Voltar </button>';

	} else {
		$htmlButtonVoltar = '';
	}	
	

	$content .= ' 

		<ul class="breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="index.php">Home</a> 
				<i class="icon-angle-right"></i>
			</li>
			<li><a href="#">Compras</a></li>
		</ul>
		
		
		<div class="box span11" >

			<div class="box-header" data-original-title="" >
				<h2><i class="halflings-icon edit"></i><span class="break"></span>Compras - Pesquisa</h2>
			</div>
			
			<div style="display: block;" class="box-content">
				<form class="form-horizontal" id="formSearch">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="datainicial">Data Inicial</label>
							<div class="controls">
								<input class="span12" style="width:25%;" placeholder="'. date("d/m/Y") .'" id="datainicial" type="text">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="datafinal">Data Final</label>
							<div class="controls">
								<input class="span12" style="width:25%;" id="datafinal" placeholder="'. date("d/m/Y") .'" type="text">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="loja">Armazém</label>
							<div class="controls">
								<select id="loja">
									<option value="todos">Todos</option>
									'.$contentOptions.'
								</select>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="tipoPesquisa">Pesquisar por</label>
							<div class="controls">
								<select id="tipoPesquisa">
									<option value="todos">Todos</option>
									<option value="fornecedor">Fornecedor</option>
									<!--<option value="artigo">Artigo</option>-->
									
								</select>
							</div>
						</div>
						<div class="control-group">
							<label id="labelTypeahead" class="control-label" for="typeahead"> Nome </label>
							<div class="controls">
								<input autocomplete="off" class="span6 typeahead " id="typeahead" placeholder="Pesquisar" data-provide="typeahead" data-items="4" data-source="[]" type="text" onClick="this.select();">
							</div>
					
							
							<div class="control-group">
								<div class="controls">
									<br><button onclick="pesquisarCompras()" onmousedown="limparProgressBar()" type="button" class="btn btn-success">Pesquisar <i class="halflings-icon search"></i></button>
								</div>
							</div>
						</div>
						
						
					</fieldset>
					
					<div id="waitingBar" class="progress progress-striped progress-success active" style="display:inherit">
						<div id="waitingBarColor" class="bar" style="width:0%;"></div>
					</div>	
					
					
					<div id="initialChart" class="span12">
						<h2>Comparativo Anual por Mês das Compras Efetuadas</h2>
						Anos <select id="selectAnoIni" onChange="mudaAnoComparativo()"></select>
						e	<select id="selectAnoFim" onChange="mudaAnoComparativo()"></select>
						<div><br></div>
						
						<div id="canvasId">
							<canvas id="comparativeMesAno" height="250px" width="850px"></canvas> 
						</div>
						<div id="seriesComparativeMesAno" class="span11 message header" style="border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px"></div>
						
					</div>
					<div class="clearfix"><br> </div>
					
				</form>   
			</div>
		</div>	 ';
					
		$content .= $htmlButtonVoltar.' <br>
				<div class="span11">
					<div id="divImagens"  class="pull-right" style="z-index: 999; position:relative">
						<table>
							<tr><td align="center">Info</td>  <td align="center">Gráficos</td></tr>
							<tr>
								<td><img onclick="pesquisarCompras()" title="Lista" src="img/list_icon.png" style="width: 70px; height: 70px;"></td>
								<td><img onclick="graficoCompras()" title="Gráficos" src="img/stats_icon.png" style="width: 70px; height: 70px;"></td>
							</tr>
						</table>
					</div>
					
					<div class="span11"><br></div>
					<br>
					<div id="tituloGrafico"> </div>	
					<br>					
					<div class="span11" id="divMapa">
						<canvas id="graficoComprasLabels" height="350px" width="350px"></canvas>
						<div class="span4 pull-right" id="seriesGraficoCompras"></div>
					</div>
					<br><br>
					<div id="divData"></div>
					
					
					
					<div id="tabela">
						<table id="tabelacompras" class="display nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th></th>
									<th>Documento</th>
									<th>Data</th>
									<th>Fornecedor</th>
									<th></th>
								</tr>
							</thead>
					 
							<tfoot>
								<tr>
									<th></th>
									<th>Documento</th>
									<th>Data</th>
									<th>Fornecedor</th>
									<th></th>
								</tr>
							</tfoot>
						</table>
					
						<div style="display:none">    
							<table id="detailsTable" class="compact row-border">
								<thead> 
									<tr>
										<th>Código</th>
										<th>Artigo</th>
										<th>Quantidade</th>
										<th>Valor Unit. </th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					
					</div>
				</div>	
			
		
		
		
		
		<div id="divMyModal" style="display: none">
			<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
								
								<table id="detalhesDocumentoTBL" class="compact"> 
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
								
							
								<h2 class="pull-right"> Total : 20.00€ </h2>
								
								<table id="detalhesDocumentoTBL" class="compact"> 
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
									
							</div>
							<!--<br><p class="pull-centre" style="font-size : 11px"> *Este documento não serve de comprovativo oficial. </p>-->
						</div>			
					</div>
					<div id="modalfooter" class="modal-footer">
						<!--<button id="printButton" type="button" class="btn btn-primary"  onClick="printModal()"> Imprimir </button>-->
					</div>
				</div>
			  </div>
			</div>
		</div>
		<iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe> ';

		
	$script = "<script>
	
		var structureTable =  '<table id=\"tabelacompras\" class=\"display /*nowrap*/\" cellspacing=\"0\" width=\"100%\">';
			structureTable += '<thead>';
			structureTable += '		<tr>';
			structureTable += '		<th></th>';
			structureTable += '		<th>Documento</th>';
			structureTable += '		<th>Data</th>';
			structureTable += '		<th>Fornecedor</th>';
			structureTable += '		<th>Total</th>';
			structureTable += '		<th></th>';
			structureTable += '	</tr>';
			structureTable += '	</thead>';
			structureTable += '	 ';
			structureTable += '	<tfoot>';
			structureTable += '	<tr>';
			structureTable += '		<th></th>';
			structureTable += '		<th>Documento</th>';
			structureTable += '		<th>Data</th>';
			structureTable += '		<th>Fornecedor</th>';
			structureTable += '		<th>Total</th>';
			structureTable += '		<th></th>';
			structureTable += '	</tr>';
			structureTable += '</tfoot>';
			structureTable += '</table>';
			structureTable += '	';
		
		
		
		var autocomplete = $('#typeahead').typeahead().on('keyup', function(ev){
			ev.stopPropagation();
			ev.preventDefault();
		}); 
		
		$('#typeahead').on('input', function() {
		
			var selected =document.forms[0].elements['tipoPesquisa'].selectedIndex;
			
			
			if(selected == 1){
				
				var autocomplete = $('#typeahead').typeahead().on('keyup', function(ev){
					ev.stopPropagation();
					ev.preventDefault();
				}); 
				
					if($('#typeahead').val().length >= 1){
						var fornecedor = $('#typeahead').val();
						$.get('modules/fornecedores/listaFornecedores.php', {'fornecedor': fornecedor} ,function(infofornecedores){
							var obj = $.parseJSON(infofornecedores);
							$('#typeahead').data('typeahead').source = obj;
						});
					} else {
						$('#typeahead').data('typeahead').source = [];
					}
								
			} else if(selected == 2){
				
				
				var autocomplete = $('#typeahead').typeahead().on('keyup', function(ev){
					ev.stopPropagation();
					ev.preventDefault();
				}); 
				if($('#typeahead').val().length >= 0){
					var expr = $('#typeahead').val();
					$.getJSON('modules/stocks/detalheArtigo.php',{'exp':expr, 'mode':'cab'},function(infoArtigo){
						$('#typeahead').data('typeahead').source = infoArtigo;
					});
				} else {
					$('#typeahead').data('typeahead').source = [];
				}
				
			}
		});
		
		
		function fnFormatDetails(table_id, html) {
			var sOut = '<table id=\"tabelacompras' + table_id +'\">';
			sOut += html;
			sOut += \"</table>\";
			return sOut;
		}
		
		var iTableCounter = 1;
		var oTable;
		var oInnerTable;
		var detailsTableHtml;
		
	/* dia de hoje formatado */
	var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 
    var today = dd+'/'+mm+'/'+yyyy;
		
	$(document).ready(function blockbackspace(event, n){
		$('#tabela').hide();
		$('#divMapa').hide();
		$('#divImagens').hide();
		$('#tituloGrafico').hide();

		
		document.getElementById('datainicial').value  = today;
		document.getElementById('datafinal').value  = today;
		
		// you would probably be using templates here
		detailsTableHtml = $('#detailsTable').html();

		//Insert a 'details' column to the table
		var nCloneTh = document.createElement('th');
		var nCloneTd = document.createElement('td');
	
		$.fn.dataTable.TableTools.defaults.aButtons = [ \"csv\", \"xls\", \"pdf\" ];
		
		oTable = $('#tabelacompras').dataTable({
				sPaginationType: \"full_numbers\",
				sDom: 'TC<\"clear\">lfrtip',
				oTableTools: {
					sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
				}
		});
			
				
		$(\"#tabelacompras\").tabs({
			active: function(event, ui) {
						var jqTable = $('table.display', ui.panel);
						if ( jqTable.length > 0 ) {
							jqTable.dataTable().fnAdjustColumnSizing();           
						}
					}
		});
		
		
		$('#tabelacompras').DataTable({
			bDestroy : true,
			bProcessing: true,
			bRetrieve : true
		});
				
		
		
		if($loja==1){
			document.getElementById('loja').value  = id_loja ; 	
			document.getElementById('datainicial').value  = today;
			document.getElementById('datafinal').value  = today;
			$('#tabelacompras').show();
			pesquisarCompras();
		}

		
		$('#tabela').hide();
		//$('#tabelacompras').hide();****************
		$('#table2').hide();
		
		
		$.getJSON('modules/compras/comprasChart.php',{'mode':'anosCompras'}, function(anoscompras){
			
			if(anoscompras.length > 0){
				for(var i=0; i < anoscompras.length; i++){
					document.getElementById('selectAnoIni').add(new Option(anoscompras[i],anoscompras[i]));
					document.getElementById('selectAnoFim').add(new Option(anoscompras[i],anoscompras[i]));
				}
				document.getElementById('selectAnoIni').selectedIndex = 0;
				document.getElementById('selectAnoFim').selectedIndex = 1;
				mudaAnoComparativo();
			}
			
		});
		
	});
		

	function mudaAnoComparativo(){	
	
		//var ini = $('#selectAnoIni').val();
		//var fim = $('#selectAnoFim').val();
		
		
		var ini = $('#selectAnoIni').find(\":selected\").text();
		var fim = $('#selectAnoFim').find(\":selected\").text();
		
		
		$.getJSON('modules/compras/comprasChart.php',{'mode':'comparativoAnoMes', 'anoini':ini, 'anofim':fim}, function(dataVendasAnuais){
				
			if(dataVendasAnuais != ''){
			
				$('#canvasId').html('');
				var htmlCanvas = '<canvas id=\"comparativeMesAno\" height=\"350\" width=\"850\"></canvas>';
				$('#canvasId').html(htmlCanvas);
				
			
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
					responsive : true,
							animation: true,
							scaleOverride: true,
							scaleStartValue: 0,
							scaleStepWidth: stepSize,
							scaleSteps: steps,
							scaleShowGridLines : true,
							scaleGridLineWidth : 1,
							scaleShowHorizontalLines : true,
							bezierCurve : true,
							bezierCurveTension : 0.4,
							pointDot : true,
							pointDotRadius : 4,
							pointDotStrokeWidth : 2,
							pointHitDetectionRadius : 20,
							datasetStroke : true,
							datasetStrokeWidth : 2,
							datasetFill : true,
							showTooltips: true,							
							scaleLabel : \"<%= Number(value).toFixed(2).replace('.', ',') + ' €'%>\",
							legend : true,
							annotateDisplay : true,
							annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
							inGraphDataShow : false,							
							datasetFill : true,
							tooltipFillColor: 'rgba(0,0,0,0.8)',
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
		
	}
		
	function voltar(){
		var lojaSelecionada = $('#loja option:selected').text();
		$.get('modules/loja/index.php',{'nomeloja': lojaSelecionada} , function(data){
			$('#content').html(data);
		});
	}
	
	
	function popoverDoc(){
		$(\"a[ rel=popover]\").popover({placement : 'right'}).click(function(e) {
			e.preventDefault();
		});
	}

	$(function() {
		$('#datainicial').datepicker();
		$('#datainicial').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
	});
	
	$(function() {
		$('#datafinal').datepicker();
		$('#datafinal').datepicker( 'option', 'dateFormat', 'dd/mm/yy');
	});

	function limparProgressBar(){
		$('#waitingBarColor').css('width', '0%' );
		$('#waitingBarColor').html('');				
	}

	function pesquisarCompras(){
		
		$('#initialChart').hide();
	
		$('#divData').html('');
		$('#divMapa').hide();
		$('#divMapa').html('');
		
		$('#divImagens').hide();
		$('#tabela').hide();
		
		$('#tituloGrafico').hide();
		

		// É NECESSARIO DESTRUIR A TABELA E VOLTAR A CONSTRUIR DE NOVO
		// DEVIDO AOS ELEMENTOS DOM QUE FICAM ARMAZENADOS
		$('#tabela').empty();
		$('#tabela').html(structureTable);

		var dataIni 		= $('#datainicial').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var dataFim 		= $('#datafinal').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var tipoPesquisa 	= $('#tipoPesquisa').val();
		var expr 			= $('#typeahead').val().split(' | ')[0];
		var loja 			= $('#loja').val();
		
		var din = $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		var fin = $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		
		if(document.getElementById('loja').value=='todos')
			nome_loja='Todas as Lojas';
		else{
			var x = document.getElementById('loja')
			nome_loja = x.options[x.selectedIndex].text;
		}
		
		$('#tituloGrafico').html('<h2 align=\"center\"> Compras de: '+din+' a '+ fin +' - '+ nome_loja +' </h2>');
		
		
		
		var htmldata = '';
		var totalCompras = parseFloat(0);
	
		var oTable2 = $('#tabelacompras').dataTable({
				sPaginationType: \"full_numbers\",
				sDom: 'TC<\"clear\">lfrtip',
				aoColumnDefs: [
					{ sClass: \"text-right\", aTargets: [ 4 ] }
				],
				oTableTools: {
					sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
				}
		});

		$('#tabelacompras').tabs({
				active: function(event, ui) {
					var jqTable = $('table.display', ui.panel);
					if ( jqTable.length > 0 ) {
						jqTable.dataTable().fnAdjustColumnSizing();           
					}
				}
		});			
			
		if(!dataIni || !dataFim){
			$('#divData').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Existem campos por preencher!</div>');
			$('#waitingBarColor').css('width', '100%' );
			$('#waitingBarColor').html('Completo');
		}else{
			if(dataIni > dataFim){
				$('#divData').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Data Inicial superior à data final!</div>');
				$('#waitingBarColor').css('width', '100%' );
				$('#waitingBarColor').html('Completo');
			} else {

				$('#waitingBarColor').css('width', '50%' );
									
				$.getJSON('modules/compras/compras.php',{'dataini':din,'datafim':fin,'tipoPesquisa':tipoPesquisa,'exp':expr,'loja':loja},function(data){
					if (data.length == 0){
						$('#divData').append('<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>');	
						$('#waitingBarColor').css('width', '100%' );
						$('#waitingBarColor').html('Completo');
					} else {
					
						$('#divImagens').show();
						$('#tabela').show();
		
		
						$.getJSON('modules/compras/linhasCompra.php',{'dataini':din,'datafim':fin,'tipoPesquisa':tipoPesquisa,'exp':expr,'loja':loja},function(linhas){
						
							$('#tabela').show();
							
							var finalData		= [];
							var count_finalData = 0;
							var dataArray 		= [];
							var posLinha 		= 0;	
							
							
							for(i=0; i< data.length; i++){ 
								var tempDetailsRow = []; 
								var count_details =0;
								
								while(posLinha < linhas.length && linhas[posLinha].serie == data[i].serie && linhas[posLinha].nroficial == data[i].nroficial ){
									var detailsRow  =  { codigo :  linhas[posLinha].codigo , desart : linhas[posLinha].desart , qtd : linhas[posLinha].qtd, preco : linhas[posLinha].pvn };
									
									tempDetailsRow[count_details] 	=   detailsRow ;
									count_details 	+= 1;
									posLinha 		+= 1;
								}
								
								var tempRow = { tipodoc : data[i].tipodoc, serie : data[i].serie, nroficial : data[i].nroficial, datadoc : data[i].datadoc, fornecedor : data[i].nome, details : tempDetailsRow };	
								
								var imgrow = 'img_'+i;
								
								var modalParameter = data[i].nroficial+'/'+data[i].serie+'/'+data[i].tipodoc;
								
								var tempArrayData 	= [];
								tempArrayData[0]	=  '<img id=\"'+imgrow+'\" src=\"img/additional/plus.png\">';
								tempArrayData[1] 	=  data[i].tipodoc + ' / ' + data[i].serie + ' / ' + data[i].nroficial;						
								tempArrayData[2] 	=  data[i].datadoc;
								tempArrayData[3] 	=  data[i].nome;
								//tempArrayData[4] 	=  parseFloat(data[i].total).toFixed(2);
								tempArrayData[4]	= $.number(data[i].total,".$_SESSION['decimaispv'].",',','.') +' '+ data[i].moeda;

								tempArrayData[5]	=  '<img  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+modalParameter+'\')\" src=\"img/additional/print.png\" \">';
								
								
								finalData[count_finalData]  =  tempRow;
								dataArray[count_finalData]  =  tempArrayData;
								count_finalData   += 1;
							}
							
							oTable = $('#tabelacompras').DataTable();
									
							oSettings = oTable.fnSettings();
							oTable.fnClearTable(this);

							for(i=0; i<dataArray.length;i++){
								oTable.oApi._fnAddData(oSettings, dataArray[i]);
							}
								
							oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
							oTable.fnDraw();
						
							$('#tabelacompras tbody ').on('click', 'tr', function () {
							
								var nTr = $(this)[0];
								var nTds = this;
								
								if (oTable.fnIsOpen(nTr)) {
									var rowIndex		= oTable.fnGetPosition( $(nTds).closest('tr')[0] ); 
									var idimg = '#img_'+rowIndex;
									$(idimg).attr('src', 'img/additional/plus.png');
									oTable.fnClose(nTr);
								}
								else {
								
									var rowIndex		= oTable.fnGetPosition( $(nTds).closest('tr')[0] ); 
									var  idimg = '#img_'+rowIndex;
									$(idimg).attr('src', 'img/additional/minus.png');
									
									var detailsRowData  = finalData[rowIndex].details;
									oTable.fnOpen(nTr, fnFormatDetails(iTableCounter, detailsTableHtml), 'details');
									oInnerTable = $('#tabelacompras' + iTableCounter).dataTable({
										aaData		: detailsRowData,
										bPaginate	: false,
										bFilter: false,
										bInfo: false,
										aoColumns	: [ 
														{ mDataProp: \"codigo\" },
														{ mDataProp: \"desart\" },
														{ mDataProp: \"qtd\" 	},
														{ mDataProp: \"preco\" 	}
													  ]		  
									});
									
									iTableCounter = iTableCounter + 1;
								}
							});
								$('#waitingBarColor').css('width', '100%' );
								$('#waitingBarColor').html('Completo');
						});
					}
				});
			}
		}
		
		$('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		$('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();	
	}
		
		
		
	function printModal(){
		//var printDivCSS = '<link href=\"../../css/iframePrintStyle.css\" rel=\"stylesheet\" type=\"text/css\">';
		window.frames[\"print_frame\"].document.body.innerHTML =  document.getElementById(\"documentView\").innerHTML;
		window.frames[\"print_frame\"].window.focus();
		window.frames[\"print_frame\"].window.print();
	}	
	
	function preencheModal(infod){
	
		$('#divMyModal').css('display', 'block');
		
		
		$('#infoTitulo').empty();
		$('#infoEmpresa').empty();
		$('#infoCliente').empty();
		$('#infoDocumento').empty();
		
		var infoArray = infod.split('/');

		$.getJSON('modules/compras/detalhesCompra.php',{'nroficial': infoArray[0].trim(), 'serie': infoArray[1].trim(),'tipodoc': infoArray[2].trim()},function(infoDoc){
			
			$('#infoTitulo').html('<h2> Documento Nr. '+ infod +'</h2>');
			$('#infoEmpresa').html('".$_SESSION['nomeEmp']."');
			
			$('#infoCliente').html('<h3> Documento Nr. '+ infod +'</h3>');
			$('#infoCliente').append('<p>Exmo(s). Sr(s). </p><p> '+infoDoc[0].fornecedor +'</p><p> '+infoDoc[0].morada+' </p><p> '+infoDoc[0].codpostal +' - '+infoDoc[0].localidade +'</p>');

			
			
			var infoc = '<br><br><table id=\"detalhesDocumentoTBL\" class=\"table table-striped\" ><thead><tr><th>Artigos</th><th>Quantidade</th><th>Preço Unit.</th><th>IVA</th><th>Total</th></tr></thead><tbody>';
			
			
			
			for(i=0; i<infoDoc.length;i++){
				var total = (infoDoc[i].pvn*infoDoc[i].qtd);
				var totalCdesc = total - infoDoc[i].dsc;
				infoc += '<tr>';
				infoc += '<td>'+infoDoc[i].desart+'</td>';
				infoc += '<td style=\"text-align: right;\">'+parseFloat(infoDoc[i].qtd).toFixed(3)+'</td>';
				infoc += '<td style=\"text-align: right;\">'+parseFloat(infoDoc[i].pvn).toFixed(2)+'</td>';
				infoc += '<td style=\"text-align: right;\">'+parseFloat(infoDoc[i].taxaiva).toFixed(2)+'%</td>';
				//infoc += '<td style=\"text-align: right;\">'+parseFloat(total).toFixed(2)+'</td>';
				//infoc += '<td style=\"text-align: right;\">'+parseFloat(infoDoc[i].dsc).toFixed(2)+'</td>';
				infoc += '<td style=\"text-align: right;\">'+parseFloat(totalCdesc).toFixed(2)+'</td>';
				infoc += '</tr>';	
			}
			
			infoc += '</tbody>';
			infoc += '</table>';
			
			
			infoc += '<table class=\"pull-right\">';
			
			infoc += '<tr>';
			infoc += '<td> Total Ilíquido </td>';
			infoc += '<td> &nbsp </td>';
			infoc += '<td class=\"pull-right\">'+ $.number(parseFloat(infoDoc[0].totalmercil).toFixed(2),2,',','.')  +'</td>';
			infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
			infoc += '</tr>';

			
			infoc += '<tr>';
			infoc += '<td> Desconto </td>';
			infoc += '<td> &nbsp </td>';
			infoc += '<td class=\"pull-right\">'+ $.number(parseFloat(infoDoc[0].totaldescln).toFixed(2),2,',','.')  +'</td>';
			infoc += '<td style=\"text-align: right;\">'+ infoDoc[0].codmoeda +'</td>';
			infoc += '</tr>';
			
			
			infoc += '<tr>';
			infoc += '<td> Total Líquido </td>';
			infoc += '<td> &nbsp </td>';
			infoc += '<td class=\"pull-right\">'+ $.number(parseFloat(infoDoc[0].totalmercli).toFixed(2),2,',','.')  +'</td>';
			infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
			infoc += '</tr>';
			
			
			infoc += '<tr>';
			infoc += '<td> Total IVA </td>';
			infoc += '<td> &nbsp </td>';
			var totalIVA = (parseFloat(infoDoc[0].iva01val)+parseFloat(infoDoc[0].iva02val)+parseFloat(infoDoc[0].iva03val)+parseFloat(infoDoc[0].iva04val));
			infoc += '<td class=\"pull-right\">'+ $.number(parseFloat(totalIVA).toFixed(2),2,',','.')  +'</td>';
			infoc += '<td style=\"text-align: right;\">'+ infoDoc[0].codmoeda +'</td>';
			infoc += '</tr>';
			/*
			infoc += '<tr>';
			infoc += '<td> Total </td>';
			infoc += '<td> &nbsp </td>';
			infoc += '<td class=\"pull-right\">'+ parseFloat(infoDoc[0].totalcompra).toFixed(2)  +'</td>';
			infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
			infoc += '</tr>';
			*/
			infoc += '</table>';
			
			
			$('#infoDocumento').html(infoc);
			
			
			var footerModalHtml = '<h2 class=\"pull-right\"> Total : '+$.number(infoDoc[0].totalcompra,2,',','.')+' '+infoDoc[0].codmoeda+' </h2>';
			$('#modalfooter').html(footerModalHtml);
			
		});
			
	}
	
	
	function graficoCompras(){
		$('#tabela').hide();
		$('#divMapa').html(''); 
		$('#tituloGrafico').show();
		
		var html = '<canvas id=\"graficoComprasLabels\" height=\"500px\" width=\"1000px\"></canvas><div class=\"span12\" id=\"seriesGraficoCompras\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\" ></div>';		

		var dataIni 		= $('#datainicial').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var dataFim 		= $('#datafinal').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var tipoPesquisa 	= $('#tipoPesquisa').val();
		var expr 			= $('#typeahead').val().split(' | ')[0];
		var loja 			= $('#loja').val();

		var din = $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		var fin = $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		
		if(dataIni !='' && dataFim != ''  && dataIni <= dataFim){
			

			$('#divMapa').html(html);

			$.getJSON('modules/compras/graficocompras.php',{'dataini':din,'datafim':fin,'tipoPesquisa':tipoPesquisa,'exp':expr,'loja':loja},function(datasetRT){
					
				var optionsPr = {
									animation: true,
									inGraphDataShow: true,
									inGraphDataTmpl: \"<%= v3 + ' €'%>\",
									scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
									showTooltips: true,											
									annotateDisplay: true,			              
									annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
									responsive : true	
								};

				var ctx = $('#graficoComprasLabels').get(0).getContext('2d');
				
				myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
				$('#seriesGraficoCompras').html('');

				var htmlToSeries = '<br><br>';
				var nrOfDatasets = datasetRT.datasets.length;
				var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
				
				
				htmlToSeries = '<table>';
				
				for(i=1;i<=datasetRT.datasets.length;i++){
					i-=1;
					htmlToSeries += '<tr>';
					for(j=0; j< nrOfRows; j++){
						if(typeof datasetRT.datasets[i] != 'undefined'){
							htmlToSeries += '<th style=\" width:170px; margin-left: 30px;\">';
							htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+';text-shadow: -1px 0 gray, 0 1px gray, 1px 0 gray, 0 -1px '+datasetRT.datasets[i].fillcolor+'; \">'+datasetRT.datasets[i].label+'</h2>';
							htmlToSeries += '</th>';
							i +=1;
						}
					}
					htmlToSeries += '</tr>';
				}		
				htmlToSeries += '</table>';
				$('#seriesGraficoCompras').html(htmlToSeries);							
			});
			$('#divMapa').show();
		
		}
	}
	
	
	</script>";

	
	
	echo ($content.$script);
	
}


?>