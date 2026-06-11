<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");
$content = "";
$contentOptions ='';

/******************************************************************************************************
****************************************************************** PAGINA

	1º --> HTML
	2ª --> ALGUMAS VARIAVEIS
	3º --> ARRANQUE JAVASCRIPT
	4ª --> EXTRAS (MODAL, DIAS, ETC)
	5º --> TABELA (ARTIGO)

*******************************************************************************************************************************************************/


	$loja = 0;


	$content = '
		<ul class="breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="index.php">Home</a>
				<i class="icon-angle-right"></i>
			</li>
			<li><a href="#">Fases de Encomenda</a></li>
		</ul>
		<div id="contentMain">
			<div class="box span12">
				<div class="box-header" data-original-title="">
					<h2><i class="halflings-icon edit"></i><span class="break"></span>Fases de Encomenda</h2>
				</div>

				<div style="display: block;" class="box-content">
					<div clas="span7">
					</div>
					<div class="clearfix"></div>
					<br>
					<div class="span7">
						<form class="form-horizontal" id="formSearch">
							<fieldset>
								<div id="ProcurarArtigo">
									<div class="control-group">
										<label class="control-label" for="datainicial">Data Inicial</label>
										<div class="controls">
											<input class="span12" style="width:50%;" id="datainicial" type="text">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="datafinal">Data Final</label>
										<div class="controls">
											<input class="span12" style="width:50%;" id="datafinal" type="text">
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<button type="submit" class="btn btn-success" onclick="procurarArtigo()" > Pesquisar </button>
										</div>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="waitingBar" class="progress progress-striped progress-success active" style="display:inherit">
				<div id="waitingBarColor" class="bar" style="width:0%;"></div>
		</div>
';

	////////////////////////////////////		OPCOES			///////////////////////////////////
	$content .='

		<div id="divImagens"  class="pull-right" style="z-index: 999; position:relative">
			<table>
				<tr>
					<td align="center"> Info </td>
					<td align="center"> Gráficos </td>
				</tr>
				<tr>
					<td><img  onclick="procurarArtigo()" title="Lista" src="img/list_icon.png" style="width: 70px; height: 70px;"></td>
					<td><img onclick="graficoArtigo()" title="Gráficos" src="img/stats_icon.png" style="width: 70px; height: 70px;"></td>
				</tr>
			</table>
			<br>
		</div>

	';

	////////////////////////////////////		GRAFICO			///////////////////////////////////
	$content .='
		<div class="span11" id="divGrafico">
			<canvas id="graficoVendasArtigosDiarias" height="450px" width="1000px"></canvas>
			<div class="span11" id="seriesgraficoVendasArtigosDiarias"></div>
		</div>

	';

	////////////////////////////////////		TABELA			///////////////////////////////////
	$content .='
			<div id="dataArtigos">
				<table id="tabelaArtigos" class="display row-border order-column" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Encomenda</th>
							<th>Data</th>
							<th>Fase</th>
							<th>Prev Entrega</th>
							<th>Cliente</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Encomenda</th>
							<th>Data</th>
							<th>Fase</th>
							<th>Prev Entrega</th>
							<th>Cliente</th>
						</tr>
					</tfoot>
				</table>
			</div>


			<div style="display:none">
				<table id="detailsTable" class="compact row-border" width="100%">
					<thead>
						<tr>
							<th>Data documento</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>

			<div class="clearfix"></div>

			<div id="divData"></div>

			<div class="clearfix"></div>


			<div id="tabela"> </div>


		<!-- Modal -->
			<div id="myModalDiv" style="display: none">
				<div id="modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-lg">
					<div class="modal-content">

						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<div id="infoTitulo">
								<h4 class="modal-title" id="myModalLabel"> Encomenda Nº: 1505 </h4>
							</div>
						</div>
						<div id="documentView">
							<div class="modal-body">

								<div>
									<h4> Cliente </h4>
									<p id="infoCliente"> José Freitas Almaro dos Santos	</p>
								</div>

								<br>
								<div>
									<h4 id="infoDocumento" class="text-center"> Fase: Corte</h4>
								</div>

								<br>
								<div id="infoProgresso">
									<div id="waitingBar1" class="progress progress-striped progress-success active" style="display:inherit">
										<div id="waitingBarColor1" class="bar" style="width:0%;background-color:#62c462!important"></div>
									</div>
								</div>
								<br>

								<div class="pull-right">
									<h4> Previsão de Entrega </h4>
									<p  id="dataEntrega"> 2021-01-15	</p>
								</div>

								<div>
									<h4> Data da Encomenda </h4>
									<p id="dataEncomenda"> 2021-01-15	</p>
								</div>

							</div>
							<div class="modal-footer"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
