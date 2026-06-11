
<?php


include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if (!isset($_SESSION['state'])){
	echo 0;
	header("Location: ../../index.php");
}
else{

	$cabecalho = '
			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.php">Home</a>
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="#">Fornecedores</a></li>
			</ul>

			<div class="box span12">
				<div class="clearfix"></div>
				<div class="box-header" data-original-title="">
					<h2><i class="halflings-icon edit"></i><span class="break"></span>Fornecedores</h2>
				</div>


				<div style="display: block;" class="box-content">
					<div class="clearfix"></div>
					<div class="span12">
						<form class="form-horizontal" id="formSearch">
							<fieldset>
								<div id="divExistencias">
									<div class="control-group">
										<label class="control-label" for="typeahead"> Nome </label>
										<div class="controls">
											<input autocomplete="off" class="span6 typeahead " id="typeahead" placeholder="Pesquisar Fornecedor para visualizar dados" data-provide="typeahead" data-items="4" data-source="[]" type="text" onClick="this.select();">
										</div>
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<button onclick="procurar()" onmousedown="limparProgressBar()" type="button" class="btn btn-success">Pesquisar <i class="halflings-icon search"></i></button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>


			<div id="waitingBar" class="progress progress-striped progress-success active" style="display:block">
				<div id="waitingBarColor" class="bar" style="width:0%;"></div>
			</div>

			<div id="divMenuInterior" class="pull-right" style="z-index: 999; position:inherit;">
				<table>
				<tr><td align="center">Info</td><td align="center">Gráficos</td></tr>
				<tr>
					<td><img onClick="setListMode()" title="Lista" src="img/list_icon.png" style="width: 70px; height: 70px;"></td>
					<td><img onClick="setGraphMode()" title="Gráficos" src="img/stats_icon.png" style="width: 70px; height: 70px;"></td>
				</tr>
				</table>
			</div>

			<div id="divTopFornecedores" class="span12">
				<div ondesktop="span10" ontablet="span8" class="box green span6">
					<div class="box-header">
						<h2 style="color:#ffffff;"><i class="halflings-icon white user"></i><span class="break"></span> Top 5 - Fornecedores</h2>
						<select class="pull-right" style="height: 26px; width: 100px; margin-top:-5px;" id="ano"></select>
					</div>
					<div class="box-content">
						<ul class="dashboard-list metro white sa" id="liTopFive">

						</ul>
					</div>
				</div>

				<div ondesktop="span10" ontablet="span8" class="box blue span6">
					<div class="box-header">
						<h2 style="color:#ffffff;"><i class="halflings-icon white user"></i><span class="break"></span> Top 5 - Novos Fornecedores</h2>
					</div>
					<div class="box-content">
						<ul class="dashboard-list metro white sa" id="liTopNovos">

						</ul>
					</div>
				</div>
			</div>


			<div id="divData"></div>




			<div id="detalhes">
				<div style="display: block;" class="box-content">

					<br>
					<form class="form-horizontal" id="formSearch">
						<fieldset>
							<dl>
								<dt style="color: #19B624;" id="nomefornecedor"></dt>
							</dl>

							<div class="tooltip-demo">
								<p style="margin-bottom: 0;" class="muted" id="fornecedorDesde">
									Fornecedor '.$_SESSION["nomeEmp"].' desde 15/07/2004
								</p>
							</div>

							<div id="detailsProduct" >
								<div class="span6">
									<div class="controls">
										<dl>
											<dt> Morada </dt>
											<dd id="moradafornecedor"></dd>
											<dt> Sublocalidade </dt>
											<dd id="sublocalidadefornecedor"></dd>
											<dt> Localidade </dt>
											<dd id="codpostalfornecedor"></dd>
											<dd id="localidadefornecedor"></dd>
										</dl>
									</div>
									<div class="controls">
										<dl>
											<dt> País </dt>
											<dd id="paisfornecedor"></dd>
										</dl>
									</div>
									<div class="controls">
										<dl>
											<dt> Nr. Contribuinte </dt>
											<dd id="contribuintefornecedor"></dd>
										</dl>
									</div>
								</div>
								<div class="span5 noMarginLeft">
									<div class="controls row-fluid" >
										<dl>
											<dt> Telefone </dt>
											<dd id="telefonefornecedor"></dd>
											<dt> Telemóvel </dt>
											<dd id="telemovelfornecedor"></dd>
											<dt> Fax </dt>
											<dd id="faxfornecedor"></dd>
											<dt> E-mail </dt>
											<dd id="emailfornecedor"></dd></dl>
										</dl>
									</div>
									<div class="controls">
										<dl>
											<dt> NIB </dt>
											<dd id="nibFornecedor"></dd>
										</dl>
									</div>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>


			<div class="box span12" id="divContactosfornecedor" style="overflow:hidden; height:50%; width:100%">
				<div class="box-header green" data-original-title="" >
					<h2><i class="halflings-icon edit"></i><span class="break"></span>Contactos</h2>
				</div>
				<div class="span11 table-responsive" style="overflow: auto; height: 100%; width: 100%; padding-right: 20px;"  >
					<table id="tabelaContatos" class="table">
						<thead>
							<tr>
								<th>Nome</th>
								<th>Cargo</th>
								<th>Telemóvel</th>
								<th>E-mail</th>
								<th>Observações</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="box span12" id="divDocumentosfornecedor">
				<div class="box-header blue" data-original-title="">
					<h2><i class="halflings-icon edit"></i><span class="break"></span>Documentos</h2>
				</div>
				<div style="display: block;" class="box-content">
					<div class="table-responsive" >
						<!-- <h2>Documentos</h2> -->
						<table id="tabelaDocumentosfornecedor" class="display row-border order-column" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Documento</th>
									<th>Data</th>
									<th>Total</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Documento</th>
									<th>Data</th>
									<th>Total</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>


			<div id="divLayoutCorrection" style="display: block">
				<img src="img/barra.png"/>
			</div>

			<div id="graphNoInfo" class="span12 style="display:none;">
				<br>
				<div class="alert alert-info" role="alert" >
					<span class="halflings-icon exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Info:</span>Não existe informação suficiente para gerar todos os gráficos!
				</div>
			</div>


				<!-- Modal -->
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

								<div id="infofornecedor">
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
						<!-- <button id="printButton" type="button" class="btn btn-primary"  onClick="printModal()"> Imprimir </button> -->
					</div>

				  </div>
				</div>
			  </div>
			</div>

			<iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>

			<div id="divGraph" class="span11">
			    <div class="span11" id="comprasForn_titulo" align="center"> <h2> </h2> </div>
					<br>
					<br>

				<div class="span11" id="divComprasFornecedor" >
					<canvas id="ComprasFornecedor_Grafico" height="450px"></canvas>
				</div>
				<div id="seriesComprasFornecedor"  class="pull-right"></div>

				<div class="span11" id="comprasFornAnual_titulo" align="center"> <h2> <br><br><br> </h2> </div>
					<br>
					<br>

				<div class="span11" id="divComprasMesAnualFornecedor" >
					<canvas id="ComprasMesAno_Grafico" height="450px"></canvas>
				</div>
				<div id="seriesComprasMesAno"  class="pull-right"></div>
			</div>


			';

	$script="<script>


		$(document).ready(function () {


			$('#waitingBar').hide();
			$('#detalhes').hide();
			$('#divData').hide();
			$('#divContactosfornecedor').hide();
			$('#divDocumentosfornecedor').hide();
			$('#divGraph').hide();
			$('#comprasForn_titulo').hide();
			$('#divMenuInterior').hide();
			$('#comprasFornAnual_titulo').hide();

			$('#graphNoInfo').hide();

			$.fn.dataTable.TableTools.defaults.aButtons = [ \"csv\", \"xls\", \"pdf\" ];

			oTable = $('#tabelaDocumentosFornecedor').dataTable({
					sPaginationType: \"full_numbers\",
					bProcessing: true,
					sDom: 'TC<\"clear\">lfrtip',
					oTableTools: {
						sSwfPath: 'media/swf/copy_csv_xls_pdf.swf'
					},
					aoColumnDefs: [
                        { sClass: \"text-right\", aTargets: [ 2 ] }
					]
			});


			$(\"#tabelaDocumentosFornecedor\").tabs( {
				\"active\": function(event, ui) {
					var jqTable = $('table.display', ui.panel);
					if ( jqTable.length > 0 ) {
							jqTable.dataTable().fnAdjustColumnSizing();
					  }
				   }
			});

			$.getJSON('modules/fornecedores/outrasInfo.php', {'tag': 'topfive', 'desde':'sempre'} ,function(topfive){
				if(topfive.length>0){
					$('#divLayoutCorrection').css('display','none');
					var list = '';
					for(i=0; i<topfive.length; i++){
						var fornSearch = topfive[i].nome+' | '+topfive[i].nrfornecedor;
						list += '<li class=\"text-success\" onClick=\"getThisForn(\''+fornSearch+'\')\">';
						list += '<a>';
						list += '<img src=\"img/new_user.png\" class=\"avatar\">';
						list += '</a>';
						list += '<strong>Nr. Fornecedor: &nbsp </strong>'+topfive[i].nrfornecedor+'<br>';
						list += '<strong>Nome: &nbsp'+topfive[i].nome+'</strong><br>';
						list += '<strong>Montante: &nbsp</strong>'+$.number(topfive[i].montante, 2,',','.')+' '+topfive[i].moeda+'<br>';
						list += '</li>';
					}
					$('#liTopFive').html(list);
				}
				else {
					$('#divTopFornecedores').hide();
				}
			});

			$.getJSON('modules/fornecedores/outrasInfo.php', {'tag': 'topNovosFornecedores'} ,function(topNovos){
				if(topNovos.length>0){
					var list = '';
					for(i=0; i<topNovos.length; i++){
						var fornSearch = topNovos[i].nome+' | '+topNovos[i].nrfornecedor;
						list += '<li class=\"text-info\" onClick=\"getThisForn(\''+fornSearch+'\')\">';
						list += '<a>';
						list += '<img src=\"img/user_metro2.png\" class=\"avatar\">';
						list += '</a>';
						list += '<strong>Nr. Fornecedor: &nbsp </strong>'+topNovos[i].nrfornecedor+'<br>';
						list += '<strong>Nome: &nbsp'+topNovos[i].nome+'</strong><br>';
						list += '<strong>Data Registo: &nbsp</strong>'+topNovos[i].datareg+'<br>';
						list += '</li>';
					}
					$('#liTopNovos').html(list);
				}
				else {
					$('#divTopFornecedores').hide();
				}
			});



			//preencher o selector no top 5 para escolher os fornecedores
			$.getJSON('modules/clientes/outrasInfo.php', {'tag': 'anoconst'} ,function(anoconstdata){
				var anoconstituicao = parseInt(anoconstdata[0].anoconst);
				var year = new Date().getFullYear();

				document.getElementById('ano').add(new Option('Sempre', 'sempre'));
				do{
					document.getElementById('ano').add(new Option(year,year));
					year --;
				}while(anoconstituicao<=year);

			});


		});


		//  TOP5Fornecedores
		//evento para quando trocar o ano verificar o top 5 fornecedores
		$('#ano').change(function() {
			var anoPesquisa = $('#ano').val();
			$.getJSON('modules/fornecedores/outrasInfo.php', {'tag': 'topfive', 'desde':anoPesquisa} ,function(topfive){
				if(topfive.length>0){
					$('#divLayoutCorrection').css('display','none');
					var list = '';
					for(i=0; i<topfive.length; i++){
						var fornSearch = topfive[i].nome+' | '+topfive[i].nrfornecedor;
						list += '<li class=\"text-success\" onClick=\"getThisForn(\''+fornSearch+'\')\">';
						list += '<a>';
						list += '<img src=\"img/new_user.png\" class=\"avatar\">';
						list += '</a>';
						list += '<strong>Nr. Fornecedor: &nbsp </strong>'+topfive[i].nrfornecedor+'<br>';
						list += '<strong>Nome: &nbsp'+topfive[i].nome+'</strong><br>';
						list += '<strong>Montante: &nbsp</strong>'+$.number(topfive[i].montante, 2,',','.')+' '+topfive[i].moeda+'<br>';
						list += '</li>';
					}
					$('#liTopFive').html(list);
				}
				else {
					//$('#divTopClientes').hide();
					alert('Não foram encontradas informações relativas ao ano de '+anoPesquisa+'.');
				}
			});
		});



		function getThisForn(forn){
			$('#typeahead').val(forn);
			limparProgressBar();
			procurar();
		}



		function limparProgressBar(){
			$('#waitingBarColor').css('width', '0%' );
			$('#waitingBarColor').html('');
		}


		var nrfornecedorGlobal=0;

		function procurar(){
			$('#waitingBar').show();
			$('#divLayoutCorrection').css('display','none');
			$('#divTopFornecedores').hide();
			$('#divMenuInterior').hide();
			$('#divGraph').hide();
			$('#graphNoInfo').hide();



			if($('#typeahead').val().length > 0){

				if($('#typeahead').val().split('|').length == 2){
					var dadosfornecedor =  $('#typeahead').val();
					var datforn 		= dadosfornecedor.split('|');
					var nomefornecedor 	= datforn[0].trim();
					var nrfornecedor 	= datforn[1].trim();

					nrfornecedorGlobal = nrfornecedor;

					$.getJSON('modules/fornecedores/detalhesFornecedor.php', {'fornecedor': nrfornecedor} ,function(detCli){

						if(detCli.length >0){


							$('#divMenuInterior').show();

							$('#detalhes').show();
							$('#divData').hide();
							$('#nomefornecedor').html('<h1><span style=\"font-size : 22px; font-weight: bold;\">'+detCli[0].nrfr+'</span>  '+detCli[0].nome+' </h1>');
							var formatData = detCli[0].data_reg.split('-')[2] + '/' + detCli[0].data_reg.split('-')[1] +'/'+ detCli[0].data_reg.split('-')[0];
							$('#fornecedorDesde').html('Fornecedor ".$_SESSION["nomeEmp"]." desde '+ formatData);
							$('#moradafornecedor').html(detCli[0].mor);
							$('#sublocalidadefornecedor').html(detCli[0].cp+' - '+detCli[0].sub);
							$('#localidadefornecedor').html(detCli[0].loc);
							$('#paisfornecedor').html(detCli[0].pais);
							$('#contribuintefornecedor').html(detCli[0].contribuinte);
							$('#telefonefornecedor').html(detCli[0].tel);
							$('#telemovelfornecedor').html(detCli[0].tlm);
							$('#faxfornecedor').html(detCli[0].fax);
							$('#emailfornecedor').html(detCli[0].email);
							$('#nibFornecedor').html(detCli[0].nib);

							$.getJSON('modules/fornecedores/outrasInfo.php', {'tag': 'pais', 'pais':detCli[0].pais} ,function(detPais){
								$('#paisfornecedor').append(' - '+detPais[0].pais);
							});

							$.getJSON('modules/fornecedores/outrasInfo.php', {'tag': 'contatos', 'nrfr':nrfornecedor} ,function(detContatos){

								if(detContatos.length > 0){
									$('#divContactosfornecedor').show();

									var tabela = '';
									tabela += '<thead>'
									tabela += '<tr>';
									tabela += '<th>Nome</th>';
									tabela += '<th>Cargo</th>';
									tabela += '<th>Telemóvel</th>';
									tabela += '<th>E-mail</th>';
									tabela += '<th>Observações</th>';
									tabela += '</tr>';
									tabela += '</thead>';
									tabela += '<tbody>'

									for(i =0;i<detContatos.length; i++){
										tabela += '<tr>';
										tabela += '<td>'+detContatos[i].nome+'</td>';
										tabela += '<td>'+detContatos[i].cargo+'</td>';
										tabela += '<td>'+detContatos[i].tlm+'</td>';
										tabela += '<td>'+detContatos[i].email+'</td>';

										if(detContatos[i].obs == null )
											tabela += '<td>  </td>';
										else
											tabela += '<td>'+detContatos[i].obs+'</td>';

										tabela += '</tr>';
									}

									tabela += '</tbody>'
									$('#tabelaContatos').html(tabela);
								} else {
									$('#divContactosfornecedor').hide();
								}
							});



							table = $('#tabelaDocumentosfornecedor').dataTable({

								bRetrieve : true,
								bDestroy : true,sPaginationType: \"full_numbers\",
								sDom: 'TC<\"clear\">lfrtip',
								oTableTools: {
									sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
								},
								sPaginationType: \"full_numbers\",
								bProcessing: true,
								sDom: 'TC<\"clear\">lfrtip',
								oTableTools: {
									sSwfPath: 'media/swf/copy_csv_xls_pdf.swf'
								},
								aoColumnDefs: [
									{ sClass: \"text-right\", aTargets: [ 2 ] }
								]
							});




							table = $('#tabelaDocumentosfornecedor').dataTable();
							oSettings = table.fnSettings();
							table.fnClearTable(this);
							$.getJSON('modules/compras/comprasFornecedor.php', {'tag': 'cab', 'nrfr':nrfornecedor} ,function(documentos){

								if(documentos.length > 0){
									$('#divDocumentosfornecedor').show();

									var inc = (documentos.length/100);
									for (var i=0; i<documentos.length; i++){
										var obj = new Array();
										var tempDocInfo = documentos[i].nroficial+' / '+documentos[i].serie+' / '+documentos[i].tipodoc;
										obj[0] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+tempDocInfo+'\')\">'+documentos[i].tipodoc+ ' / '+documentos[i].serie+ ' / '+documentos[i].nroficial+'</a>';
										obj[1] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+tempDocInfo+'\')\">'+documentos[i].datadoc+'</a>';
										obj[2] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+tempDocInfo+'\')\">'+$.number(documentos[i].total,2,',','.')+' '+documentos[i].codmoeda+'</a>';
										table.oApi._fnAddData(oSettings, obj);
									}
									oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
									table.fnDraw();

									table.fnSort([[0,'desc']]);
									table.fnSort([[1,'desc']]);
								} else {
									$('#divDocumentosfornecedor').hide();
								}
							});
						} else {
							$('#divData').html('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Deve selecionar primeiro um fornecedor!</div>');
							$('#divData').show();
							$('#detalhes').hide();
							$('#divContactosfornecedor').hide();
							$('#divDocumentosfornecedor').hide();
							$('#divLayoutCorrection').css('display','block');
						}
					});
				} else {
					$('#divData').html('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Deve selecionar primeiro um fornecedor!</div>');
					$('#divData').show();
					$('#detalhes').hide();
					$('#divContactosfornecedor').hide();
					$('#divDocumentosfornecedor').hide();
					$('#divLayoutCorrection').css('display','block');
				}
			} else {
				$('#divData').html('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Deve selecionar primeiro um fornecedor!</div>');
				$('#divData').show();
				$('#detalhes').hide();
				$('#divContactosfornecedor').hide();
				$('#divDocumentosfornecedor').hide();
				$('#divLayoutCorrection').css('display','block');
			}
			$('#waitingBarColor').css('width', '100%' );
			$('#waitingBarColor').html('Completo');
		}

		var autocomplete = $('#typeahead').typeahead().on('keyup', function(ev){
			ev.stopPropagation();
			ev.preventDefault();
		});

		function preencheModal(infodoc){

			$('#infoTitulo').empty();
			$('#infoEmpresa').empty();
			$('#infofornecedor').empty();
			$('#infoDocumento').empty();

			$('#myModalDiv').css('display', 'block');


			var infoArray = infodoc.split('/');

			$.getJSON('modules/compras/detalhesCompra.php',{'nroficial': infoArray[0].trim(), 'serie': infoArray[1].trim(),'tipodoc': infoArray[2].trim()},function(infoDoc){

				$('#infoTitulo').html('<h2> Documento Nr. '+ infodoc +'</h2>');
				$('#infoEmpresa').html('".$_SESSION['nomeEmp']."');
				//$('#infoEmpresa').append('<p id=\"idvendedor\" class=\"pull-right\">Vendedor: '+infoDoc[0].vendedor+'</p>');
				$('#infofornecedor').html('<h3> Documento Nr. '+ infodoc +'</h3>');
				$('#infofornecedor').append('<p>Exmo(s). Sr(s). </p><p> '+infoDoc[0].fornecedor +'</p><p> '+infoDoc[0].morada+' </p>');

				var infoc = '<br><br><table id=\"detalhesDocumentoTBL\" class=\"table table-striped\" ><thead><tr><th>Artigos</th><th>Quantidade</th><th>Preço Unit.</th><th>IVA</th><th>Total</th></tr></thead><tbody>';

				for(i=0; i<infoDoc.length;i++){
					infoc += '<tr>';
					infoc += '<td> '+infoDoc[i].desart+'</td>';
					infoc += '<td style=\"text-align:right\"> '+$.number(infoDoc[i].qtd, 3,',','.')+'</td>';
					infoc += '<td style=\"text-align:right\"> '+$.number(infoDoc[i].pvn, 2,',','.')+'</td>';
					infoc += '<td style=\"text-align:right\"> '+$.number(infoDoc[i].taxaiva, 2,',','.')+'% </td>';
					infoc += '<td style=\"text-align:right\"> '+$.number(infoDoc[i].pvn*infoDoc[i].qtd, 2,',','.')+'</td>';
					infoc += '</tr>';
				}

				infoc += '</tbody>';
				infoc += '</table>';
				infoc += '<table class=\"pull-right\">';
				infoc += '<tr>';
				infoc += '<td> Total Ilíquido </td>';
				infoc += '<td> &nbsp </td>';
				infoc += '<td class=\"pull-right\">'+ $.number(infoDoc[0].totalmercil, 2,',','.')  +'</td>';
				infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
				infoc += '</tr>';

				infoc += '<tr>';
				infoc += '<td> Desconto </td>';
				infoc += '<td> &nbsp </td>';
				infoc += '<td class=\"pull-right\">'+ $.number(infoDoc[0].totaldescln, 2,',','.')  +'</td>';
				infoc += '<td >'+ infoDoc[0].codmoeda +'</td>';
				infoc += '</tr>';

				infoc += '<tr>';
				infoc += '<td> Total Líquido </td>';
				infoc += '<td> &nbsp </td>';
				infoc += '<td class=\"pull-right\">'+ $.number(infoDoc[0].totalmercli, 2,',','.') +'</td>';
				infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
				infoc += '</tr>';

				/*var sujeito = $.number((parseInt(infoDoc[0].iva01suj) + parseInt(infoDoc[0].iva02suj) + parseInt(infoDoc[0].iva03suj) + parseInt(infoDoc[0].iva04suj)), 2,',','.');

				infoc += '<tr>';
				infoc += '<td> Sujeito </td>';
				infoc += '<td> &nbsp </td>';
				infoc += '<td class=\"pull-right\">'+ sujeito  +'</td>';
				infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
				infoc += '</tr>';
				*/
				var valiva =  $.number(parseFloat(infoDoc[0].iva01val) + parseFloat(infoDoc[0].iva02val) + parseFloat(infoDoc[0].iva03val) + parseFloat(infoDoc[0].iva04val),2,',','.');

				infoc += '<tr>';
				infoc += '<td> Valor IVA </td>';
				infoc += '<td> &nbsp </td>';
				infoc += '<td class=\"pull-right\">'+ valiva  +'</td>';
				infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
				infoc += '</tr>';
				infoc += '</table>';

				$('#infoDocumento').html(infoc);

				var footerModalHtml = '<h2 class=\"pull-right\"> Total : '+$.number(infoDoc[0].totalcompra, 2,',','.')+' '+infoDoc[0].codmoeda+' </h2>';
				$('#footerModal').html(footerModalHtml);

			});

		}

		$('#typeahead').on('input', function() {
			if($('#typeahead').val().length >=1){
				var fornecedor = $('#typeahead').val();
				$.get('modules/fornecedores/listaFornecedores.php', {'fornecedor': fornecedor} ,function(infofornecedores){
					var obj = $.parseJSON(infofornecedores);
					$('#typeahead').data('typeahead').source = obj;
				});
			} else {
				$('#typeahead').data('typeahead').source = [];
			}
		});


		function printModal(){
			//var printDivCSS = '<link href=\"../../css/iframePrintStyle.css\" rel=\"stylesheet\" type=\"text/css\">';
			window.frames[\"print_frame\"].document.body.innerHTML =  document.getElementById(\"documentView\").innerHTML;
			window.frames[\"print_frame\"].window.focus();
			window.frames[\"print_frame\"].window.print();
		}

		function setListMode(){

			$('#detalhes').show();
			$('#divContactosfornecedor').show();
			$('#divDocumentosfornecedor').show();
			$('#divGraph').hide();
			$('#graphNoInfo').hide();

			procurar();

		}


		function setGraphMode(){

			$('#detalhes').hide();
			$('#divContactosfornecedor').hide();
			$('#divDocumentosfornecedor').hide();
			$('#divGraph').show();

			$('#divComprasFornecedor').hide();
			$('#seriesComprasFornecedor').hide();

			$('#divComprasMesAnualFornecedor').hide();
			$('#seriesComprasMesAno').hide();
			setGraphics();
		}


		function setGraphics(){

			$.getJSON('modules/fornecedores/outrasInfo.php',{'nrfornecedor':nrfornecedorGlobal, 'tag':'comprasFornecedor'},function(dataFr){

				if(dataFr.datasets[0].data.length > 0 ){
					$('#divComprasFornecedor').show();
					$('#seriesComprasFornecedor').show();
 					$('#ComprasFornecedor_Grafico').show();
 					$('#comprasForn_titulo').show();
 					$('#comprasFornAnual_titulo').show();

					var optionsPr = {
							datasetFill : true,
							scaleStartValue: 0,
							scaleLabel : \"<%= Number(value).toFixed(2).replace('.', ',') + ' €'%>\",
							graphTitle : \"Compras ao Fornecedor\",
							graphTitleFontSize: 18,
							showTooltips: true,
							legend : true,
							inGraphDataShow: true,
							inGraphDataTmpl: \"<%= v3 + ' €'%>\",
							annotateDisplay : true,
							datasetFill : true,
							annotateDisplay : true,
							annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
							xAxisLabel: \"(Últimas 10)\",
							responsive : true
					};

					//NECESSARIO PARA O GRAFICO FICAR RESPONSIVO
					var   ctx = $('#ComprasFornecedor_Grafico').get(0).getContext('2d');
					var width = $('#ComprasFornecedor_Grafico').parent().width();
					$('#ComprasFornecedor_Grafico').attr('width',width);

					new Chart(ctx).Bar(dataFr,optionsPr);

				} else {
					$('#divComprasFornecedor').hide();
					$('#seriesComprasFornecedor').hide();
					$('#graphNoInfo').show();
					$('#comprasForn_titulo').hide();
				}
			});

			$.getJSON('modules/fornecedores/outrasInfo.php',{'nrfornecedor':nrfornecedorGlobal, 'tag':'comprasMesAno'},function(dataFr){
				if(dataFr.datasets[0].data.length > 0 ){
					$('#divComprasMesAnualFornecedor').show();
					$('#seriesComprasMes').show();
 					$('#ComprasMesAno_Grafico').show();
					$('#seriesComprasMesAno').show();

					var max = 0;
					for(i = 0; i<dataFr.datasets.length;i++){
						var maxtemp = Math.max.apply(Math,dataFr.datasets[i].data);
						if(maxtemp > max)
							max = maxtemp;
					}
					var steps = 10;
					var stepSize = max / steps;

					var htmlToSeries = '<br><br>';
					for(i=0;i<dataFr.datasets.length;i++){
						htmlToSeries += '<h2 style=\"color:'+dataFr.datasets[i].strokeColor+';text-shadow: -1px 0 gray, 0 1px gray, 1px 0 gray, 0 -1px '+dataFr.datasets[i].fillcolor+';\">'+dataFr.datasets[i].label+'</h2>';
					}
					$('#seriesComprasMesAno').html(htmlToSeries);

					var optionsPr = {
							graphMin:0,
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
							graphTitle : \"Top Mensal Comprado / Ano\",
							graphTitleFontSize: 18,
							legend : true,
							annotateDisplay : true,
							annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
							inGraphDataShow : false,
							datasetFill : true,
							tooltipFillColor: 'rgba(0,0,0,0.8)',
							xAxisLabel: \"(Últimos 5 anos)\",
							responsive : true
					};

					//NECESSARIO PARA O GRAFICO FICAR RESPONSIVO
					var   ctx = $('#ComprasMesAno_Grafico').get(0).getContext('2d');
					var width = $('#ComprasMesAno_Grafico').parent().width();
					$('#ComprasMesAno_Grafico').attr('width',width);

					new Chart(ctx).Line(dataFr,optionsPr);


				} else {
					$('#divComprasMesAnualFornecedor').hide();
					$('#seriesComprasMes').hide();
					$('#graphNoInfo').show();
					$('#comprasFornAnual_titulo').hide();
				}
			});


		}

		</script>";

	//echo ($cabecalho.$content." ".$script);
	echo ($cabecalho." ".$script);

}


?>
