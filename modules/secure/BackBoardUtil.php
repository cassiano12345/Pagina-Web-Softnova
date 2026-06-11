<?php
	
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$options = "";

	
	$sqlquery = "select idgrupo, nome from dash_grupos where codemp=".$_SESSION['codemp'];
	$rs 	  = $db->Execute($sqlquery);
	
	if($rs){
		While($linha = $rs->FetchRow()){
			$options .= '<option value='.$linha[0].'>'.$linha[1].'</option>';
		}
	}
	
	
$page = ' 	<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.php">Home</a> 
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="#">Gestão Utilizador</a></li>
			</ul>
			
			<h1>Gestão de Utilizador</h1>
			<br>
							
			<div class="teste">	 </div>

			<div class="row-fluid sortable ui-sortable green>
				<div class="box-content span7 "> 
					<div id="divUserTable" width="100%">
						<table id="usersTable" class="display compact">
							<thead>
								<tr>
									<th>Id</th>
									<th>Cód. Utilizador</th>
									<th>Nome</th>
									<th>Grupo</th>
									<th></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Id</th>
									<th>Cód. Utilizador</th>
									<th>Nome</th>
									<th>Grupo</th>
									<th></th>
								</tr>
							</tfoot>
						</table>
					</div>
					
					<br>
							
				</div>	
			</div>	
		
		
			<div id="myModal" class="modal hide fade in" style="display: block;" aria-hidden="false">
				<div class="modal-header">
					<button data-dismiss="modal" class="close" type="button" onClick="cancelarAlteracoes()">×</button>
					<h3>Alterar Utilizador</h3>
				</div>
				<div class="modal-body">
					<div class="box-content">
						<fieldset>
							<div class="control-group">
								<label for="modalUserID" class="control-label" style="color: #000000"> Id </label>
								<div class="controls">
									<input type="text" placeHolder="Id" id="modalUserID" class="input-xlarge focused" disabled>
								</div>
								<label for="modalUserCodUtil" class="control-label" style="color: #000000"> Código Utilizador </label>
								<div class="controls">
									<input type="text" placeHolder="Código de Utilizador" id="modalUserCodUtil" class="input-xlarge focused"disabled>
								</div>
								<label for="modalUserUtil" class="control-label" style="color: #000000"> Nome </label>
								<div class="controls">
									<input type="text" placeHolder="Nome" id="modalUserUtil" class="input-xlarge focused"disabled>
								</div>
							</div>
							
							
							<!--<div class="control-group" style="color: #000000">
								<label for="modalUserGroup" class="control-label"> Grupo </label>
								<div class="controls">
								  <select id="modalUserGroup" name="modalUserGroup">
									'.$options.'
								  </select>
								</div>
							</div>-->
							
							<div class="control-group">
								<!--<label for="modalUserPassAntiga" class="control-label" style="color: #000000"> Password Antiga </label>
								<div class="controls">
									<input type="password" placeHolder="Password" id="modalUserPassAntiga" class="input-xlarge focused">
								</div>-->
							
								<label for="modalUserPassOne" class="control-label" style="color: #000000"> Password </label>
								<div class="controls">
									<input type="password" placeHolder="Password" id="modalUserPassOne" class="input-xlarge focused">
								</div>
							
								<label for="modalUserPassTwo" class="control-label" style="color: #000000"> Repetir Password </label>
								<div class="controls">
									<input type="password" placeHolder="Repetir Password" id="modalUserPassTwo" class="input-xlarge focused">
								</div>
							</div>
						  
						 
						  
						</fieldset>
					</div>
					
				</div>
				<div class="modal-footer">
					<a data-dismiss="modal" class="btn" onClick="cancelarAlteracoes()"> Cancelar </a>
					<a class="btn btn-primary" onClick="edit()"> Guardar Alterações </a>
				</div>
			</div>
		
		
		
			
			<script>
			
			var structureTable = "<table id=\"usersTable\" class=\"display compact\">";
				structureTable += "<thead>";
				structureTable += "<tr>";
				structureTable += "<th></th>";
				structureTable += "<th>Id</th>";
				structureTable += "<th>Cód. Utilizador</th>";
				structureTable += "<th>Nome</th>";
				structureTable += "<th>Grupo</th>";
				structureTable += "</tr>";
				structureTable += "</thead>";
				structureTable += "</table>";

				$(".btn-minimize").click(function(e){
					e.preventDefault();
					var $target = $(this).parent().parent().next(\'.box-content\');
					if($target.is(\':visible\')){ 
						$(\'i\',$(this)).removeClass(\'chevron-up\').addClass(\'chevron-down\');
						$("#divAddUser").removeClass("green");
					}else{
						$(\'i\',$(this)).removeClass(\'chevron-down\').addClass(\'chevron-up\');
						$("#divAddUser").addClass("green");
					}
					$target.slideToggle();
					
				});
				
				
				function cancelarAlteracoes(){
					$("#myModal").hide();
					clearFields();
				}
				
				
				
				function goToDashboard(){
					window.location.replace("../../index.php");
				}
				
				$(document).ready(function() {
					var oTable = $("#usersTable").dataTable({
						bDestroy 	 : true,
						bRetrieve 	 : true,
						sPaginationType: "full_numbers"
					});
					updateTable();
					$("#myModal").hide();
				});

				function edit(){
					var ident 		= $("#modalUserID").val();
					var codutil 	= $("#modalUserCodUtil").val();
					var util 		= $("#modalUserUtil").val();	
					var passAntiga 	= $("#modalUserPassAntiga").val();				
					var passOne 	= $("#modalUserPassOne").val();
					var passTwo 	= $("#modalUserPassTwo").val();										
										
					if(passOne == "" && passTwo =="")
					{
						if(ident != "" && codutil != "" && util != ""){
							//alterar info
							$.getJSON("modules/secure/MasterPiece.php",{"identifier":ident,"user":codutil,"name":util,"mode":"UPDATEUTIL"},function(data)
							{
								if(data==1){
									alert("Password não foi atualizada");
									updateTable();
									clearFields();
									cancelarAlteracoes();
								}
								else{
									alert(data);
									alert("Erro");
								}
							});
						} else {
								alert("Existem campos por preencher!");
						}
					}else
					{
						if(passOne == passTwo && passOne.length > 0){
							//verificar pass antiga
							$.getJSON("modules/secure/MasterPiece.php",{"identifier":ident, "user": codutil, "pass":passAntiga, "mode":"CONFIRMAPWD"},function(data)
							{
								if(data == 1)
								{
									//alterar password
									$.getJSON("modules/secure/MasterPiece.php",{"identifier":ident,"user":codutil,"name":util,"pass":passOne,"mode":"UPDATEALLUSER"},function(data)
									{
										if(data==1){
											alert("Utilizador editado com sucesso.");
											updateTable();
											clearFields();
											cancelarAlteracoes();
										}
										else{
											alert(data);											
										}
									});
								}
								else{
									alert("Password está incorreta!");
								}
								
							});
						}else{
							alert("As passwords são diferentes!")
						}
					}					
				}
				
				function updateTable(){

					$.getJSON("modules/secure/MasterPiece.php",{"mode":"ALTUSER"},function(data){
						
						$("#divUserTable").empty();
						$("#divUserTable").html(structureTable);
						
						table = $("#usersTable").dataTable();
						oSettings = table.fnSettings();
						table.fnClearTable(this);
						
						for (var i=0; i<data.length; i++){
							var obj = new Array();
							obj[0] = "<img id=\"imag"+i+"\" src=\"img/additional/edit.png\">";
							obj[1] = data[i].id;
							obj[2] = data[i].codutil;
							obj[3] = data[i].nomeutil;
							obj[4] = data[i].grupo;
							table.oApi._fnAddData(oSettings, obj);
						}
						 
						oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
						table.fnDraw();
						
						$("#usersTable tbody ").on("click", "tr", function () {
							var nTr = $(this)[0];
							var nTds = this;
							
							var rowIndex = table.fnGetPosition( $(nTds).closest("tr")[0] ); 
							var aData = table.fnGetData(rowIndex);

							$("#modalUserID").val(aData[1]);
							$("#modalUserCodUtil").val(aData[2]);
							$("#modalUserUtil").val(aData[3]);
							
							$.getJSON("modules/secure/MasterPiece.php",{"nomegrupo":aData[4], "mode":"GETGROUPID"},function(data){
								 $("#modalUserGroup").val(data);
							});

							$("#myModal").show();
						});
					});
				
					clearFields();
				}
				
				function clearFields(){
					$("#userID").val(\'\');
					$("#userCodUtil").val(\'\') ;
					$("#userUtil").val(\'\');
					$("#userPassOne").val(\'\');
					$("#userPassTwo").val(\'\');
					$("#userGroup").val(\'\');
					
					$("#modalUserID").val(\'\');
					$("#modalUserCodUtil").val(\'\');
					$("#modalUserUtil").val(\'\');
					$("#modalUserPassAntiga").val(\'\');
					$("#modalUserPassOne").val(\'\');
					$("#modalUserPassTwo").val(\'\');
					$("#modalUserGroup").val(\'\');
				}
				
			</script>
			
			';
	
	echo $page;

	
?>