';



$script="<script type='text/javascript'>

	// ********************************* VARIAVEIS *************************************


	var oTable;

	var detailsTableHtml;


	// ********************************** INICIAR *********************************************

	$(function() {

		$('#mesProcura').datepicker( {
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'MM yy',
			onClose: function(dateText, inst) {
				var month = $(\"#ui-datepicker-div .ui-datepicker-month :selected\").val();
				var year = $(\"#ui-datepicker-div .ui-datepicker-year :selected\").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
			}
		});

	});

	$(document).ready(function () {




		detailsTableHtml = $('#detailsTable').html();

		var nCloneTh = document.createElement('th');
		var nCloneTd = document.createElement('td');

		$.fn.dataTable.TableTools.defaults.aButtons = [ \"csv\", \"xls\", \"pdf\" ];

		oTable = $('#tabelaArtigos').dataTable({
				bProcessing: true,
				sPaginationType: \"full_numbers\",
				sDom: 'TC<\"clear\">lfrtip',
				aoColumnDefs: [
                        { sClass: \"text-right\", aTargets: [ 3 ] },
                        { sClass: \"text-right\", aTargets: [ 2 ] }
                ],
				oTableTools: {
					sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
				},
				\"fnRowCallback\": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
					if(aData[6] =='A'){
						$(nRow).addClass('alert alert-error');
					}
				}
		});



		$(\"#tabelaArtigos\").tabs( {
			\"active\": function(event, ui) {
				var jqTable = $('table.display', ui.panel);
				if ( jqTable.length > 0 ) {
						jqTable.dataTable().fnAdjustColumnSizing();
				  }
			   }
		});


		limpar();


		var d = new Date();
		var month = d.getMonth()+1;
		var day = d.getDate();

		var output = (day<10 ? '0' : '') + day+ '/' +(month<10 ? '0' : '') + month + '/' + d.getFullYear();

		$('#datainicial').val(output);
		$('#datafinal').val(output);

		$('#mesprocura').val(output);


	});


	$(function() {
		$('#datainicial').datepicker();
		$('#datainicial').datepicker( 'option', 'dateFormat', 'dd/mm/yy');
	});

	$(function() {
		$('#datafinal').datepicker();
		$('#datafinal').datepicker( 'option', 'dateFormat', 'dd/mm/yy');
	});


	function limpar(){
		$('#waitingBarColor').css('width', '0%' );
		$('#waitingBarColor').html('');
		$('#dataArtigos').hide();
		$('#dataVendedor').hide();
		$('#dataLoja').hide();
		$('#divMenuInterior').hide();
		$('#divGrafico').hide();
		$('#divMapa1').hide();
		$('#divImagens').hide();
		$('#divMenuLoja').hide();
		$('#lojasMesDiv').hide();
		$('#totaisDiv').html('');
		$('#divMapa1').html('');
		$('#divData').html('');
		$('#divArtigosMensais').html('');
		$('#divMapa').hide();
		$('#divSubMenuLoja').hide();
		$('#divMensaisLoja').hide();


	}


	// ********************************** EXTRAS **************** (modal, dias, meses, etc..)
	//Mostra o PopUp com detalhes do documento
	function preencheModal(infodoc){

		$('#myModalLabel').empty();
		$('#infoCliente').empty();
		$('#infoDocumento').empty();
		//$('#infoProgresso').empty();
		$('#dataEntrega').empty();
		$('#dataEncomenda').empty();

		$('#myModalDiv').css('display', 'block');

		var din = $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		var fin = $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		var total = 0;
		var percentagem = 0;
		var fase = 0;

		$.getJSON('modules/encomendas/modalEncomenda.php',{'dataini':din,'datafim':fin, 'nroficial':infodoc},function(infoDoc){

			$('#myModalLabel').html('<h4> Encomenda Nº: '+ infoDoc[0].nroficial +'</h4>');

			$('#infoCliente').html('<p> '+ infoDoc[0].nome +'</p>');

			$('#infoDocumento').html('<p> Fase: '+ infoDoc[0].fasedescricao +'</p>');

			$('#dataEncomenda').html('<p> '+ infoDoc[0].datadoc +'</p>');

			$('#dataEntrega').html('<p> '+ infoDoc[0].dataeprevista +'</p>');

			fase = infoDoc[0].fase;
			dif = infoDoc[0].dif;

			if (parseFloat(dif)<=0)
			{
				$.getJSON('modules/encomendas/fasesEncomendas.php',{'dataini':din,'datafim':fin},function(faseDoc){
					total = faseDoc[0].nrfases;
					percentagem = (100/total)*fase;
					$('#waitingBarColor1').css('background-color', '#f02522' );
					$('#waitingBarColor1').html('Previsão ultrapassada!');
					$('#waitingBarColor1').css('width', percentagem+'%' );
				});
			}
			else if(parseFloat(dif)<=5)
			{
				$.getJSON('modules/encomendas/fasesEncomendas.php',{'dataini':din,'datafim':fin},function(faseDoc){
					total = faseDoc[0].nrfases;
					percentagem = (100/total)*fase;
					$('#waitingBarColor1').css('background-color', '#ebbe1c' );
					$('#waitingBarColor1').html('Previsão a terminar!');
					$('#waitingBarColor1').css('width', percentagem+'%' );
				});
			}
			else
			{
				$.getJSON('modules/encomendas/fasesEncomendas.php',{'dataini':din,'datafim':fin},function(faseDoc){
					total = faseDoc[0].nrfases;
					percentagem = (100/total)*fase;
					$('#waitingBarColor1').css('background-color', '#62c462' );
					$('#waitingBarColor1').html('');
					$('#waitingBarColor1').css('width', percentagem+'%' );
				});
			}

		});

	}


	function fnFormatDetails(table_id, html) {
		var sOut = '<table id=\"tabelaLoja' + table_id +'\">';
		sOut += html;
		sOut += \"</table>\";
		return sOut;
	}


	//retorna true se o ano é bissexto
	function leapYear(year)
	{
	  return ((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0);
	}


	//funcao para retornar o numero do mes e os dias que o mes tempArrayData
	// necessidade devido aos anos bissextos

	function getMonthMaxDaysByName(name, year){
		var dataMonth = [];
		switch(name){
			case 'Janeiro':
				dataMonth[0] = 1;
				dataMonth[1] = 31;
				break;
			case 'Fevereiro':
				dataMonth[0] = 2;
				if(leapYear(year))
					dataMonth[1] = 29;
				else
					dataMonth[1] = 28;
				break;
			case 'Março':
				dataMonth[0] = 3;
				dataMonth[1] = 31;
				break;
			case 'Abril':
				dataMonth[0] = 4;
				dataMonth[1] = 30;
				break;
			case 'Maio':
				dataMonth[0] = 5;
				dataMonth[1] = 31;
				break;
			case 'Junho':
				dataMonth[0] = 6;
				dataMonth[1] = 30;
				break;
			case 'Julho':
				dataMonth[0] = 7;
				dataMonth[1] = 31;
				break;
			case 'Agosto':
				dataMonth[0] = 8;
				dataMonth[1] = 31;
				break;
			case 'Setembro':
				dataMonth[0] = 9;
				dataMonth[1] = 30;
				break;
			case 'Outubro':
				dataMonth[0] = 10;
				dataMonth[1] = 31;
				break;
			case 'Novembro':
				dataMonth[0] = 11;
				dataMonth[1] = 30;
				break;
			case 'Dezembro':
				dataMonth[0] = 12;
				dataMonth[1] = 31;
				break;
			default:
				dataMonth[0] = 0;
				dataMonth[1] = 0;
				break;
		}
		return dataMonth;
	}



	/********************************** dia de hoje formatado *********************************/
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


	var m_names = new Array(\"Janeiro\", \"Fevereiro\", \"Março\",
		\"Abril\", \"Maio\", \"Junho\", \"Julho\", \"Agosto\", \"Setembro\",
		\"Outubro\", \"Novembro\", \"Dezembro\");

	var d = new Date();
	var curr_month = d.getMonth();
	var curr_year = d.getFullYear();
	var today2= ( m_names[curr_month] + ' ' + curr_year);




	// *******************************************  TABELA ***********************************************************************************************************************************************

	function procurarArtigo(){
		limpar();
		$('#tabela').empty();
		$('#divData').empty();

		var dataIni 		= $('#datainicial').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var dataFim 		= $('#datafinal').datepicker( 'option', 'dateFormat', 'yymmdd').val();


		var htmldata = '';

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
				$('#dataArtigos').show();
				$('#waitingBarColor').css('width', '50%' );

				var din = $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
				var fin = $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();


				table = $('#tabelaArtigos').dataTable();

				oSettings = table.fnSettings();
				table.fnClearTable(this);

				$.getJSON('modules/encomendas/tabelaEncomendas.php',{'dataini':din,'datafim':fin},function(dataCab){
					total = dataCab.length;

					if(total < 1){
						total = 0;
					}

					if(dataCab.length > 0) {
						cabecalhos = dataCab;
					}

					if (dataCab.length == 0){
						$('#divData').append('<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>');
						$('#waitingBarColor').css('width', '100%' );
						$('#waitingBarColor').html('Completo');
						$('#dataArtigos').hide();
					} else {


						var inc = (dataCab.length/100);

						for (var i=0; i<dataCab.length; i++){

							var obj = new Array();
							obj[0] = dataCab[i].nroficial;
							obj[1] = dataCab[i].datadoc;
							obj[2] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+dataCab[i].nroficial+'\')\">'+dataCab[i].fase+'</a>'
							obj[3] = dataCab[i].dataeprevista;
							obj[4] = dataCab[i].nome;

							table.oApi._fnAddData(oSettings, obj);
						}
						$('#divImagens').show();
						oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
						table.fnDraw();

						table.fnSort([[1,'asc']]);

					}
				});

				$('#waitingBarColor').css('width', '100%' );
				$('#waitingBarColor').html('Completo');
			}
		}

		$('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		$('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
	}


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


	//***************************************** GRAFICO *****************************************

	function graficoArtigo(){
		$('#dataArtigos').hide();
		$('#divGrafico').html('');
		var html = '<canvas id=\"graficoVendasArtigosDiarias\" height=\"500px\" width=\"1000px\"></canvas><br><br><div class=\"span12\" id=\"seriesgraficoVendasArtigosDiarias\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\"></div>';

		$('#divGrafico').html(html);


		var dataIni 		= $('#datainicial').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var dataFim 		= $('#datafinal').datepicker( 'option', 'dateFormat', 'yymmdd').val();


		var din = $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		var fin = $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();


		if(dataIni !='' && dataFim != ''  && dataIni <= dataFim){

			$.getJSON('modules/encomendas/grafico_fases.php',{'dataini':din,'datafim':fin},function(datasetRT){
				var ctx = $('#graficoVendasArtigosDiarias').get(0).getContext('2d');

				var optionsPr = {
									showTooltips: true,
									animationSteps : 200,
									graphTitle : \"Fases de encomendas\",
									legend : true,
									graphTitleFontSize: 18,
									inGraphDataShow: true,
									inGraphDataPaddingX: 5,
									graphMin: 0,
									barBorderRadius: 3,
									scaleShowLabels: false,
									scaleLineColor: \"rgba(0,0,0,.1)\",
									scaleLineWidth: 1,
									annotateDisplay: true,
									spaceRight:20,
									spaceLeft:20,
									scaleLabel : \"<%= formatNumber(value,1, '.', '') + ' '%>\",
									annotateLabel: \"<%='Fase'+(v1!=' ' || v2 !=' ' ? ': ' : '') + v3 %>\",
									yAxisLabel: \"Encomendas\",
									xAxisLabel: \"Fases\",
									responsive : true

								};

				myChart = new Chart(ctx).HorizontalBar(datasetRT, optionsPr);

				$('#seriesgraficoVendasArtigosDiarias').html('');


				var htmlToSeries = '<br><br>';
				var nrOfDatasets = datasetRT.datasets.length;
				var nrOfRows = Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5));


				htmlToSeries = '<table>';

				for(i=1;i<=datasetRT.datasets.length;i++){
					i-=1;
					htmlToSeries += '<tr>';
					for(j=0; j< nrOfRows; j++){
						if(typeof datasetRT.datasets[i] != 'undefined'){
							htmlToSeries += '<th style=\" width:400px; margin-left: 30px;\">';
							htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+';text-shadow: -1px 0 gray, 0 1px gray, 1px 0 gray, 0 -1px '+datasetRT.datasets[i].fillcolor+';\">'+'Nº '+datasetRT.datasets[i].label+': '+datasetRT.datasets[i].data+'('+datasetRT.datasets[i].desc+')'+'</h2>';
							htmlToSeries += '</th>';
							i +=1;
						}
					}
					htmlToSeries += '</tr>';
				}
				htmlToSeries += '</table>';
				$('#seriesgraficoVendasArtigosDiarias').html(htmlToSeries);


			});
			$('#divGrafico').show();
		}
	}
";


	echo ($content.$script);




?>
