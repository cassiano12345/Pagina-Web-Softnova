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

	try{
		$rs = $db->execute('Select dl.id, dl.Nome from dash_lojas dl, dash_acessos am where dl.codemp = '.$_SESSION["codemp"].' and am.codemp = dl.codemp and am.idloja   = dl.id and am.idgrupo  = '.$_SESSION["grupo"].'');
		if($rs){
			While($row = $rs->FetchRow()){
				$contentOptions .= '<option value='.$row[0].'>'.$row[1].'</option>';
			}
		}
	} catch(Exception $e) {
		echo $e;
	}
	try{
		$rs = $db->execute('Select distinct ano from stk_saldos where codemp = '.$_SESSION["codemp"].' order by ano desc');
		if($rs){
			While($row = $rs->FetchRow()){
				$yearOptions .= '<option value='.$row[0].'>'.$row[0].'</option>';
			}
		}
	} catch(Exception $e) {
		echo $e;
	}
	try{
		$rs = $db->execute('Select distinct mes from stk_saldos where codemp = '.$_SESSION["codemp"].' order by mes asc');
		if($rs){
			While($row = $rs->FetchRow()){
				$dateObj   = DateTime::createFromFormat('!m', $row[0]);
				$monthName = $dateObj->format('F');
				$monthOptions .= '<option value='.$row[0].'>'.$monthName.'</option>';
			}
		}
	} catch(Exception $e) {
		echo $e;
	}

	//**************************************************
	if (isset($_GET["op"])){
		$op1 	 = $_GET["op"];
	}
	else{
		$op1 	 = "";
	}

	if (isset($_GET["op_d"])){
		$op_d 	 = $_GET["op_d"];
	}
	else{
		$op_d 	 = "";
	}

	if (isset($_GET["id_loja"])){
		$id_loja = $_GET["id_loja"];
	}
	else{
		$id_loja = "";
	}
	//****************************************************

	$loja 	 = 0;
	$op  	 = 0;

	if ($id_loja != ""){
		$loja=1;
		$htmlButtonVoltar = '<button class="btn btn-info icon-arrow-left" id="imagemV" onClick="voltar()"> Voltar </button>';
	}else{
		$htmlButtonVoltar = '';
	}

	if ($op1!=""){
		$op=1;
	}
	if ($op_d!=""){
		$op=2;
	}

	$cabecalho = '
			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.php">Home</a>
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="#">Movimento Stocks</a></li>
			</ul>



			<div class="box span11">
				<div class="clearfix"></div>
				<div class="box-header" data-original-title="">
					<h2><i class="halflings-icon edit"></i><span class="break"></span>Movimento Stocks - Pesquisa</h2>
				</div>

				<div style="display: block;" class="box-content">
					<div clas="span7">
						<nav role="navigation" class="navbar navbar-default">
							<div>
								<ul class="nav navbar-nav">

									<li id="existenciasBtn"  class="active">
										<a onclick="setActiveNav(0)" style="cursor:pointer">Existências</a>
									</li>
									<li id="roturaBtn"  class="">
										<a onclick="setActiveNav(2)" style="cursor:pointer">Rotura de Stocks</a>
									</li>
									<li id="stockBtn"  class="">
										<a onclick="setActiveNav(3)" style="cursor:pointer">Stock Disponível</a>
									</li>

								</ul>
							</div>
						</nav>
						<br>
					</div>
					<div class="clearfix"></div>
					<br>
					<div class="span7">
						<form class="form-horizontal" id="formSearch">
							<fieldset>
								<div class="control-group">
									<label class="control-label" for="loja">Armazém</label>
									<div class="controls">
										<select id="loja">
											<option value="todos">Todos</option>
											'.$contentOptions.'
										</select>
									</div>
								</div>

								<div id="divExistencias">
									<div class="control-group">
										<label class="control-label" for="dataLimite" >Data Limite</label>
										<div class="controls">
											<input class="span6" style="" id="dataLimite" type="text">

										</div>
									</div>

								</div>

								<div class="control-group">
									<div class="controls">
										<br><button onclick="procurar()" onmousedown="limparProgressBar()" type="button" class="btn btn-success">Pesquisar <i class="halflings-icon search"></i></button>

									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div id="waitingBar" class="progress progress-striped progress-success active" style="display:inherit">
				<div id="waitingBarColor" class="bar" style="width:0%;"></div>
			</div>	';


			$cabecalho .= $htmlButtonVoltar.'


			<div id="divData"></div>
			<div id="tabela"></div>

			<div id="dataExistencias">
				<table id="existencias" class="display row-border order-column" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Armazém</th>
							<th>Código</th>
							<th>Nome</th>
							<th>Qtd</th>

							<th>Últ. Mov.</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Armazém</th>
							<th>Código</th>
							<th>Nome</th>
							<th>Qtd</th>

							<th>Últ. Mov.</th>
						</tr>
					</tfoot>
				</table>
			</div>

			<div id="dataRotura">
				<table id="rotura" class="display row-border order-column" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Armazém</th>
							<th>Armazém</th>
							<th>Código</th>
							<th>Artigo</th>
							<th>Stk. Min.</th>
							<th>Qtd. Stk.</th>
							<th>Ped. Clientes</th>
							<th>Enc. Fornecedor</th>
							<th>Rotura</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Armazém</th>
							<th>Armazém</th>
							<th>Código</th>
							<th>Artigo</th>
							<th>Stk. Min.</th>
							<th>Qtd. Stk.</th>
							<th>Ped. Clientes</th>
							<th>Enc. Fornecedor</th>
							<th>Rotura</th>
						</tr>
					</tfoot>
				</table>
			</div>

			<div id="dataDisponivel">
				<table id="disponivel" class="display row-border order-column" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Armazém</th>
							<th>Armazém</th>
							<th>Código</th>
							<th>Nome</th>
							<th>Qtd</th>
							<th>Enc. Cli.</th>
							<th>Req. For.</th>
							<th>Disponível</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Armazém</th>
							<th>Armazém</th>
							<th>Código</th>
							<th>Nome</th>
							<th>Qtd</th>
							<th>Enc. Cli.</th>
							<th>Req. For.</th>
							<th>Disponível</th>
						</tr>
					</tfoot>
				</table>
			</div>


			';


	$script="<script>


		$(function() {
			$('#dataLimite').datepicker();

			$('#dataLimite').datepicker( 'option', 'dateFormat', 'dd/mm/yy');
		});

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
		var id_loja = '".$id_loja."';

		$(document).ready(function(){
			document.getElementById('dataLimite').value  = today;
			modo = 'existencias';



			$('#existencias').dataTable({
				sPaginationType: \"full_numbers\",
				sDom: 'TC<\"clear\">lfrtip',
				oTableTools: {
					sSwfPath: 'flash.swf'
				},
				aoColumnDefs: [
                        { sClass: \"text-right\", aTargets: [ 3 ] }
                ]
			});


			var oTable2 = $('#rotura').dataTable({
				sPaginationType: \"full_numbers\",
				sDom: 'TC<\"clear\">lfrtip',
				oTableTools: {
					sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
				},
				aoColumnDefs: [
                        { sClass: \"text-right\", aTargets: [ 4 ] },
                        { sClass: \"text-right\", aTargets: [ 5 ] },
                        { sClass: \"text-right\", aTargets: [ 6 ] },
                        { sClass: \"text-right\", aTargets: [ 7 ] },
                        { sClass: \"text-right\", aTargets: [ 8 ] }
                ]
			});

			var oTable3 = $('#disponivel').dataTable({
				sPaginationType: \"full_numbers\",
				sDom: 'TC<\"clear\">lfrtip',
				oTableTools: {
					sSwfPath: 'http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf'
				},
				aoColumnDefs: [
                        { sClass: \"text-right\", aTargets: [ 4 ] },
                        { sClass: \"text-right\", aTargets: [ 5 ] },
                        { sClass: \"text-right\", aTargets: [ 6 ] },
                        { sClass: \"text-right\", aTargets: [ 7 ] }
                ]
			});

			$(\"#existencias\").tabs( {
			\"active\": function(event, ui) {
				var jqTable = $('table.display', ui.panel);
				if ( jqTable.length > 0 ) {
						jqTable.dataTable().fnAdjustColumnSizing();
				  }
			   }
			});

			$(\"#rotura\").tabs( {
			\"active\": function(event, ui) {
				var jqTable = $('table.display', ui.panel);
				if ( jqTable.length > 0 ) {
						jqTable.dataTable().fnAdjustColumnSizing();
				  }
			   }
			});

			$(\"#disponivel\").tabs( {
			\"active\": function(event, ui) {
				var jqTable = $('table.display', ui.panel);
				if ( jqTable.length > 0 ) {
						jqTable.dataTable().fnAdjustColumnSizing();
				  }
			   }
			});

			$('#waitingBar').hide();

			$('#existencias').DataTable({
				bDestroy : true,
				bProcessing: true,
				bRetrieve : true,
				aoColumnDefs: [
                        { sClass: \"text-right\", aTargets: [ 3 ] }
                ]
			});

			$('#rotura').DataTable({
				bDestroy : true,
				bProcessing: true,
				bRetrieve : true,
				aoColumnDefs: [
                        { sClass: \"text-right\", aTargets: [ 4 ] },
                        { sClass: \"text-right\", aTargets: [ 5 ] },
                        { sClass: \"text-right\", aTargets: [ 6 ] },
                        { sClass: \"text-right\", aTargets: [ 7 ] },
                        { sClass: \"text-right\", aTargets: [ 8 ] }
                ]
			});

			$('#disponivel').DataTable({
				bDestroy : true,
				bProcessing: true,
				bRetrieve : true,
				aoColumnDefs: [
                        { sClass: \"text-right\", aTargets: [ 4 ] },
                        { sClass: \"text-right\", aTargets: [ 5 ] },
                        { sClass: \"text-right\", aTargets: [ 6 ] },
                        { sClass: \"text-right\", aTargets: [ 7 ] }
                ]

			});

			$('#dataExistencias').hide();
			$('#dataRotura').hide();
			$('#dataDisponivel').hide();

			if(".$loja."==1 && ".$op."== 0){
				$('#dataExistencias').show();
				document.getElementById('loja').value  = id_loja ;
				document.getElementById('dataLimite').value  = today;
				procurar();
			}
			else if(".$loja."==1 && ".$op."== 1){
				$('#dataRotura').show();
				setActiveNav(2);
				document.getElementById('loja').value  = id_loja ;
				document.getElementById('dataLimite').value  = today;
				procurar();
			}
			else if(".$loja."==1 && ".$op."== 2){
				$('#dataDisponivel').show();
				setActiveNav(3);
				document.getElementById('loja').value  = id_loja ;
				document.getElementById('dataLimite').value  = today;
				procurar();
			}


		});


		function setActiveNav(param){

			$('#existenciasBtn').removeAttr('class');
			$('#inventarioBtn').removeAttr('class');
			$('#roturaBtn').removeAttr('class');
			$('#stockBtn').removeAttr('class');

			$('#dataExistencias').hide();
			$('#dataRotura').hide();
			$('#dataDisponivel').hide();


			if(param == '0'){
				$('#existenciasBtn').addClass('active');
				modo = 'existencias';

				$('#divdata').html('');
				$('#tabela').html('');

				$('#waitingBar').hide();

			}else if(param == '2'){
				$('#roturaBtn').addClass('active');
				modo = 'rotura';

				$('#divdata').html('');
				$('#tabela').html('');


				$('#waitingBar').hide();

			}else if(param == '3'){
				$('#stockBtn').addClass('active');
				modo = 'disponivel';

				$('#divdata').html('');
				$('#tabela').html('');

				$('#waitingBar').hide();
			}
		}

		function limparProgressBar(){
			$('#waitingBarColor').css('width', '0%' );
			$('#waitingBarColor').html('');
		}

		function opencloseDropdown(){
			if ( $('#dropdown_user').hasClass('open') ){
				$('#dropdown_user').removeClass('open');
			} else{
				$('#dropdown_user').addClass('open');
			}
		}

		function logout(){
			$.get('modules/logout/index.php',function(data){
				$('body').html(data);
			});
		}



		var modo;




		function procurar(){

			$('#waitingBar').show();

			var total = 0;
			var nomeloja = document.getElementById('loja').value;
			var dataLimite = document.getElementById('dataLimite').value;

			var htmlTable ='';

			if(dataLimite != ''){

				$.getJSON('modules/stocks/cabecalhoTabelas.php',{'nomeloja':nomeloja, 'datalimite':dataLimite, 'modo':modo},function(dataCab){

					total = dataCab.length;
					if(total < 1){
						total = 0;
					}

					htmlTable +='<h1>'+total+' Resultados </h1><br>';

					if(dataCab.length > 0){
						cabecalhos = dataCab;

						if(modo=='existencias'){
							$('#dataExistencias').show();

							table = $('#existencias').dataTable();
							oSettings = table.fnSettings();
							table.fnClearTable(this);

							var inc = (dataCab.length/100);

							for (var i=0; i<dataCab.length; i++){
								$('#waitingBarColor').css('width', (i*inc)+'%' );
								var obj = new Array();
								obj[0] = dataCab[i].loja;
								obj[1] = dataCab[i].codart;
								obj[2] = dataCab[i].nome;
								obj[3] = dataCab[i].qtd;

								obj[4] = dataCab[i].ultmov;
								table.oApi._fnAddData(oSettings, obj);
							}

							oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
							table.fnDraw();


						}else if(modo=='rotura'){

							$('#dataRotura').show();

							table = $('#rotura').dataTable();
							oSettings = table.fnSettings();
							table.fnClearTable(this);

							$('#waitingBarColor').css('width', '60%' );

							var inc = (dataCab.length/100);

							for (var i=0; i<dataCab.length; i++){
								$('#waitingBarColor').css('width', (i*inc)+'%' );
								var obj = new Array();
								obj[0] = dataCab[i].loja;
								obj[1] = dataCab[i].armazem;
								obj[2] = dataCab[i].codart;
								obj[3] = dataCab[i].nome;
								obj[4] = dataCab[i].qtd2;
								obj[5] = dataCab[i].qtdstk;
								obj[6] = dataCab[i].qtdecl;
								obj[7] = dataCab[i].qtdref;
								obj[8] = dataCab[i].rotura;
								table.oApi._fnAddData(oSettings, obj);
							}

							oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
							table.fnDraw();


						} else if(modo=='disponivel'){


							$('#dataDisponivel').show();

							table = $('#disponivel').dataTable();
							oSettings = table.fnSettings();
							table.fnClearTable(this);

							$('#waitingBarColor').css('width', '60%' );

							var inc = (dataCab.length/100);

							for (var i=0; i<dataCab.length; i++){
								$('#waitingBarColor').css('width', (i*inc)+'%' );
								var obj = new Array();
								obj[0] = dataCab[i].loja;
								obj[1] = dataCab[i].armazem;
								obj[2] = dataCab[i].codart;
								obj[3] = dataCab[i].nome;
								obj[4] = dataCab[i].qtdstk;
								obj[5] = dataCab[i].qtdecl;
								obj[6] = dataCab[i].qtdref;
								obj[7] = dataCab[i].disponivel;
								table.oApi._fnAddData(oSettings, obj);
							}

							oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
							table.fnDraw();

						}


						$('#tabela').html(htmlTable);
						$('body').css('cursor', 'default');
						//$('#waitingBarColor').css('width', '100%' );
						$('#waitingBarColor').html('Completo');

					} else {

						$('#dataExistencias').hide();
						$('#dataRotura').hide();
						$('#dataDisponivel').hide();

						$('#waitingBarColor').css('width', '100%' );
						htmlTable +='<div class=\"alert alert-info\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Info:</span> Sem dados para apresentar!</div>';
						htmlTable +='</div>';
						$('#tabela').html(htmlTable);
					}

					$('#waitingBarColor').css('width', '100%' );
					$('#waitingBarColor').html('Completo');
				});
			} else {
				$('#waitingBarColor').css('width', '100%' );
				htmlTable +='<div class=\"alert alert-erro\" role=\"alert\"><span class=\"halflings-icon info-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span> Deve preencher a data limite!</div>';
				htmlTable +='</div>';
				htmlTable +='</div>';
				htmlTable +='</div>';
				$('#tabela').html(htmlTable);
			}

		}


		function voltar(){
			var lojaSelecionada = $('#loja option:selected').text();
			$.get('modules/loja/index.php',{'nomeloja': lojaSelecionada} , function(data){
				$('#content').html(data);
			});
		}


		</script>";


	echo ($cabecalho.$content." ".$script);

}


?>
