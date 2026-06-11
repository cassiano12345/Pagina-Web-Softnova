<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");
$content = "";
$contentOptions ='';

/*******************************************************************************************************************************************************
****************************************************************** PAGINA ******************************************************************************
********************************************************************************************************************************************************

	1º --> HTML
	2ª --> ALGUMAS VARIAVEIS
	3º --> ARRANQUE JAVASCRIPT
	4ª --> EXTRAS (MODAL, DIAS, ETC)
	5º --> TABELAS DIARIAS (ARTIGO, LOJA, VENDEDOR)
	6ª --> TABELAS MENSAIS (ARTIGO, LOJA, VENDEDOR)
	7º --> GRAFICOS

*******************************************************************************************************************************************************
*******************************************************************************************************************************************************/


if (!isset($_SESSION['user'])){
	echo "";
	header("Location: ../../index.php");
}
else{

	try{
		$rs = $db->execute('Select dl.id, dl.Nome from dash_lojas dl, dash_acessos am where dl.codemp   = '.$_SESSION["codemp"].' and am.codemp   = dl.codemp and am.idloja   = dl.id and am.idgrupo  = '.$_SESSION["grupo"].'');
		if($rs){
			While($row = $rs->FetchRow()){
				$contentOptions .= '<option value='.$row[0].'>'.$row[1].'</option>';
			}
		}
	} catch(Exception $e) {
		echo $e;
	}
	
	
	$op 		= $_GET["op"];
	//$id_loja 	= $_GET["id_loja"];$id_loja 	= "";
	if (isset($_GET["id_loja"])){
		$id_loja = $_GET["id_loja"];
	}
	else{
		$id_loja = "";
	}
	
	
	$loja = 0;

	if ($id_loja != ""){
		$loja = 1;
		$htmlButtonVoltar = '<button class="btn btn-info icon-arrow-left" id="imagemV" onClick="voltar()"> Voltar </button>';
	} else {
		$htmlButtonVoltar = '';
	}
	
	$op1 = 1;
	if ($op == 2){
		$op1 = 1;
	}


	if($op == 1){
		$content = '
			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.php">Home</a> 
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="#">Vendas</a></li>
			</ul>
			<div id="contentMain">		
				<div class="box span12">
					<div class="box-header" data-original-title="">
						<h2><i class="halflings-icon edit"></i><span class="break"></span>Vendas - Pesquisa</h2>
					</div>
					
					<div style="display: block;" class="box-content">
						<div clas="span7">
							<nav role="navigation" class="navbar navbar-default ">
								<div>
									<ul class="nav navbar-nav">											
										<li id="ArtigosBtn"  class="active">
											<a onclick="setActiveNav(0)" style="cursor:pointer">Artigo</a>
										</li>
										<li id="LojaBtn"  class="">
											<a onclick="setActiveNav(1)" style="cursor:pointer">Armazém</a>
										</li>
										<li id="VendedorBtn"  class="">
											<a onclick="setActiveNav(2)" style="cursor:pointer">Vendedor</a>
										</li>												
									</ul>
								</div>
							</nav>
						</div>
						<div class="clearfix"></div>					
						<br>
						<div class="span7">
							<form class="form-horizontal" id="formSearch">
								<fieldset>
									<div class="control-group" id="pesquisas">
										<label class="control-label" for="loja">Armazém</label>
										<div class="controls">
											<select id="loja" >
												<option value="todos">Todos</option>
												'.$contentOptions.'
											</select>
										</div>
									</div>
										
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
											<label class="control-label" for="typeahead"> Artigo </label>
											<div class="controls">
												<input autocomplete="off" class="span6 typeahead " style="width:100%;" placeholder="Todos" id="typeahead" type="text" >
											</div>							
										</div>
										<div class="control-group">
											<div class="controls">
												<button type="submit" class="btn btn-success" onclick="procurarArtigo()" > Pesquisar </button>
											</div>
										</div>
									</div>
						
									<div id="ProcurarLoja" style="display: none;">	
									
										<div class="control-group">
											<label class="control-label" for="dialoja">Data Inicial</label>
											<div class="controls">
													<input class="span12" style="width:50%;" id="dialoja" type="text">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="dialojaFim">Data Final</label>
											<div class="controls">
													<input class="span12" style="width:50%;" id="dialojaFim" type="text">
											</div>
											<br>
											<br>
											<div class="controls">
												<button type="submit" class="btn btn-success" onclick="procurarLoja()" > Pesquisar </button>	
											</div>
										</div>
									</div>
									
							
									<div id="ProcurarVendedor" style="display: none;">
										
											<div class="control-group">
												<label class="control-label" for="datainicial2">Data Inicial</label>
												<div class="controls">
													<input class="span12" style="width:50%;" id="datainicial2" type="text">
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="datafinal2">Data Final</label>
													<div class="controls">
														<input class="span12" style="width:50%;" id="datafinal2" type="text">
													</div>
											</div>
											
											<div class="control-group">
												<label class="control-label" for="typeaheadVendedor"> Vendedor </label>
												<div class="controls">
													<input autocomplete="off" class="span12 typeahead " style="width:100%;" id="typeaheadVendedor" placeholder="Todos" data-provide="typeahead" data-items="4" data-source="[]" type="text" onClick="this.select();">
												</div>
											</div>
											
											
											<div class="controls">
												<button type="submit" class="btn btn-success" onclick="procurarVendedor()" > Pesquisar </button>
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
		</div>	';
		
		$content .= $htmlButtonVoltar .'
		
		<div id="divImagens"  class="pull-right" style="z-index: 999; position:relative">
			<table>
				<tr>  
					<td align="center">Info</td>
					<td align="center">Gráficos</td>
				</tr>
				<tr>
					<td><img onclick="procurarArtigo()" 		title="Lista" 		src="img/list_icon.png"  style="width: 70px; height: 70px;"></td>
					<td><img onclick="graficoArtigoDiarias()" 	title="Gráficos" 	src="img/stats_icon.png" style="width: 70px; height: 70px;"></td>
				</tr>
			</table>
		</div>	

		
		<div id="divMenuLoja"  class="pull-right" style="z-index: 999; position:relative">
			<table>
				<tr>
					<td align="center">Info</td>
					<td align="center">Gráficos</td>
				</tr>
				<tr>
					<td><img onclick="procurarLoja()" 		title = "Lista" 	src="img/list_icon.png"  style="width: 70px; height: 70px;"></td>
					<td><img onclick="graficoLojaDiario()" title = "Gráficos"  src="img/stats_icon.png" style="width: 70px; height: 70px;"></td>
				</tr>
			</table>
		</div>	
		
		<div id="divMenuInterior"  class="pull-right" style="z-index: 999; position:relative">
			<table>
				<tr>
					<td align="center">Info</td>
					<td align="center">Gráficos</td>
				</tr>
				<tr>
					<td><img onclick="procurarVendedor()" 		title = "Lista" 	src="img/list_icon.png"  style="width: 70px; height: 70px;"></td>
					<td><img onclick="graficoVendedorDiarias()" title = "Gráficos"  src="img/stats_icon.png" style="width: 70px; height: 70px;"></td>
				</tr>
			</table>
		</div>	
		
		
		<br>
		<br><div id="totaisDiv"> </div>
		<br>
		<br> ';
		
		
	} else	{

	
		$content = '
			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.php">Home</a> 
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="#">Vendas</a></li>
			</ul>
			<div id="contentMain">		
				<div class="box span12">
					<div class="box-header" data-original-title="">
						<h2><i class="halflings-icon edit"></i><span class="break"></span> Vendas Mensais - Pesquisa</h2>
					</div>
					
					<div style="display: block;" class="box-content">
						<div clas="span7">
							<nav role="navigation" class="navbar navbar-default ">
								<div>
									<ul class="nav navbar-nav">											
										<li id="ArtigosBtn"  class="active">
											<a onclick="setActiveNav(0)" style="cursor:pointer">Artigo</a>
										</li>
										<li id="LojaBtn"  class="">
											<a onclick="setActiveNav(1)" style="cursor:pointer">Armazém</a>
										</li>
										<li id="VendedorBtn"  class="">
											<a onclick="setActiveNav(2)" style="cursor:pointer">Vendedor</a>
										</li>												
									</ul>
								</div>
							</nav>
						</div>
						<div class="clearfix"></div>					
						<br>
						<div class="span7">
							<form class="form-horizontal" id="formSearch">
								<fieldset>
									<div class="control-group" id="pesquisas">
										<label class="control-label" for="loja">Armazém</label>
										<div class="controls">
											<select id="loja">
												<option value="todos">Todos</option>
												'.$contentOptions.'
											</select>
										</div>
									</div>
										
									
									<div class="control-group">
										<label class="control-label" for="mesProcura">Mês</label>
										<div class="controls">
											<input class="span12" style="width:50%;" placeholder="Selecione um Mês" id="mesProcura" type="text">
										</div>
									</div>
								
									<div id="ProcurarArtigo">
										<!--<div class="control-group">
											<label class="control-label" for="procuraArtigo"> Artigo </label>
											<div class="controls">
												<input autocomplete="off" class="input-large" placeholder="Nome do Artigo" id="procuraArtigo" type="text" >
											</div>							
										</div>-->
										
										<div class="control-group">
											<label class="control-label" for="typeahead"> Artigo </label>
											<div class="controls">
												<input autocomplete="off" class="span6 typeahead " style="width:100%;" placeholder="Todos" id="typeahead" type="text" >
											</div>							
										</div>
										
										<div class="control-group">
											<div class="controls">
												<button type="submit" class="btn btn-success" onclick="procurarArtigoMes()" > Pesquisar </button>
											</div>
										</div>
									</div>
						
									<div id="ProcurarLoja" style="display: none;">	
										<div class="control-group">
											<div class="controls">
												<button type="submit" class="btn btn-success" onclick="procurarLojaMes()" > Pesquisar </button>	
											</div>
										</div>
									</div>
							
									<div id="ProcurarVendedor" style="display: none;">
										
										<div class="control-group">
											<label class="control-label" for="typeaheadVendedor"> Vendedor </label>
											<div class="controls">
												<input autocomplete="off" class="span12 typeahead " style="width:100%;" id="typeaheadVendedor" placeholder="Todos" data-provide="typeahead" data-items="4" data-source="[]" type="text" onClick="this.select();">
											</div>
										</div>
											
										<div class="control-group">
											<div class="controls">
												<button type="submit" class="btn btn-success" onclick="procurarVendedorMes()" > Pesquisar </button>
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
		
		$content .= $htmlButtonVoltar .'
		
		<div id="divImagensMensais1"  class="pull-right" style="z-index: 999; position:relative">
			<table>
				<tr>
					<td align="center"> Info </td>  
					<td align="center"> Gráficos </td>
				</tr>
				<tr>
					<td><img  onclick="procurarArtigoMes()" title="Lista" src="img/list_icon.png" style="width: 70px; height: 70px;"></td>
					<td><img onclick="graficoArtigoMensais()" title="Gráficos" src="img/stats_icon.png" style="width: 70px; height: 70px;"></td>
				</tr>
			</table>
		</div>	
		
		<div id="divSubMenuLoja"  class="pull-right" style="z-index: 999; position:relative">
			<table>
				<tr>
					<td align="center"> Info </td>  
					<td align="center"> Gráficos </td>
				</tr>
				<tr>
					<td><img  onclick="procurarLojaMes()" title="Lista" src="img/list_icon.png" style="width: 70px; height: 70px;"></td>
					<td><img onclick="graficoLojaMensais()" title="Gráficos" src="img/stats_icon.png" style="width: 70px; height: 70px;"></td>
				</tr>
			</table>
		</div>	
		
		<div id="divImagensMensais"  class="pull-right" style="z-index: 999; position:relative">
			<table>
				<tr>
					<td align="center">Info</td> 
					<td align="center">Gráficos</td>
				</tr>
				<tr>
					<td><img  onclick="procurarVendedorMes()" title="Lista" src="img/list_icon.png" style="width: 70px; height: 70px;"></td>
					<td><img onclick="graficoVendedorMensais()" title="Gráficos" src="img/stats_icon.png" style="width: 70px; height: 70px;"></td>
				</tr>
			</table>
		</div>
		
		<br>
		<br>
		
		<div id="totaisDiv"> </div>	
		<br>
		<br>
		
		<div class="span11" id="divArtigosMensais">
			<canvas id="graficoVendasArtigoMensais" height="350px" width="950px"></canvas>
			<div class="span4" id="seriesGraficoVendasArtigoMensais"></div>
		</div>
		
		<div class="span11" id="divMapa1">
			<canvas id="graficoVendasPorVendedor1" height="350px" width="350px"></canvas>
			<div class="span4 pull-right" id="seriesGraficoVendasPorVendedor1"></div>
		</div>
		
		
		<div class="span11" id="divMensaisLoja">
			<canvas id="graficoMensaisLoja" height="350px" width="950px"></canvas>
			<div class="span4 pull-right" id="seriesGraficoMensaisLoja"></div>
		</div>
		
		';
	}
		
	$content .='
			<div id="dataArtigos">
				<table id="tabelaArtigos" class="display row-border order-column" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Código</th>
							<th>Nome</th>
							<th>Qtd</th>
							<th>Preço</th>
							<th>Data</th>
							<th>Documento </th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Código</th>
							<th>Nome</th>
							<th>Qtd</th>
							<th>Preço</th>
							<th>Data</th>
							<th>Documento</th>	
						</tr>
					</tfoot>
				</table>
			</div>
			
			
			
			<div class="span11" id="divGrafico">
				<canvas id="graficoVendasArtigosDiarias" height="350px" width="850px"></canvas>
				<div class="span11" id="seriesGraficoVendasArtigosDiarias"></div>
			</div>
			
			<div id="lojasMesDiv">
				<table id="lojasMesTabela" class="display row-border order-column" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Armazém</th>
							<th>Documento</th>
							<th>Total</th>
							<th>Data</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Armazém</th>
							<th>Documento</th>
							<th>Total</th>
							<th>Data</th>
						</tr>
					</tfoot>
				</table>
			</div>
			

			<div id="dataLoja">
				<table id="tabelaLoja" class="display row-border order-column" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Armazém</th>
							<th>Documento</th>
							<th>Data</th>
							<th>Total</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Armazém</th>
							<th>Documento</th>
							<th>Data</th>
							<th>Total</th>
						</tr>
					</tfoot>
				</table>
			</div>
			
			<div id="graficoLoja">
				<canvas id="graficoVendasLoja" height="350px" width="850px"></canvas>
				<div class="span11" id="seriesGraficoVendasLoja"></div>
			</div>
			
			<div id="dataVendedor">
				<table id="tabelaVendedor" class="display row-border order-column" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Nome</th>
							<th>Armazém</th>
							<th>Total</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Nome</th>
							<th>Armazém</th>
							<th>Total</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="span11" id="divMapa">
				<canvas id="graficoVendasPorVendedor" height="350px" width="350px"></canvas>
				<div class="span4 pull-right" id="seriesGraficoVendasPorVendedor"></div>
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
		
		<iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe> ';

		
		
$script="<script type='text/javascript'>

	// ***************************** VARIAVEIS *************************************
	
	//var iTableCounter = 1;
	var oTable;
	var oTable2;
	var oTable3;
	var detailsTableHtml;
	//var oInnerTable;
	
	// ********************************************************** INICIAR *****************************************************************

	var autocomplete = $('#typeahead').typeahead().on('keyup', function(ev){
		ev.stopPropagation();
		ev.preventDefault();
	}); 
					
	jQuery('#typeahead').on('input', function() {
		if($('#typeahead').val().length >= 1){
			var expr = $('#typeahead').val();
			$.getJSON('modules/stocks/detalheArtigo.php',{'exp':expr, 'mode':'nome'},function(infoArtigo){
				$('#typeahead').data('typeahead').source = infoArtigo;
			});
		} else {
			$('#typeahead').data('typeahead').source = [];
		}
	});
	
	
	/*var autocompleteVendedor = $('#typeaheadVendedor').typeahead().on('keyup', function(ev){
		ev.stopPropagation();
		ev.preventDefault();
	});*/ 
	jQuery('#typeaheadVendedor').on('input', function() {
		if($('#typeaheadVendedor').val().length >= 1){
			var expr = $('#typeaheadVendedor').val();
			
			$.getJSON('modules/vendas/detalhesVendedor.php',{'exp':expr},function(infoVendedor){
				$('#typeaheadVendedor').data('typeahead').source = infoVendedor;
			});
			
			
			
		} else {
			$('#typeaheadVendedor').data('typeahead').source = [];
		}
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
					if(aData[6] =='A'){
						$(nRow).addClass('alert alert-error');
					}
				}
		});

		oTable2 = $('#tabelaLoja').dataTable({
				sPaginationType: \"full_numbers\",
				sDom: 'TC<\"clear\">lfrtip',
				oTableTools: {
					sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
				},
				aoColumnDefs: [
                        { sClass: \"text-right\", aTargets: [ 3 ] }
                ],
				\"fnRowCallback\": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
					if(aData[5] =='A'){
						$(nRow).addClass('alert alert-error');
					}
				}
		});
		
		
		oTable3 = $('#tabelaVendedor').dataTable({
				sPaginationType: \"full_numbers\",
				sDom: 'TC<\"clear\">lfrtip',
				oTableTools: {
					sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
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
		
		
		oTable4 = $('#lojasMesTabela').dataTable({
				sPaginationType: \"full_numbers\",
				sDom: 'TC<\"clear\">lfrtip',
				oTableTools: {
					sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
				},
				aoColumnDefs: [
                        { sClass: \"text-right\", aTargets: [ 2 ] }
                ],
				\"fnRowCallback\": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
					if(aData[5] =='A'){
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
	
		$(\"#tabelaLoja\").tabs( {
		\"active\": function(event, ui) {
			var jqTable = $('table.display', ui.panel);
			if ( jqTable.length > 0 ) {
					jqTable.dataTable().fnAdjustColumnSizing();
			  }
		   }
		});
		
		$(\"#tabelaVendedor\").tabs( {
		\"active\": function(event, ui) {
			var jqTable = $('table.display', ui.panel);
			if ( jqTable.length > 0 ) {
					jqTable.dataTable().fnAdjustColumnSizing();
			  }
		   }
		});
	
	
		$(\"#lojasMesTabela\").tabs( {
		\"active\": function(event, ui) {
			var jqTable = $('table.display', ui.panel);
			if ( jqTable.length > 0 ) {
					jqTable.dataTable().fnAdjustColumnSizing();
			  }
		   }
		});
	
		/*$('#divMenuInterior').hide();
		$('#lojasMesDiv').hide();
		$('#dataArtigos').hide();
		$('#dataLoja').hide();
		$('#dataVendedor').hide();
		$('#graficoLoja').hide();
		$('#divMapa').hide();
		$('#divGrafico').hide();
		$('#divMenuLoja').hide();
		$('#divImagensMensais').hide();
		$('#divImagensMensais1').hide();
		$('#divMapa1').hide();
		$('#divImagens').hide();
		$('#divArtigosMensais').hide();*/
		
		//document.getElementById('imagemV').style.display = 'none';
		
		limpar();
		
		
		
		var d = new Date();
		var month = d.getMonth()+1;
		var day = d.getDate();

		var output = (day<10 ? '0' : '') + day+ '/' +(month<10 ? '0' : '') + month + '/' + d.getFullYear();
		
		$('#datainicial').val(output);
		$('#dialoja').val(output);
		$('#dialojaFim').val(output);
		$('#datafinal').val(output);
		$('#datainicial2').val(output);
		$('#datafinal2').val(output);
		
		$('#mesprocura').val(output);
		
		
		
		if(".$loja."==1 && ".$op."==1){
			
			$('#ArtigosBtn').removeAttr('class');
			$('#LojaBtn').removeAttr('class');
			$('#VendedorBtn').removeAttr('class');
			
			$('#LojaBtn').addClass('active');
			  
			  document.getElementById('ProcurarLoja').style.display = 'block';
			  document.getElementById('imagemV').style.display = 'block';
			  document.getElementById('ProcurarArtigo').style.display = 'none';
			  document.getElementById('ProcurarVendedor').style.display = 'none';
			  

			document.getElementById('loja').value  = id_loja;
			procurarLoja();
		} else if(".$loja."==1 && ".$op1."==1){ 
		
			$('#ArtigosBtn').removeAttr('class');
			$('#LojaBtn').removeAttr('class');
			$('#VendedorBtn').removeAttr('class');
			
			$('#LojaBtn').addClass('active');
			limpar();
			document.getElementById('ProcurarLoja').style.display = 'block';
			document.getElementById('ProcurarArtigo').style.display = 'none';
			document.getElementById('ProcurarVendedor').style.display = 'none';
			  
			document.getElementById('mesProcura').value  = today2;
			document.getElementById('loja').value  = id_loja;
			procurarLojaMes();
		}
	});
	
	function voltar(){
		limpar();
		var lojaSelecionada = $('#loja option:selected').text();
		$.get('modules/loja/index.php',{'nomeloja': lojaSelecionada} , function(data){
			$('#content').html(data);
		});
	}
	
	$(function() {
		$('#datainicial').datepicker();
		$('#datainicial').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
	});
	
	$(function() {
		$('#dialoja').datepicker();
		$('#dialoja').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
	});
	
	$(function() {
		$('#dialojaFim').datepicker();
		$('#dialojaFim').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
	});
		
	$(function() {
		$('#datafinal').datepicker();
		$('#datafinal').datepicker( 'option', 'dateFormat', 'dd/mm/yy');
	});
	
	$(function() {
		$('#datainicial2').datepicker();
		$('#datainicial2').datepicker( 'option', 'dateFormat', 'dd/mm/yy');	
	});
		
	$(function() {
		$('#datafinal2').datepicker();
		$('#datafinal2').datepicker( 'option', 'dateFormat', 'dd/mm/yy');
	});

	function limpar(){
		$('#waitingBarColor').css('width', '0%' );
		$('#waitingBarColor').html('');
		$('#dataArtigos').hide();
		$('#dataVendedor').hide();
		$('#dataLoja').hide();
		$('#divMenuInterior').hide();
		$('#divImagensMensais').hide();
		$('#divImagensMensais1').hide();
		$('#divGrafico').hide();
		$('#divMapa1').hide();
		$('#divImagens').hide();
		$('#divMenuLoja').hide();
		$('#lojasMesDiv').hide();
		$('#totaisDiv').html('');
		$('#divMapa1').html('');	
		$('#divData').html('');
		$('#divArtigosMensais').html('');
		$('#graficoLoja').hide();
		$('#divMapa').hide();	
		$('#divSubMenuLoja').hide();	
		$('#divMensaisLoja').hide();
		
	}

	function setActiveNav(param){
		limpar();
		$('#ArtigosBtn').removeAttr('class');
		$('#LojaBtn').removeAttr('class');
		$('#VendedorBtn').removeAttr('class');

		if(param == '0'){
			$('#ArtigosBtn').addClass('active');
			  limpar();
			  document.getElementById('ProcurarArtigo').style.display = 'block';
			  document.getElementById('ProcurarLoja').style.display = 'none';
			  document.getElementById('ProcurarVendedor').style.display = 'none';
		}else if(param == '1'){
			$('#LojaBtn').addClass('active');	
			  limpar();
			  document.getElementById('ProcurarLoja').style.display = 'block';
			  document.getElementById('ProcurarArtigo').style.display = 'none';
			  document.getElementById('ProcurarVendedor').style.display = 'none';
		}else if(param == '2'){
			$('#VendedorBtn').addClass('active');	
			  limpar();
			  document.getElementById('ProcurarVendedor').style.display = 'block';
			  document.getElementById('ProcurarLoja').style.display = 'none';
			  document.getElementById('ProcurarArtigo').style.display = 'none';
			
		}
	}
	
	// ********************************** EXTRAS **************** (modal, dias, meses, etc..) 

	function preencheModal(infodoc){
		
		$('#infoTitulo').empty();
		$('#infoEmpresa').empty();
		$('#infoCliente').empty();
		$('#infoDocumento').empty();
		
		$('#myModalDiv').css('display', 'block');
				
		
		var infoArray = infodoc.split('/');

		$.getJSON('modules/vendas/detalhesVenda.php',{'nroficial': infoArray[2].trim(), 'serie': infoArray[1].trim(),'tipodoc': infoArray[0].trim()},function(infoDoc){
			
			
			if(infoDoc[0].situacao =='A')
				$('#infoTitulo').html('<h2 style=\"color:red;\"> Documento Nr. '+ infodoc +' (ANULADO)</h2>');
			else
				$('#infoTitulo').html('<h2> Documento Nr. '+ infodoc +'</h2>');

				
			$('#infoEmpresa').html('".$_SESSION['nomeEmp']."');
			
			
			$('#infoEmpresa').append('<p id=\"idvendedor\" class=\"pull-right\">Vendedor: '+infoDoc[0].vendedor+'</p>');
			
			$('#infoCliente').html('<h3> Documento Nr. '+ infodoc +'</h3>');
			$('#infoCliente').append('<p>Exmo(s). Sr(s). </p><p> '+infoDoc[0].cliente +'</p><p> '+infoDoc[0].morada+' </p><p> '+infoDoc[0].email +'</p>');

			
			
			var infoc = '<br><br><table id=\"detalhesDocumentoTBL\" class=\"table table-striped\" ><thead><tr><th>Artigos</th><th>Qtd</th><th>Preço Unit.</th><th>IVA</th><th>Desconto</th><th>Total</th></tr></thead><tbody>';
			
			
			
			for(i=0; i<infoDoc.length;i++){		
				infoc += '<tr>';
				infoc += '<td> '+infoDoc[i].desart+'</td>';
				infoc += '<td style=\"text-align:center\"> '+$.number(infoDoc[i].qtd, 3,',','.')+'</td>';
				infoc += '<td style=\"text-align:center\"> '+$.number(infoDoc[i].pvn, 2,',','.')+'</td>';
				infoc += '<td style=\"text-align:center\"> '+$.number(infoDoc[i].taxa, 2,',','.')+'% </td>';
				
				if(infoDoc[i].desclinha1!='' || infoDoc[i].desclinha1!='0'
				 ||infoDoc[i].desclinha2!='' || infoDoc[i].desclinha2!='0'
				 ||infoDoc[i].desclinha3!='' || infoDoc[i].desclinha3!='0')
				{
					//Desconto
					if (infoDoc[i].desclinha1 !== '0'){ var auxDesc1 = infoDoc[i].desclinha1+'%|';}
					else{auxDesc1=''}
					if (infoDoc[i].desclinha2 !== '0'){ var auxDesc2 = infoDoc[i].desclinha2+'%|';}
					else{auxDesc2=''}
					if (infoDoc[i].desclinha3 !== '0'){ var auxDesc3 = infoDoc[i].desclinha3+'%|';}
					else{auxDesc3=''}
					
					infoc += '<td style=\"text-align:center\">'+auxDesc1+auxDesc2+auxDesc3+'</td>';
				}
												
				else{
					infoc += '<td style=\"text-align:center\"> 0,00% </td>';										
				}
			
				//Total											
				var Desc1 =  infoDoc[i].pvn*infoDoc[i].qtd*(-infoDoc[i].desclinha1/100+1);
				var Desc2 = Desc1 * (-infoDoc[i].desclinha2/100+1);
				var Desc3 = Desc2 * (-infoDoc[i].desclinha3/100+1);		

				infoc += '<td style=\"text-align:center\"> '+$.number(Desc3, 2,',','.')+' €</td>';
				infoc += '</tr>';

			/*old
				infoc += '<td style=\"text-align:center\"> '+$.number(infoDoc[i].pvn*infoDoc[i].qtd, 2,',','.')+' €</td>';
				infoc += '</tr>';
			*/	
			}
			
			infoc += '</tbody>';
			infoc += '</table>';
			//infoc += '<br><h2 class=\"pull-right\"> Total '+infoDoc[0].codmoeda+' : '+infoDoc[0].total+' </h2><br><br>';
			
			
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
			infoc += '<td class=\"pull-right\">'+ $.number(mercli,2,',','.') +'</td>';
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
	

function fnFormatDetails(table_id, html) {
		var sOut = '<table id=\"tabelaLoja' + table_id +'\">';
		sOut += html;
		sOut += \"</table>\";
		return sOut;
	}
		
	
//Mostra o PopUp com detalhes do documento
	function printModal(){
		//var printDivCSS = '<link href=\"../../css/iframePrintStyle.css\" rel=\"stylesheet\" type=\"text/css\">';
		window.frames[\"print_frame\"].document.body.innerHTML =  document.getElementById(\"documentView\").innerHTML;
		window.frames[\"print_frame\"].window.focus();
		window.frames[\"print_frame\"].window.print();
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
	
	
		
/* ************************ dia de hoje formatado **************************************** */
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
	
	
	

	
	// *********************************************************  DIARIAS **************************************************************************************************************



	function procurarArtigo(){
		limpar();
		$('#tabela').empty();
		$('#divData').empty();
	
		var aprocurar 		= document.getElementById('typeahead').value; 
		var dataIni 		= $('#datainicial').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var dataFim 		= $('#datafinal').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var loja 			= $('#loja').val();

		var htmldata = '';
		var totalCompras = parseFloat(0);

		var nome_loja = '';
	
		if(document.getElementById('loja').value=='todos')
			nome_loja='Todos';
		else{
			var x	  = document.getElementById('loja')
			nome_loja = x.options[x.selectedIndex].text;
		}
		
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
						
				$.getJSON('modules/vendas/procurarArtigo.php',{'dataini':din,'datafim':fin,'aprocurar':aprocurar, 'loja': loja },function(dataCab){
					total = dataCab.length;
				
					if(total < 1){
						total = 0;
					}
				
					if(dataCab.length > 0){
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
							obj[0] = dataCab[i].codart;
							obj[1] = dataCab[i].nome;
							obj[2] = dataCab[i].qtd;
							obj[3] = $.number(dataCab[i].pvn,".$_SESSION['decimaispv'].",',','.') +' '+ dataCab[i].moeda;
							obj[4] = dataCab[i].datadoc;
							obj[5] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+dataCab[i].documento+'\')\">'+dataCab[i].documento+'</a>';
							obj[6] = dataCab[i].situacao;
							
							table.oApi._fnAddData(oSettings, obj);
						}
						 
						oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
						table.fnDraw();
						
						table.fnSort([[4,'desc']]);

						$('#totaisDiv').html('<h1> De: '+din+' a '+ fin +' - '+ nome_loja +' </h1>');

						$('#divImagens').show();
					}
				});
			
			$('#waitingBarColor').css('width', '100%' );
			$('#waitingBarColor').html('Completo');
			
			}
		}
		
		$('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		$('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();	
	}



	function procurarLoja(){
		limpar();
		table = $('#tabelaLoja').dataTable({
			sPaginationType: \"full_numbers\",
			bRetrieve : true,
			bDestroy : true,
			oTableTools: {
				sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
			}
		});
		
		

		$('#dataLoja').show();
		$('#divData').html('');
		$('#waitingBarColor').css('width','0%' );

		var dataIniVAL 	= $('#dialoja').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var dia 		= $('#dialoja').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		var dataFimVAL 	= $('#dialojaFim').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var diafim 		= $('#dialojaFim').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		var loja 		= $('#loja').val();

		
		var nome_loja = '';
		if(document.getElementById('loja').value=='todos')
			nome_loja = 'Todos';
		else{
			 var x=document.getElementById('loja')
			nome_loja=x.options[x.selectedIndex].text;
		}

		
		oSettings = table.fnSettings();
		table.fnClearTable(this);

		if(dia != '' && diafim != '' && dataIniVAL <= dataFimVAL){
			$.getJSON('modules/vendas/diasLoja.php',{'dia':dia, 'diafim':diafim ,'loja': loja},function(data){
				if(data.length > 0){
					var total = 0;
					for (var i=0; i<data.length; i++){
						var obj = new Array();
						var tempDocInfo = data[i].tipodoc+'/'+data[i].serie+'/'+data[i].nroficial;
						obj[0] = data[i].loja;
						obj[1] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+tempDocInfo+'\')\">'+tempDocInfo+'</a>';
						obj[2] = data[i].datadoc;
						obj[3] = $.number(data[i].total,".$_SESSION['decimaispv'].",',','.') +' '+ data[i].moeda;
						obj[5] = data[i].situacao;
							
						table.oApi._fnAddData(oSettings, obj);
						total = parseFloat(total) + parseFloat(data[i].total);
					}
					

					//ordenar pela data do documento
					table.fnSort([[2,'desc']]);

					oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
					table.fnDraw();
					$('#waitingBarColor').css('width','100%');
					$('#waitingBarColor').html('Completo');
					//$('#totaisDiv').html('<h1> De: '+dia+' a '+ diafim +' - '+ nome_loja +' </h1>');
					$('#totaisDiv').html('<h1> De: '+dia+' a '+ diafim +' - '+ nome_loja +' </h1><br><h2> Total: '+$.number(total,".$_SESSION['decimaispv'].",',','.')+'".$_SESSION['moeda']." </h2>');
					$('#divMenuLoja').show();
					
				} else {
					$('#dataLoja').hide();
					$('#divData').append('<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Não existe informação para este intervalo de dias!</div>');
					$('#waitingBarColor').css('width', '100%' );
					$('#waitingBarColor').html('Completo');
				}

			});
		} 		
		else {
			$('#dataLoja').hide();
			$('#divData').append('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Verfique o intervalo de datas por favor!</div>');
			$('#waitingBarColor').css('width', '100%' );
			$('#waitingBarColor').html('Completo');
		}
	}



		
	function procurarVendedor(){
		limpar();
		$('#divMapa').html(''); 
		$('#tabela').empty();
		$('#divData').empty();
		
		
		var loja 			= $('#loja').val();
		var aprocurar 		= document.getElementById('typeaheadVendedor').value.split(' | ')[0]	; 
		var dataIni 		= $('#datainicial2').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var dataFim 		= $('#datafinal2').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		
		
		var nome_loja= '';
	
		if(document.getElementById('loja').value=='todos')
			nome_loja='Todos';
		else{
			 var x=document.getElementById('loja')
			nome_loja=x.options[x.selectedIndex].text;
		}
		

		var htmldata = '';
		

		
		if(!dataIni || !dataFim){
			$('#divData').append('<br><div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Existem campos por preencher!</div>');
			$('#waitingBarColor').css('width', '100%' );
			$('#waitingBarColor').html('Completo');
		}else{
			if(dataIni > dataFim){
				$('#divData').append('<br><div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Data Inicial superior à data final!</div>');
				$('#waitingBarColor').css('width', '100%' );
				$('#waitingBarColor').html('Completo');
			} else {
				$('#dataVendedor').show();
				$('#waitingBarColor').css('width', '50%' );
				
				var din = $('#datainicial2').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
				var fin = $('#datafinal2').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
						
					
				table = $('#tabelaVendedor').dataTable();
				oSettings = table.fnSettings();
				table.fnClearTable(this);
						
				$.getJSON('modules/vendas/procurarVendedor.php',{'loja':loja, 'dataini':din,'datafim':fin,'aprocurar':aprocurar },function(dataCab){
					
					total = dataCab.length;
				
					if(total < 1){
						total = 0;
					}
				
					if(dataCab.length > 0){
						cabecalhos = dataCab;
					}

					if (dataCab.length == 0){
						$('#divData').append('<br><div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>');	
						$('#waitingBarColor').css('width', '100%' );
						$('#waitingBarColor').html('Completo');
						$('#dataVendedor').hide();
					} else {
						var inc = (dataCab.length/100);
						var total = 0;
						for (var i=0; i<dataCab.length; i++){
									
							var obj = new Array();
							obj[0] = dataCab[i].vendedor;
							obj[1] = dataCab[i].loja;
							obj[2] = $.number(dataCab[i].montante,".$_SESSION['decimaispv'].",',','.') +' '+ dataCab[i].moeda;
							obj[3] = dataCab[i].situacao;
							table.oApi._fnAddData(oSettings, obj);
							total = parseFloat(total) + parseFloat(dataCab[i].montante);
						}
						 
						oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
						table.fnDraw();
						//$('#totaisDiv').html('<h1> De: '+din+' a '+ fin +' - '+ nome_loja +' </h1>');
						$('#totaisDiv').html('<h1> De: '+din+' a '+ fin +' - '+ nome_loja +' </h1><br><h2> Total: '+$.number(total,".$_SESSION['decimaispv'].",',','.')+'".$_SESSION['moeda']." </h2>');
						$('#divMenuInterior').show();
					}
				});
				
				$('#waitingBarColor').css('width', '100%' );
				$('#waitingBarColor').html('Completo');
			}
		}
		
		$('#datainicial2').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		$('#datafinal2').datepicker('option', 'dateFormat', 'dd/mm/yy').val();	
		
	
	}


	
	
// ********************************************* MES ***************************************************************************************************************
	
	function procurarArtigoMes(){
		limpar();
		$('#dataArtigos').show();
		$('#divData').html('');
		$('#waitingBarColor').css('width','0%' );
		
		var ano 		= $('#mesProcura').val().split(' ')[1];
		var monthName 	= $('#mesProcura').val().split(' ')[0];
		var monthData 	= getMonthMaxDaysByName(monthName);
		var month 		= monthData[0];
		var monthDays 	= monthData[1];
		var expr 	    = $('#typeahead').val();
		var loja 		= $('#loja').val();
		
		var nome_loja= '';
	
		if(document.getElementById('loja').value=='todos')
			nome_loja='Todos';
		else{
			var x=document.getElementById('loja')
			nome_loja=x.options[x.selectedIndex].text;
		}
		
		table = $('#tabelaArtigos').dataTable({
			bRetrieve : true,
			bDestroy : true,
			bProcessing: true,
			sPaginationType: \"full_numbers\",
			sDom: 'TC<\"clear\">lfrtip',
			aoColumnDefs: [
				{ sClass: \"text-right\", aTargets: [ 3 ] }
			],
			oTableTools: {
				sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf',
				aButtons: [                
						\"copy\",
						\"print\",
						{                    
							\"sExtends\":    \"collection\",
							\"sButtonText\": \"Save\",
							\"aButtons\":    [ \"csv\", \"xls\", \"pdf\" ]
						}
				]
			}
		});
		
		oSettings = table.fnSettings();
		table.fnClearTable(this);
		
		if(month != 0 && monthDays != 0){
			$.getJSON('modules/vendas/artigosMes.php',{'mes':month, 'exp': expr, 'ano':ano, 'dias':monthDays, 'loja': loja},function(data){
				if(data.length > 0){
					$('#waitingBarColor').css('width','50%' );
			
					for (var i=0; i<data.length; i++){
						var obj = new Array();
						obj[0] = data[i].codart;
						obj[1] = data[i].nome;
						obj[2] = data[i].qtd;
						//obj[3] = parseFloat(data[i].pvn).toFixed(".$_SESSION['decimaispv']."); 
						obj[3] = $.number(data[i].pvn,".$_SESSION['decimaispv'].",',','.') +' '+ data[i].moeda;
						obj[4] = data[i].datadoc;
						obj[5] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+data[i].documento+'\')\">'+data[i].documento+'</a>';
						obj[6] = data[i].situacao;
						table.oApi._fnAddData(oSettings, obj);
					}
					
					//ordenar pela data do documento
					table.fnSort( [ [4,'desc']] );
					 
					oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
					table.fnDraw();
					$('#totaisDiv').html('<h1> Mês: '+monthName+' - '+ nome_loja +' </h1>');
					$('#waitingBarColor').css('width','100%' );
					$('#waitingBarColor').html('Completo');
					$('#divImagensMensais1').show();
					
				} else {
					$('#dataArtigos').hide();
					$('#divData').append('<br><div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Não existe informação para este mês!</div>');
					$('#waitingBarColor').css('width', '100%' );
					$('#waitingBarColor').html('Completo');
				
				}
			});
		} else {
			$('#dataArtigos').hide();
			$('#divData').append('<br><div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>O mês não está correto!</div>');
			$('#waitingBarColor').css('width', '100%' );
			$('#waitingBarColor').html('Completo');
		}
	}	
	
	function procurarLojaMes(){
		limpar();
		$('#lojasMesDiv').show();
		
		var ano 		= $('#mesProcura').val().split(' ')[1];
		var monthName 	= $('#mesProcura').val().split(' ')[0];
		var monthData 	= getMonthMaxDaysByName(monthName);
		var month 		= monthData[0];
		var monthDays 	= monthData[1];
		var loja 		= $('#loja').val();

		var nome_loja = '';
	
		if(document.getElementById('loja').value=='todos')
			nome_loja='Todos';
		else{
			var x	  = document.getElementById('loja')
			nome_loja = x.options[x.selectedIndex].text;
		}

		table = $('#lojasMesTabela').dataTable({
			bRetrieve : true,
			bDestroy : true,
			sPaginationType: \"full_numbers\",
			aoColumnDefs: [
					{ sClass: \"text-right\", aTargets: [ 2 ] }
			]
		});
		oSettings = table.fnSettings();
		table.fnClearTable(this);
		
		if(month != 0 && monthDays != 0){
			$.getJSON('modules/vendas/artigosMesLoja.php',{'mes':month, 'ano':ano, 'dias':monthDays, 'loja':loja},function(data){
				if(data.length > 0){
					$('#waitingBarColor').css('width','50%' );
					$('#divSubMenuLoja').show();
		
					var total = 0;
					
					for (var i=0; i<data.length; i++){
						var obj = new Array();
						var tempDocInfo = data[i].tipodoc+'/'+data[i].serie+'/'+data[i].nroficial;
						obj[0] = data[i].loja;
						obj[1] = '<a  data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onClick=\"preencheModal(\''+tempDocInfo+'\')\" >'+tempDocInfo+'</a>';
						obj[2] = $.number(data[i].total,".$_SESSION['decimaispv'].",',','.') +' '+ data[i].moeda;
						obj[3] = data[i].datadoc;
						obj[5] = data[i].situacao; 
						 
						total =  parseFloat(total) + parseFloat(data[i].total);
						table.oApi._fnAddData(oSettings, obj);
					}
					
					
					//ordenar pela data do documento
					table.fnSort( [ [3,'desc'], [1, 'desc']] );
					 
					 
					oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
					table.fnDraw();
					
					$('#totaisDiv').html('<h1> Mês: '+monthName+' - '+ nome_loja +' </h1><br><h2> Total: '+$.number(total,".$_SESSION['decimaispv'].",',','.')+'".$_SESSION['moeda']." </h2>');
					$('#waitingBarColor').css('width','100%' );
					$('#waitingBarColor').html('Completo');
				} else {
					$('#lojasMesDiv').hide();
					$('#divData').append('<br><div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Não existe informação para este mês!</div>');
					$('#waitingBarColor').css('width', '100%' );
					$('#waitingBarColor').html('Completo');
					$('#totaisDiv').html('');
				}
			});
		} else {
			$('#lojasMesDiv').hide();
			$('#divData').append('<br><div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>O mês não está correto!</div>');
			$('#waitingBarColor').css('width', '100%' );
			$('#waitingBarColor').html('Completo');
			$('#totaisDiv').html('');
		}
	}
	
	
	function decode_utf8(s) {
	  return decodeURIComponent(escape(s));
	}

	
	function procurarVendedorMes(){
		limpar();
		$('#dataVendedor').show();	

		var nome_loja= '';
	
		if(document.getElementById('loja').value=='todos')
			nome_loja='Todos';
		else{
			var x=document.getElementById('loja')
			nome_loja=x.options[x.selectedIndex].text;
		}
		
		var ano 		= $('#mesProcura').val().split(' ')[1];
		var monthName 	= $('#mesProcura').val().split(' ')[0];
		var monthData 	= getMonthMaxDaysByName(monthName);
		var month 		= monthData[0];
		var monthDays 	= monthData[1];
		var aprocurar 	= $('#typeaheadVendedor').val().split(' | ')[0];
		var loja 		= $('#loja').val();
		
		table = $('#tabelaVendedor').dataTable({
			bRetrieve : true,
			bDestroy : true,
			aoColumnDefs: [
					{ sClass: \"text-right\", aTargets: [ 2 ] }
			]
			});
		oSettings = table.fnSettings();
		table.fnClearTable(this);
		
		if(month != 0 && monthDays != 0){
			$.getJSON('modules/vendas/procurarVendedorMes.php',{'mes':month, 'aprocurar': aprocurar, 'ano':ano, 'dias':monthDays, 'loja': loja},function(data){
				if(data.length > 0){
					$('#waitingBarColor').css('width','50%' );
			
					for (var i=0; i<data.length; i++){
						var obj = new Array();
						obj[0] = data[i].vendedor;
						obj[1] = data[i].loja;
						obj[2] = parseFloat(data[i].montante).toFixed(".$_SESSION['decimaispv'].") +' '+ data[i].moeda;
							
						table.oApi._fnAddData(oSettings, obj);
					}
					
					oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
					table.fnDraw();
					
					$('#totaisDiv').html('<h1> Mês: '+monthName+' - '+ nome_loja +' </h1>');
					$('#waitingBarColor').css('width','100%' );
					$('#waitingBarColor').html('Completo');
					$('#divImagensMensais').show();
				} else {
					$('#dataVendedor').hide();
					$('#divData').append('<br><div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Não existe informação para este mês!</div>');
					$('#waitingBarColor').css('width', '100%' );
					$('#waitingBarColor').html('Completo');
				}
			});
		} else {
			$('#dataVendedor').hide();
			$('#divData').append('<br><div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>O mês não está correto!</div>');
			$('#waitingBarColor').css('width', '100%' );
			$('#waitingBarColor').html('Completo');
		}
	}	


	
// *******************************************************************************************************************************************************************************************************
// ************************************************************************************* GRAFICOS ********************************************************************************************************
// *******************************************************************************************************************************************************************************************************

// ************************************************************************************* diarias *********************************************************************************************************
	function graficoArtigoDiarias(){
		$('#dataArtigos').hide();
		$('#divGrafico').html(''); 
		var html = '<canvas id=\"graficoVendasArtigosDiarias\" height=\"500px\" width=\"1000px\"></canvas><div class=\"span12\" id=\"seriesgraficoVendasArtigosDiarias\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\"></div>';
		$('#divGrafico').html(html);
		
		
		var loja 			= $('#loja').val();
		var dataIni 		= $('#datainicial').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var dataFim 		= $('#datafinal').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var aprocurar 		= document.getElementById('typeahead').value; 

		
		var din = $('#datainicial').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		var fin = $('#datafinal').datepicker('option', 'dateFormat', 'dd/mm/yy').val();

		
		if(dataIni !='' && dataFim != ''  && dataIni <= dataFim){
	
			$.getJSON('modules/vendas/grafico_diarias_Artigo.php',{'dataini':din,'datafim':fin, 'loja':loja, 'aprocurar': aprocurar },function(datasetRT){
				var ctx = $('#graficoVendasArtigosDiarias').get(0).getContext('2d');
				
				var optionsPr = {
								animation: true,
								inGraphDataShow: true,
								inGraphDataTmpl: \"<%= v3 + ' Un'%>\",
								scaleLabel : \"<%= formatNumber(value,2,',','.') + ' Un'%>\",
								showTooltips: true,
								//graphMin: 0,
								legend : true,
								annotateDisplay: true,			              
								annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' Un' %>\",
								responsive : true

									};
				myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
				
				$('#seriesgraficoVendasArtigosDiarias').html('');
				
				
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
				$('#seriesgraficoVendasArtigosDiarias').html(htmlToSeries);
			
			});
			$('#divGrafico').show();
		}
	}
	
	
	function graficoLojaDiario(){
		
		$('#dataLoja').hide();	
		$('#graficoLoja').html(''); 
		
		var html = '<canvas id=\"graficoVendasLoja\" height=\"500px\" width=\"1000px\"></canvas><div class=\"span12\" id=\"seriesGraficoVendasLoja\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\"></div>';
		$('#graficoLoja').html(html);
		var aprocurar 	    = $('#typeaheadVendedor').val();
		
		var loja 			= $('#loja').val();
		var dataIni 		= $('#dialoja').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var dataFim 		= $('#dialojaFim').datepicker( 'option', 'dateFormat', 'yymmdd').val();

		
		var din = $('#dialoja').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		var fin = $('#dialojaFim').datepicker('option', 'dateFormat', 'dd/mm/yy').val();

		
		if(dataIni !='' && dataFim != ''  && dataIni <= dataFim){
			$.getJSON('modules/vendas/grafico_diarias_Loja.php',{'dataini':din,'datafim':fin, 'loja':loja, 'aprocurar':aprocurar },function(datasetRT){
				var ctx = $('#graficoVendasLoja').get(0).getContext('2d');
				var optionsPr = {
								animation: true,
								inGraphDataShow: true,
								inGraphDataTmpl: \"<%= v3 + ' €'%>\",
								scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
								showTooltips: true,
								//graphMin: 0,
								legend : true,
								annotateDisplay: true,			              
								annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
								responsive : true
							};
				myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
				
				$('#seriesGraficoVendasLoja').html('');
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
				
				
				
				$('#seriesGraficoVendasLoja').html(htmlToSeries);
			});
			$('#dataLoja').hide();
			$('#graficoLoja').show();
			
		}
	}
		
	function graficoVendedorDiarias(){
		$('#dataVendedor').hide();
		$('#divMapa').html(''); 
		var html = '<canvas id=\"graficoVendedorDiariasoVendasPorVendedor\" height=\"500px\" width=\"1000px\"></canvas><div class=\"span12\" id=\"seriesgraficoVendedorDiariasoVendasPorVendedor\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\"></div>';
		$('#divMapa').html(html);		
		
		var loja 			= $('#loja').val();
		var dataIni 		= $('#datainicial2').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var dataFim 		= $('#datafinal2').datepicker( 'option', 'dateFormat', 'yymmdd').val();
		var aprocurar 	    = $('#procuraVendedor').val();		
		
		var din = $('#datainicial2').datepicker('option', 'dateFormat', 'dd/mm/yy').val();
		var fin = $('#datafinal2').datepicker('option', 'dateFormat', 'dd/mm/yy').val();

		
		if(dataIni !='' && dataFim != ''  && dataIni <= dataFim){
			$.getJSON('modules/vendas/grafico_diarias_Vendedores.php',{'dataini':din,'datafim':fin, 'loja':loja, 'aprocurar':aprocurar },function(datasetRT){
				var ctx = $('#graficoVendedorDiariasoVendasPorVendedor').get(0).getContext('2d');
				var optionsPr = {
								animation: true,
								inGraphDataShow: true,
								inGraphDataTmpl: \"<%= v3 + ' €'%>\",
								scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
								showTooltips: true,
								//graphMin: 0,
								legend : true,
								annotateDisplay: true,			              
								annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
								responsive : true
							};
				myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
				
				$('#seriesgraficoVendedorDiariasoVendasPorVendedor').html('');
				
				
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
				
				
				
				
				
				$('#seriesgraficoVendedorDiariasoVendasPorVendedor').html(htmlToSeries);
			});
			$('#divMapa').show();
		}
	}
	
	
	
// +++++++++++++++++++++++++++++++++++++++   mensais   ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
	
	function graficoArtigoMensais(){
		$('#dataArtigos').hide();

		var ano 		= $('#mesProcura').val().split(' ')[1];
		var monthName 	= $('#mesProcura').val().split(' ')[0];
		var monthData 	= getMonthMaxDaysByName(monthName);
		var month 		= monthData[0];
		var monthDays 	= monthData[1];
		var loja 			= $('#loja').val();
		var exp 		= document.getElementById('typeahead').value.split(' | ')[0];
		
	
		if(month != 0 && monthDays != 0){
			
			$('#divArtigosMensais').html(''); 
			var html = '<canvas id=\"graficoVendasArtigoMensais\" height=\"500px\" width=\"1000px\"></canvas><div class=\"span12\" id=\"seriesGraficoVendasArtigoMensais\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\"></div>';
			$('#divArtigosMensais').html(html);

			$.getJSON('modules/vendas/grafico_mes_Artigo.php',{'mes':month, 'ano':ano, 'dias':monthDays, 'loja': loja, 'exp': exp },function(datasetRT){
				var ctx = $('#graficoVendasArtigoMensais').get(0).getContext('2d');
				var optionsPr = {
								animation: true,
								inGraphDataShow: true,
								inGraphDataTmpl: \"<%= v3 + ' Un'%>\",
								scaleLabel : \"<%= formatNumber(value,2,',','.') + ' Un'%>\",
								showTooltips: true,
								//graphMin: 0,
								legend : true,
								annotateDisplay: true,			              
								annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' Un' %>\",
								responsive : true
							};
				myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
								
				$('#seriesGraficoVendasArtigoMensais').html('');
				
				
				var htmlToSeries = '<br><br>';
				var nrOfDatasets = datasetRT.datasets.length;
				var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
				
				htmlToSeries = '<table>';
				
				for(i=1;i<=datasetRT.datasets.length;i++){
					i-=1;
					htmlToSeries += '<tr>';
					for(j=0; j< nrOfRows; j++){
						if(typeof datasetRT.datasets[i] != 'undefined'){
							htmlToSeries += '<th style=\" width:180px; margin-left: 40px;\">';
							htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+';text-shadow: -1px 0 gray, 0 1px gray, 1px 0 gray, 0 -1px '+datasetRT.datasets[i].fillcolor+'; \">'+datasetRT.datasets[i].label+'</h2>';
							htmlToSeries += '</th>';
							i +=1;
						}
					}
					htmlToSeries += '</tr>';
				}
				htmlToSeries += '</table>';
				
				
				$('#seriesGraficoVendasArtigoMensais').html(htmlToSeries);
			});
			$('#divArtigosMensais').show();
		}
	}
	
	
	function graficoVendedorMensais(){
		$('#dataVendedor').hide();
		
		var ano 		= $('#mesProcura').val().split(' ')[1];
		var monthName 	= $('#mesProcura').val().split(' ')[0];
		var monthData 	= getMonthMaxDaysByName(monthName);
		var month 		= monthData[0];
		var monthDays 	= monthData[1];
		var aprocurar 	    = $('#procuraVendedor').val();
		var loja 		= $('#loja').val();

		if(month != 0 && monthDays != 0){
			$('#divMapa1').html(''); 
			var html = '<canvas id=\"graficoVendedorDiariasoVendasPorVendedor1\" height=\"500px\" width=\"1000px\"></canvas><div class=\"span12\" id=\"seriesgraficoVendedorDiariasoVendasPorVendedor1\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\"></div>';
			$('#divMapa1').html(html);
			$('#divMapa1').show();

			$.getJSON('modules/vendas/grafico_mensais.php',{'mes':month, 'ano':ano, 'dias':monthDays, 'loja': loja, 'aprocurar': aprocurar },function(datasetRT){
				var ctx = $('#graficoVendedorDiariasoVendasPorVendedor1').get(0).getContext('2d');
				var optionsPr = {
								animation: true,
								inGraphDataShow: true,
								inGraphDataTmpl: \"<%= v3 + ' €'%>\",
								scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
								showTooltips: true,
								//graphMin: 0,
								legend : true,
								annotateDisplay: true,			              
								annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
								responsive : true
							};
				myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
				$('#seriesgraficoVendedorDiariasoVendasPorVendedor1').html('');
				
				
				var htmlToSeries = '<br><br>';
				var nrOfDatasets = datasetRT.datasets.length;
				var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
				
				htmlToSeries = '<table>';
				
				for(i=1;i<=datasetRT.datasets.length;i++){
					i-=1;
					htmlToSeries += '<tr>';
					for(j=0; j< nrOfRows; j++){
						if(typeof datasetRT.datasets[i] != 'undefined'){
							htmlToSeries += '<th style=\" width:180px; margin-left: 40px;\">';
							htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+';text-shadow: -1px 0 gray, 0 1px gray, 1px 0 gray, 0 -1px '+datasetRT.datasets[i].fillcolor+'; \">'+datasetRT.datasets[i].label+'</h2>';
							htmlToSeries += '</th>';
							i +=1;
						}
					}
					htmlToSeries += '</tr>';
				}
				htmlToSeries += '</table>';
				
				
				$('#seriesgraficoVendedorDiariasoVendasPorVendedor1').html(htmlToSeries);
			});
		}
	}
	
	
	function graficoLojaMensais(){
	
		$('#lojasMesDiv').hide();
			
		var ano 		= $('#mesProcura').val().split(' ')[1];
		var monthName 	= $('#mesProcura').val().split(' ')[0];
		var monthData 	= getMonthMaxDaysByName(monthName);
		var month 		= monthData[0];
		var monthDays 	= monthData[1];
		var aprocurar 	= $('#procuraVendedor').val();
		var loja 		= $('#loja').val();

		if(month != 0 && monthDays != 0){
			$('#divMensaisLoja').html(''); 
			var html = '<canvas id=\"graficoMensaisLoja\" height=\"500px\" width=\"1000px\"></canvas><div class=\"span12\" id=\"seriesGraficoMensaisLoja\" style=\"border-style: solid; border-color: rgba(110, 178, 225, 0.9); border-width:2px\"></div>';
			$('#divMensaisLoja').html(html);
			$('#divMensaisLoja').show();

			$.getJSON('modules/vendas/grafico_mensais_loja.php',{'mes':month, 'ano':ano, 'dias':monthDays, 'loja': loja },function(datasetRT){
				var ctx = $('#graficoMensaisLoja').get(0).getContext('2d');
				
				var optionsPr = {
								animation: true,
								inGraphDataShow: true,
								inGraphDataTmpl: \"<%= v3 + ' €'%>\",
								scaleLabel : \"<%= formatNumber(value,2,',','.') + ' €'%>\",
								showTooltips: true,
								//graphMin: 0,
								legend : true,
								annotateDisplay: true,			              
								annotateLabel: \"<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ': ' : '') + v3 + ' €' %>\",
								responsive : true
							};
				myChart = new Chart(ctx).Bar(datasetRT, optionsPr);
								
				$('#seriesGraficoMensaisLoja').html('');
				
				
				var htmlToSeries = '<br><br>';
				var nrOfDatasets = datasetRT.datasets.length;
				var nrOfRows =Math.ceil(nrOfDatasets/Math.ceil(nrOfDatasets/5)); 
				
				htmlToSeries = '<table>';
				
				for(i=1;i<=datasetRT.datasets.length;i++){
					i-=1;
					htmlToSeries += '<tr>';
					for(j=0; j< nrOfRows; j++){
						if(typeof datasetRT.datasets[i] != 'undefined'){
							htmlToSeries += '<th style=\" width:180px; margin-left: 40px;\">';
							htmlToSeries += '<h2 style=\"color:'+datasetRT.datasets[i].strokeColor+';text-shadow: -1px 0 gray, 0 1px gray, 1px 0 gray, 0 -1px '+datasetRT.datasets[i].fillcolor+'; \">'+datasetRT.datasets[i].label+'</h2>';
							htmlToSeries += '</th>';
							i +=1;
						}
					}
					htmlToSeries += '</tr>';
				}
				htmlToSeries += '</table>';
				
				
				$('#seriesGraficoMensaisLoja').html(htmlToSeries);
			});
		}
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
	
	
";

	
	echo ($content.$script);
	
}


?>