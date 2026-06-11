<?php


include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");
$content = '';
$contentOptions = '';
$yearOptions = '';
$monthOptions = '';

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
				<li><a href="#">Clientes</a></li>
			</ul>

			<div class="box span12">
				<div class="clearfix"></div>
				<div class="box-header" data-original-title="">
					<h2><i class="halflings-icon edit"></i><span class="break"></span>Clientes</h2>
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
											<input autocomplete="off" class="span6 typeahead " id="typeahead" placeholder="Pesquisar Cliente" data-provide="typeahead" data-items="4" data-source="[]" type="text" onClick="this.select();">
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


			<div id="waitingBar" class="progress progress-striped progress-success active" style="display:inherit">
				<div id="waitingBarColor" class="bar" style="width:0%;"></div>
			</div>

			<div id="divData"></div>


			<div id="divMenuInterior" class="pull-right" style="z-index: 999; position:relative">
				<table>
				<tr><td align="center">Info</td><td align="center">Gráficos</td></tr>
				<tr>
					<td><img onClick="setListMode()" title="Lista" src="img/list_icon.png" style="width: 70px; height: 70px;"></td>
					<td><img onClick="setGraphMode()" title="Gráficos" src="img/stats_icon.png" style="width: 70px; height: 70px;"></td>
				</tr>
				</table>
			</div>

			<br>
			<div id="detalhes">
				<div class="box-content">
					<form class="form-horizontal" id="formSearch">
						<fieldset>
							<dl>
								<dt style="color: #19B624;" id="nomeCliente"></dt>
							</dl>

							<div class="tooltip-demo">
								<p style="margin-bottom: 0;" class="muted" id="clienteDesde">
									Cliente '.$_SESSION["nomeEmp"].' desde 15/07/2004
								</p>
							</div>

							<div id="detailsProduct" >
							  <div class="box-content">
								<div class=" span12">
									<div class="span4">
										<div class="controls row-fluid">
											<dl>
												<dt> Morada </dt>
												<dd id="moradaCliente"></dd>
												<dt> Sublocalidade </dt>
												<dd id="sublocalidadeCliente"></dd>
												<dt> Localidade </dt>
												<dd id="codpostalCliente"></dd>
												<dd id="localidadeCliente"></dd>
												<dt> País </dt>
												<dd id="paisCliente"></dd>
											</dl>
										</div>
									</div>
									<div class="span4">
										<div class="controls row-fluid" >
											<dl>
												<dt> Nr. Contribuinte </dt>
												<dd id="contribuinteCliente"></dd>
												<dt> Telefone </dt>
												<dd id="telefoneCliente"></dd>
												<dt> Telemóvel </dt>
												<dd id="telemovelCliente"></dd>
												<dt> Fax </dt>
												<dd id="faxCliente"></dd>
												<dt> E-mail </dt>
												<dd id="emailCliente"></dd></dl>
											</dl>
										</div>
									</div>
									<div class="span4">
										<div class="controls row-fluid">
											<dl>
												<dt> Condição de Pagamento </dt>
												<dd id="condpagamento"></dd>
												<dt> Limite Crédito </dt>
												<dd id="limiteCreditoCliente"></dd>
												<dt> Valor Seguro </dt>
												<dd id="valorSeguroCliente"></dd>
												<dt> Vendedor Associado </dt>
												<dd id="vendAssoc"></dd>
											</dl>
										</div>
									</div>
								</div>
							  </div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>


			<div class="box span12" id="divContactosCliente" style="overflow:hidden; height:50%; width:100%">
				<div class="box-header green" data-original-title="" >
					<h2><i class="halflings-icon edit"></i><span class="break"></span> Contactos </h2>
				</div>
				<div class="span11 table-responsive" style="overflow: auto; height: 100%; width: 100%; padding-right: 15px;"  >
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

			<div class="box span12" id="divDocumentosCliente">
				<div class="box-header blue" data-original-title="">
					<h2><i class="halflings-icon edit"></i><span class="break"></span>Documentos</h2>
				</div>
				<div style="display: block;" class="box-content">
					<div class="table-responsive" >
						<!-- <h2>Documentos</h2> -->
						<table id="tabelaDocumentosCliente" class="display row-border order-column" cellspacing="0" width="100%">
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


			<div id="divTopClientes" class="span12">

				<div ondesktop="span10" ontablet="span8" class="box blue span6">
					<div class="box-header">
						<h2 style="color:#ffffff;"><i class="halflings-icon white user"></i><span class="break"></span> Top 5 - Clientes</h2>
						<select class="pull-right" style="height: 26px; width: 100px; margin-top:-5px;" id="ano"></select>
					</div>
					<div class="box-content">
						<ul class="dashboard-list metro white sa" id="liTopFive">

						</ul>
					</div>
				</div>

				<div ondesktop="span10" ontablet="span8" class="box green span6">
					<div class="box-header">
						<h2 style="color:#ffffff;"><i class="halflings-icon white user"></i><span class="break"></span> Top 5 - Novos Clientes</h2>
					</div>
					<div class="box-content">
						<ul class="dashboard-list metro white sa" id="liTopNovos">

						</ul>
					</div>
				</div>

			</div>


			<div id="divGraph" class="span11">
				    <div class="span12" id="divVendasCliente" >
					<h2 style="text-align:center;"> Vendas ao Cliente </h2>
					<canvas id="VENDASCLIENTE_Grafico" height="450px"></canvas>
				</div>
				 <div class="span11" id="divVendasMesAnualCliente" >
					<h2 style="text-align:center;"> Total Mensal por Ano Vendido ao Cliente </h2>
					<canvas id="VENDASMESANOCLIENTE_Grafico" height="450px"></canvas>
				</div>
				<div id="seriesVENDASMESANOCLIENTE" class="pull-right" ></div>
			</div>


			<div id="divLayoutCorrection" style="display: block">
				<img src="img/barra.png"/>
			</div>

			<div id="graphNoInfo" class="span12">
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
							<!-- <button id="printButton" type="button" class="btn btn-primary"  onClick="printModal()"> Imprimir </button> -->
						</div>
				  </div>
				</div>
			  </div>
			</div>




			<iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>



			';

	$script="<script>




		$(document).ready(function () {
			$('#graphNoInfo').hide();
			$('#detalhes').hide();
			$('#divData').hide();
			$('#divContactosCliente').hide();
			$('#divDocumentosCliente').hide();
			$('#divMenuInterior').hide();
			$('#divGraph').hide();

			$('#waitingBar').hide();

			oTable = $('#tabelaDocumentosCliente').dataTable({
					bRetrieve : true,
					bDestroy : true,
					sPaginationType: \"full_numbers\",
					bProcessing: true,
					sDom: 'TC<\"clear\">lfrtip',
					oTableTools: {
						sSwfPath: 'media/swf/copy_csv_xls_pdf.swf'
					},
					aoColumnDefs: [
                        { sClass: \"text-right\", aTargets: [ 2 ] }
					],
					\"fnRowCallback\": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
						if(aData[3] =='A'){
							$(nRow).addClass('alert alert-error');
						}
					}
			});

			$(\"#tabelaDocumentosCliente\").tabs( {
				\"active\": function(event, ui) {
					var jqTable = $('table.display', ui.panel);
					if ( jqTable.length > 0 ) {
							jqTable.dataTable().fnAdjustColumnSizing();
					  }
				   }
			});


			$.getJSON('modules/clientes/outrasInfoClientes.php', {'mode': 'topfive', 'desde':'sempre'} ,function(topfive){
				if(topfive.length>0){
					$('#divLayoutCorrection').css('display','none');
					var list = '';
					for(i=0; i<topfive.length; i++){
						var clientSearch = topfive[i].nome+' | '+topfive[i].nrcl;
						list += '<li class=\"text-info\" onClick=\"getThisClient(\''+clientSearch+'\')\">';
						list += '<a>';
						list += '<img src=\"img/user_metro2.png\" class=\"avatar\">';
						list += '</a>';
						list += '<strong>Nr. Cliente: &nbsp </strong>'+topfive[i].nrcl+'<br>';
						list += '<strong>Nome: &nbsp'+topfive[i].nome+'</strong><br>';
						list += '<strong>Montante: &nbsp</strong>'+$.number(topfive[i].montante, 2,',','.')+' '+topfive[i].moeda+'<br>';
						list += '</li>';
					}
					$('#liTopFive').html(list);
				}
				else {
					$('#divTopClientes').hide();
				}
			});

			$.getJSON('modules/clientes/outrasInfoClientes.php', {'mode': 'topNovosClientes'} ,function(topNovos){
				if(topNovos.length>0){
					var list = '';
					for(i=0; i<topNovos.length; i++){
						var clientSearch = topNovos[i].nome+' | '+topNovos[i].nrcl;
						list += '<li class=\"text-success\" onClick=\"getThisClient(\''+clientSearch+'\')\">';
						list += '<a>';
						list += '<img src=\"img/new_user.png\" class=\"avatar\">';
						list += '</a>';
						list += '<strong>Nr. Cliente: &nbsp </strong>'+topNovos[i].nrcl+'<br>';
						list += '<strong>Nome: &nbsp'+topNovos[i].nome+'</strong><br>';
						list += '<strong>Data Registo: &nbsp</strong>'+topNovos[i].datareg+'<br>';
						list += '</li>';
					}
					$('#liTopNovos').html(list);
				}
				else {
					$('#divTopClientes').hide();
				}
			});


			//preencher o selector no top 5 para escpçher os clientes
			$.getJSON('modules/clientes/outrasInfo.php', {'tag': 'anoconst'} ,function(anoconstdata){
				var anoconstituicao = parseInt(anoconstdata[0].anoconst);
				var year = new Date().getFullYear();

				document.getElementById('ano').add(new Option('Sempre', 'sempre'));
				do{
					document.getElementById('ano').add(new Option(year,year));
					year --;
				}while(anoconstituicao <= year);

			});




		});


		//  TOP5CLIENTES
		//evento para quando trocar o ano verificar o top 5 clientes
		$('#ano').change(function() {
			var anoPesquisa = $('#ano').val();
			$.getJSON('modules/clientes/outrasInfoClientes.php', {'mode': 'topfive', 'desde':anoPesquisa} ,function(topfive){
				if(topfive.length>0){
					$('#divLayoutCorrection').css('display','none');
					var list = '';
					for(i=0; i<topfive.length; i++){
						var clientSearch = topfive[i].nome+' | '+topfive[i].nrcl;
						list += '<li class=\"text-info\" onClick=\"getThisClient(\''+clientSearch+'\')\">';
						list += '<a>';
						list += '<img src=\"img/user_metro2.png\" class=\"avatar\">';
						list += '</a>';
						list += '<strong>Nr. Cliente: &nbsp </strong>'+topfive[i].nrcl+'<br>';
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



		function getThisClient(client){
			$('#typeahead').val(client);
			limparProgressBar();
			procurar();
		}


		function limparProgressBar(){
			$('#waitingBarColor').css('width', '0%' );
			$('#waitingBarColor').html('');
		}

		var nrclGlobal = 0;

		function procurar(){
			$('#waitingBar').show();
			$('#divLayoutCorrection').css('display','none');

			$('#divTopClientes').hide();

			//$('#divContactosCliente').html('');
			//$('#divDocumentosCliente').html('');

			$('#waitingBar').show();

			$('#divGraph').hide();
			$('#graphNoInfo').hide();


			$('#VENDASCLIENTE_Grafico').remove(); // this is my <canvas> element
			$('#divVendasCliente').html('<h2 style=\"text-align:center;\">  </h2><canvas id=\"VENDASCLIENTE_Grafico\" height=\"450px\" width=\"110%\"><canvas>');
			$('#VENDASMESANOCLIENTE_Grafico').remove(); // this is my <canvas> element
			$('#seriesVENDASMESANOCLIENTE').remove()
			$('#divVendasMesAnualCliente').html('<h2 style=\"text-align:center;\"> <br><br> </h2><canvas id=\"VENDASMESANOCLIENTE_Grafico\" height=\"450px\"><canvas>');
			$('#divGraph').append('<div id=\"seriesVENDASMESANOCLIENTE\"  class=\"pull-right\"></div>');

			if($('#typeahead').val().length > 0){
				if($('#typeahead').val().split('|').length == 2){
					var dadoscliente =  $('#typeahead').val();
					var datcli = dadoscliente.split('|');
					var nomecliente = datcli[0].trim();
					var nrcliente 	= datcli[1].trim();

					nrclGlobal = nrcliente;

					$.getJSON('modules/clientes/detalhesCliente.php', {'cliente': nrcliente, 'mode': 'det'} ,function(detCli){

						if(detCli.length >0){
							$('#divMenuInterior').show();
							$('#divVendasCliente').show();

							$('#detalhes').show();
							$('#divData').hide();
							$('#nomeCliente').html('<h1><span style=\"font-size : 22px; font-weight: bold;\">'+detCli[0].nrcl+'</span>  '+detCli[0].nome+' </h1>');
							var formatData = detCli[0].data_reg.split('-')[2] + '/' + detCli[0].data_reg.split('-')[1] +'/'+ detCli[0].data_reg.split('-')[0];
							$('#clienteDesde').html('Cliente ".$_SESSION["nomeEmp"]." desde '+ formatData);
							$('#moradaCliente').html(detCli[0].mor);
							$('#sublocalidadeCliente').html(detCli[0].cp+' - '+detCli[0].sub);
							$('#localidadeCliente').html(detCli[0].loc);
							$('#paisCliente').html(detCli[0].pais);
							$('#contribuinteCliente').html(detCli[0].contribuinte);
							$('#telefoneCliente').html(detCli[0].tel);
							$('#telemovelCliente').html(detCli[0].tlm);
							$('#faxCliente').html(detCli[0].fax);
							$('#emailCliente').html(detCli[0].email);
							$('#condpagamento').html(detCli[0].condpag);
							$('#limiteCreditoCliente').html($.number(detCli[0].limite_credito, 2, ',','.')+' '+detCli[0].codmoeda);
							$('#valorSeguroCliente').html($.number(detCli[0].valseguro,2,',','.')+' '+detCli[0].codmoeda);

							$.getJSON('modules/clientes/detalhesCliente.php', {'cliente': nrcliente, 'mode': 'vendassoc'} ,function(vendaso){
								$('#vendAssoc').html(vendaso[0].vendassoc);
							});

							$.getJSON('modules/clientes/outrasInfo.php', {'tag': 'pais', 'pais':detCli[0].pais} ,function(detPais){
								$('#paisCliente').append(' - '+detPais[0].pais);
							});

							$.getJSON('modules/clientes/outrasInfo.php', {'tag': 'contatos', 'nrcl':nrcliente} ,function(detContatos){

								if(detContatos.length > 0){
									$('#divContactosCliente').show();

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
									$('#divContactosCliente').hide();
								}
							});

							table = $('#tabelaDocumentosCliente').dataTable({
									bRetrieve : true,
									bDestroy : true,
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

							table = $('#tabelaDocumentosCliente').dataTable();
							oSettings = table.fnSettings();
							table.fnClearTable(this);



							$.getJSON('modules/vendas/vendasCliente.php', {'tag': 'cab', 'nrcl':nrcliente} ,function(documentos){

								if(documentos.length > 0){
									$('#divDocumentosCliente').show();

									var inc = (documentos.length/100);
									for (var i=0; i<documentos.length; i++){
										var obj = new Array();
										var tempDocInfo = documentos[i].nroficial+' / '+documentos[i].serie+' / '+documentos[i].tipodoc;
										obj[0] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+tempDocInfo+'\')\">'+documentos[i].tipodoc+ ' / ' + documentos[i].serie+ ' / ' + documentos[i].nroficial+'</a>';

										obj[1] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+tempDocInfo+'\')\">'+documentos[i].datadoc+'</a>';
										obj[2] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+tempDocInfo+'\')\">'+$.number(documentos[i].totalsiva,2,',','.')+' '+documentos[i].codmoeda+'</a>';
										obj[3] = documentos[i].situacao;
										table.oApi._fnAddData(oSettings, obj);

									}
									oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
									table.fnDraw();

									table.fnSort([[0,'desc']]);
									table.fnSort([[1,'desc']]);
								} else {
									$('#divDocumentosCliente').hide();
								}
							});
						} else {
							$('#divData').html('<br><div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Deve selecionar primeiro um Cliente!</div>');
							$('#divData').show();
							$('#detalhes').hide();
							$('#divContactosCliente').hide();
							$('#divDocumentosCliente').hide();
							$('#divLayoutCorrection').css('display','block');
							$('#divMenuInterior').hide();
							$('#divGraph').hide();
						}
					});
				} else {
					$('#divData').html('<br><div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Deve selecionar primeiro um Cliente!</div>');
					$('#divData').show();
					$('#detalhes').hide();
					$('#divContactosCliente').hide();
					$('#divDocumentosCliente').hide();
					$('#divLayoutCorrection').css('display','block');
					$('#divMenuInterior').hide();
					$('#divGraph').hide();
				}
			} else {
				$('#divData').html('<br><div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Deve selecionar primeiro um Cliente!</div>');
				$('#divData').show();
				$('#detalhes').hide();
				$('#divContactosCliente').hide();
				$('#divDocumentosCliente').hide();
				$('#divLayoutCorrection').css('display','block');
				$('#divMenuInterior').hide();
				$('#divGraph').hide();
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
			$('#infoCliente').empty();
			$('#infoDocumento').empty();

			$('#myModalDiv').css('display', 'block');


			var infoArray = infodoc.split('/');

			$.getJSON('modules/vendas/detalhesVenda.php',{'nroficial': infoArray[0].trim(), 'serie': infoArray[1].trim(),'tipodoc': infoArray[2].trim()},function(infoDoc){

				if(infoDoc[0].situacao == 'A')
					$('#infoTitulo').html('<h2 style=\"color:red;\"> Documento Nr. '+ infodoc +' (ANULADO)</h2>');
				else
					$('#infoTitulo').html('<h2> Documento Nr. '+ infodoc +'</h2>');


				$('#infoEmpresa').html('".$_SESSION['nomeEmp']."');
				$('#infoEmpresa').append('<p id=\"idvendedor\" class=\"pull-right\">Vendedor: '+infoDoc[0].vendedor+'</p>');
				$('#infoCliente').html('<h3> Documento Nr. '+ infodoc +'</h3>');
				$('#infoCliente').append('<p>Exmo(s). Sr(s). </p><p> '+infoDoc[0].cliente +'</p><p> '+infoDoc[0].morada+' </p><p> '+infoDoc[0].email +'</p>');

				var infoc = '<br><br><table id=\"detalhesDocumentoTBL\" class=\"table table-striped\" ><thead><tr><th>Artigos</th><th>Quantidade</th><th>Preço Unit.</th><th>Desconto</th><th>IVA</th><th>Total</th></tr></thead><tbody>';

				for(i=0; i<infoDoc.length;i++){
					infoc += '<tr>';
					infoc += '<td> '+infoDoc[i].desart+'</td>';
					infoc += '<td style=\"text-align:right\"> '+$.number(infoDoc[i].qtd, 3,',','.')+'</td>';
					infoc += '<td style=\"text-align:right\"> '+$.number(infoDoc[i].pvn, 2,',','.')+'</td>';

					if(infoDoc[i].desclinha1!='' || infoDoc[i].desclinha1!='0'){
						infoc += '<td style=\"text-align:right\"> '+$.number(infoDoc[i].desclinha1, 2,',','.')+'% </td>';
					}else if (infoDoc[i].desclinha2!='' || infoDoc[i].desclinha2!='0'){
						infoc += '<td style=\"text-align:right\"> '+$.number(infoDoc[i].desclinha2, 2,',','.')+'% </td>';
					} else if (infoDoc[i].desclinha3!='' || infoDoc[i].desclinha3!='0'){
						infoc += '<td style=\"text-align:right\"> '+$.number(infoDoc[i].desclinha3, 2,',','.')+'% </td>';
					}
					else{
						infoc += '<td style=\"text-align:right\"> 0,00% </td>';
					}



					infoc += '<td style=\"text-align:right\"> '+$.number(infoDoc[i].taxa, 2,',','.')+'% </td>';

					infoc += '<td style=\"text-align:right\"> '+$.number(infoDoc[i].pvn*infoDoc[i].qtd, 2,',','.')+'</td>';
					infoc += '</tr>';
				}

				infoc += '</tbody>';
				infoc += '</table>';
				infoc += '<table class=\"pull-right\">';
				infoc += '<tr>';
				infoc += '<td> Total Ilíquido </td>';
				infoc += '<td> &nbsp </td>';
				infoc += '<td class=\"pull-right\">'+ $.number(infoDoc[0].totalmercil, 2,',','.') +'</td>';
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
				infoc += '<td class=\"pull-right\">'+ $.number(infoDoc[0].totalmercli, 2,',','.')  +'</td>';
				infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
				infoc += '</tr>';

				/*
				var sujeito = $.number((parseFloat(infoDoc[0].iva01suj) + parseFloat(infoDoc[0].iva02suj) + parseFloat(infoDoc[0].iva03suj) + parseFloat(infoDoc[0].iva04suj)), 2,',','.');

				infoc += '<tr>';
				infoc += '<td> Sujeito </td>';
				infoc += '<td> &nbsp </td>';
				infoc += '<td class=\"pull-right\">'+ sujeito  +'</td>';
				infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
				infoc += '</tr>';
				*/

				var valiva = $.number((parseFloat(infoDoc[0].iva01val) + parseFloat(infoDoc[0].iva02val) + parseFloat(infoDoc[0].iva03val) + parseFloat(infoDoc[0].iva04val)), 2,',','.');

				infoc += '<tr>';
				infoc += '<td> Valor IVA </td>';
				infoc += '<td> &nbsp </td>';
				infoc += '<td class=\"pull-right\">'+ valiva +'</td>';
				infoc += '<td>'+ infoDoc[0].codmoeda +'</td>';
				infoc += '</tr>';
				infoc += '</table>';

				$('#infoDocumento').html(infoc);

				var footerModalHtml = '<h2 class=\"pull-right\"> Total : '+$.number(infoDoc[0].total, 2,',','.')+' '+infoDoc[0].codmoeda+' </h2>';
				$('#footerModal').html(footerModalHtml);

			});

		}

		$('#typeahead').on('input', function() {
			if($('#typeahead').val().length >=1){
				var cliente = $('#typeahead').val();
				$.get('modules/clientes/listaClientes.php', {'cliente': cliente} ,function(infoClientes){
					var obj = $.parseJSON(infoClientes);
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
			$('#divContactosCliente').show();
			$('#divDocumentosCliente').show();

			procurar();

			$('#divGraph').hide();
		}


		function setGraphMode(){
			$('#detalhes').hide();
			$('#divContactosCliente').hide();
			$('#divDocumentosCliente').hide();

			$('#divGraph').show();

			setGraphics();
		}

		function setGraphics(){

			$.getJSON('modules/clientes/outrasInfoClientes.php',{'nrcl':nrclGlobal, 'mode':'vendasCliente'},function(dataVd){
				if(dataVd.datasets[0].data.length > 0 ){


					var optionsPr = {

							datasetFill : true,
							scaleStartValue: 0,
							scaleLabel : \"<%= Number(value).toFixed(2).replace('.', ',') + ' €'%>\",
							graphTitle : \"Volume Vendas Individual\",
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

					var ctx = $('#VENDASCLIENTE_Grafico').get(0).getContext('2d');
					var width = $('#VENDASCLIENTE_Grafico').parent().width();
					$('#VENDASCLIENTE_Grafico').attr('width',width);
					new Chart(ctx).Bar(dataVd,optionsPr);
					$('#VENDASCLIENTE_Grafico').show();
				} else {
					$('#divVendasCliente').hide();
					$('#graphNoInfo').show();
				}
			});

			$.getJSON('modules/clientes/outrasInfoClientes.php',{'nrcl':nrclGlobal, 'mode':'vendasMensaisCliente'},function(dataVd){
				if(dataVd.datasets.length > 0 ){
					$('#VENDASMESANOCLIENTE_Grafico').show();
					$('#divVendasMesAnualCliente').show();


					var max = 0;
					for(i = 0; i<dataVd.datasets.length;i++){
						var maxtemp = Math.max.apply(Math,dataVd.datasets[i].data);
						if(maxtemp > max)
							max = maxtemp;
					}
					var steps = 10;
					var stepSize = max / steps;

					var htmlToSeries = '<br><br>';
					for(i=0;i<dataVd.datasets.length;i++){
						htmlToSeries += '<h2 style=\"color:'+dataVd.datasets[i].strokeColor+';text-shadow: -1px 0 gray, 0 1px gray, 1px 0 gray, 0 -1px '+dataVd.datasets[i].fillcolor+';\">'+dataVd.datasets[i].label+'</h2>';
					}

					$('#seriesVENDASMESANOCLIENTE').html(htmlToSeries);

					var optionsPr = {
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
							scaleStartValue: 0,
							scaleLabel : \"<%= Number(value).toFixed(2).replace('.', ',') + ' €'%>\",
							graphTitle : \"Volume Vendas Mês/Ano\",
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
					var ctx = $('#VENDASMESANOCLIENTE_Grafico').get(0).getContext('2d');
					var width = $('#VENDASMESANOCLIENTE_Grafico').parent().width();
					$('#VENDASMESANOCLIENTE_Grafico').attr('width',width);
					new Chart(ctx).Line(dataVd,optionsPr);

				} else {
					//$('#divVendasMesAnualCliente').hide();
					$('#divVendasMesAnualCliente').hide();
					$('#graphNoInfo').show();
				}
			});



		}

		</script>";

	echo ($cabecalho.$content." ".$script);

}


?>
