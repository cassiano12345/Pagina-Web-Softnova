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
			<li><a href="#">Movimentos de Diários</a></li>
		</ul>
		<div id="contentMain">
			<div class="box span12">
				<div class="box-header" data-original-title="">
					<h2><i class="halflings-icon edit"></i><span class="break"></span>Movimentos de Diários</h2>
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
										<label class="control-label" for="typeahead"> Diario </label>
										<div class="controls">
											<input autocomplete="off" class="span6 typeahead " id="typeahead" placeholder="Pesquisar Cliente" data-provide="typeahead" data-items="4" data-source="[]" type="text" onClick="this.select();">
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
				<caption id="tableCaption" style="text-align: right; font-size: 12px;" >Classificação do Campeonato</caption>
					<thead>
						<tr>
							<th>Data Mov.</th>
							<th>Lanct.</th>
							<th>Conta Geral</th>
							<th>Auxiliar</th>
							<th>Desctritivo</th>
							<th>Doc.</th>
							<th>Serie</th>
							<th>Valor Débito</th>
							<th>Valor Crédito</th>
							
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Data Mov.</th>
							<th>Lanct.</th>
							<th>Conta Geral</th>
							<th>Auxiliar</th>
							<th>Desctritivo</th>
							<th>Doc.</th>
							<th>Serie</th>
							<th>Valor Débito</th>
							<th>Valor Crédito</th>
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


		

';



$script="<script type='text/javascript'>

	// ********************************* VARIAVEIS *************************************


	var oTable;

	var detailsTableHtml;


	// ********************************** INICIAR *********************************************

	$(document).ready(function() {
    var autocomplete = $('#Conta_Geral').typeahead().on('keyup', function(ev) {
        ev.stopPropagation();
        ev.preventDefault();
    });

		$('#typeahead').on('input', function() {
			if($('#typeahead').val().length >=1){
				var cliente = $('#typeahead').val();
				$.get('modules/contabilidade/nome_diario.php', {'cliente': cliente} ,function(infoClientes){
					var obj = $.parseJSON(infoClientes);
					$('#typeahead').data('typeahead').source = obj;
				});
				//console.log($('#typeahead').val());
				
			} else {
				$('#typeahead').data('typeahead').source = [];
			}


			});
		});


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

					//Meter alerts em linhas da tabela
					if(aData[6] =='A'){
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

				var dadoscliente =  $('#typeahead').val();
				var datcli = dadoscliente.split('|');
				var nomecliente = datcli[0].trim();
				var nrcliente 	= datcli[1].trim();
				

				var din = $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
				var fin = $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
				var tipoPesquisa 	= $('#tipoPesquisa').val();
				var Codigo 	= nrcliente;
				
				table = $('#tabelaArtigos').dataTable();
				
				oSettings = table.fnSettings();
				table.fnClearTable(this);

				$.getJSON('modules/contabilidade/tabelacontabilidade_Mov_diarios.php',{'dataini':din,'datafim':fin,'cg':Codigo},function(dataCab){
				
				$.getJSON('modules/contabilidade/total_contabilidade_Mov_Diario.php',{'datafim':fin,'cg':Codigo},function(dataCab2){ 
				
				console.log(dataCab2[0].total);
				document.getElementById('tableCaption').innerText = 'Total:' + dataCab2[0].total ;
						
				});

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
							obj[0] = dataCab[i].datamov;
							obj[1] = dataCab[i].nrlancto;
							obj[2] = dataCab[i].cg;
							obj[3] = dataCab[i].cx;
							obj[4] = dataCab[i].descmovcnt;
							obj[5] = dataCab[i].nrdocofic;
							obj[6] = dataCab[i].serie;
							obj[7] = dataCab[i].vld;
							obj[8] = dataCab[i].vlc;
							obj[9] = dataCab[i].grupo;

							table.oApi._fnAddData(oSettings, obj);
						}
						oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
						table.fnDraw();

						table.fnSort([[1,'asc']]);


						$(document).ready(function() {
        					var table = $('#tabelaArtigos').DataTable();

        					// Aplica o alinhamento inicial
       						 table.on('draw', function() {
							 	$('#tabelaArtigos td:nth-child(8), #tabelaArtigos th:nth-child(8)').css('width', '20px'); // Ajuste o valor conforme necessário
								$('#tabelaArtigos td:nth-child(9), #tabelaArtigos th:nth-child(9)').css('width', '20px'); // Ajuste o valor conforme necessário
								$('#tabelaArtigos td:nth-child(5), #tabelaArtigos th:nth-child(5)').css('width', '180px'); // Ajuste o valor conforme necessário
								$('#tabelaArtigos td').css('font-size', '13px');
								$('#tabelaArtigos td:nth-child(2)').css('text-align', 'center');
								$('#tabelaArtigos td:nth-child(3)').css('text-align', 'center');
								$('#tabelaArtigos td:nth-child(4)').css('text-align', 'center');
            					$('#tabelaArtigos td:nth-child(8)').css('text-align', 'right');
       					 		$('#tabelaArtigos td:nth-child(9)').css('text-align', 'right');
																 $('#tabelaArtigos td:nth-child(9)').each(function() { // Altere o número da coluna conforme necessário
                var valor = parseFloat($(this).text()); // Converte o texto para um número float
                $(this).text(valor.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' })); // Formata como moeda em Euro
            });
								 $('#tabelaArtigos td:nth-child(8)').each(function() { // Altere o número da coluna conforme necessário
                var valor = parseFloat($(this).text()); // Converte o texto para um número float
                $(this).text(valor.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' })); // Formata como moeda em Euro
            });
        					});

							$('#tabelaArtigos td:nth-child(8), #tabelaArtigos th:nth-child(8)').css('width', '20px');
							$('#tabelaArtigos td:nth-child(9), #tabelaArtigos th:nth-child(9)').css('width', '20px');
							$('#tabelaArtigos td:nth-child(5), #tabelaArtigos th:nth-child(5)').css('width', '180px'); // Ajuste o valor conforme necessário
							$('#tabelaArtigos td').css('font-size', '13px');
        					// Reaplica o alinhamento ao inicializar
							$('#tabelaArtigos td:nth-child(2)').css('text-align', 'center');
							$('#tabelaArtigos td:nth-child(3)').css('text-align', 'center');
							$('#tabelaArtigos td:nth-child(4)').css('text-align', 'center');
       						$('#tabelaArtigos td:nth-child(8)').css('text-align', 'right');
       					 	$('#tabelaArtigos td:nth-child(9)').css('text-align', 'right');
							$('#tabelaArtigos td:nth-child(9)').each(function() {
            var valor = parseFloat($(this).text());
            $(this).text(valor.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' }));
        });

							$('#tabelaArtigos td:nth-child(8)').each(function() {
            var valor = parseFloat($(this).text());
            $(this).text(valor.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' }));
        });
    					});
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
		//Imprimira os dados////////////////////////////////////
		function printModalContent() {
		var printContents = document.getElementById('documentView').innerHTML;
		var originalContents = document.body.innerHTML;

		document.body.innerHTML = printContents;

		window.print();

		document.body.innerHTML = originalContents;

		location.reload(); // This is to ensure the page is reloaded after printing
	}
";


	echo ($content.$script);




?>
