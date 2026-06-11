<?php
	
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

//FAZER VERIFICACAO SE TEM A SESSAO INICIADA COMO MASTER

if (!isset($_SESSION['user']) || $_SESSION['codutil'] != 'admin'){
	echo "";
	header("Location: ../../index.php");
}
else{
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
				<li><a href="#">Gestão Utilizadores</a></li>
			</ul>
			
			<h1>Gestão de Utilizadores</h1>
			<br>
					
				 
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
							
					
					<div class="row-fluid sortable ui-sortable green" id="divAddUser">
						<div class="box span12 green">
							<div data-original-title="" class="box-header">
								<h2><i class="halflings-icon edit"></i><span class="break"></span>Adicionar Utilizador</h2>
								<div class="box-icon">
									<a class="btn-minimize" ><i class="halflings-icon chevron-down"></i></a>
								</div>
							</div>

							<div class="box-content">
								<fieldset>
									<div class="control-group">
										<label for="userID" class="control-label" style="color: #000000"> Id </label>
										<div class="controls">
											<input type="text" placeHolder="Id" id="userID" class="input-xlarge focused">
										</div>
										<label for="userCodUtil" class="control-label" style="color: #000000"> Código Utilizador </label>
										<div class="controls">
											<input type="text" placeHolder="Código de Utilizador" id="userCodUtil" class="input-xlarge focused">
										</div>
										<label for="userUtil" class="control-label" style="color: #000000"> Nome </label>
										<div class="controls">
											<input type="text" placeHolder="Nome" id="userUtil" class="input-xlarge focused">
										</div>
									</div>
									
									
									<div class="control-group " style="color: #000000">
										<label for="userGroup" class="control-label"> Grupo </label>
										<div class="controls" >
										  <select id="userGroup">
											'.$options.'
										  </select>
										</div>
									</div>
									
									<div class="control-group">
										<label for="userPassOne" class="control-label" style="color: #000000"> Password </label>
										<div class="controls">
											<input type="password" placeHolder="Password" id="userPassOne" class="input-xlarge focused">
										</div>
									
										<label for="userPassTwo" class="control-label" style="color: #000000"> Repetir Password </label>
										<div class="controls">
											<input type="password" placeHolder="Repetir Password" id="userPassTwo" class="input-xlarge focused">
										</div>
									</div>
								  
								 
								  <div class="form-actions">
									<button class="btn btn-primary green" type="submit" onClick="signUp()">Registar</button>
									<button class="btn red" onClick="clearFields()">Cancelar</button>
								  </div>
								  
								</fieldset>
							</div>

						</div><!--/span-->
					
					</div>
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
									<input type="text" placeHolder="Código de Utilizador" id="modalUserCodUtil" class="input-xlarge focused">
								</div>
								<label for="modalUserUtil" class="control-label" style="color: #000000"> Nome </label>
								<div class="controls">
									<input type="text" placeHolder="Nome" id="modalUserUtil" class="input-xlarge focused">
								</div>
							</div>
							
							
							<div class="control-group" style="color: #000000">
								<label for="modalUserGroup" class="control-label"> Grupo </label>
								<div class="controls">
								  <select id="modalUserGroup" name="modalUserGroup">
									'.$options.'
								  </select>
								</div>
							</div>
							
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
				
				function signUp(){
					var ident 		= $("#userID").val();
					var codutil 	= $("#userCodUtil").val();
					var util 		= $("#userUtil").val();
					var passOne 	= $("#userPassOne").val();
					var passTwo 	= $("#userPassTwo").val();
					var group 		= $("#userGroup").val();
					
					
					if(ident != "" && codutil != "" && util != "" && passOne != "" && passTwo != "" && group != ""){
						if(passOne == passTwo){
								//Registar os novos utilizadores
								$.getJSON("modules/secure/MasterPiece.php",{"identifier":ident,"user":codutil,"name":util,"pass":passOne,"group":group, "mode":"INSERT"},function(data){
									if(data == 1){
										alert("Utilizador adicionado com sucesso.");
										updateTable();
									}
									else if(data == 0)
									{
										alert("O Id ou o Código de Utilizador já foi escolhido e não está disponível. Por favor, tente outro Id e/ou Código de Utilizador.");
									}
									else{
										alert("Não é possível neste momento adicionar utilizadores na base de dados. Tente mais tarde.");
									}
								});
						} else {
							alert("As passwords são diferentes!");
						}
					} else {
							alert("Existem campos por preencher!");
					}
				}

				function edit(){
					var ident 		= $("#modalUserID").val();
					var codutil 	= $("#modalUserCodUtil").val();
					var util 		= $("#modalUserUtil").val();
					var passAntiga 	= $("#modalUserPassAntiga").val();
					var passOne 	= $("#modalUserPassOne").val();
					var passTwo 	= $("#modalUserPassTwo").val();
					var group 		= $("#modalUserGroup").val();
					
					
					
					if(passAntiga == "" && passOne == "" && passTwo =="")
					{
						if(ident != "" && codutil != "" && util != "" && group != "" ){
							//Registar os novos utilizadores
							$.getJSON("modules/secure/MasterPiece.php",{"identifier":ident,"user":codutil,"name":util,"group":group,"mode":"UPDATE"},function(data){
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
						} else {
								alert("Existem campos por preencher!");
						}
					}else{
					
						if(passOne == passTwo && passOne.length > 0){
							//verificar pass antiga
							$.getJSON("modules/secure/MasterPiece.php",{"identifier":ident, "user": codutil, "pass":passAntiga, "mode":"CONFIRMAPWD"},function(data){
								if(data == 1)
								{
									//alterar password
									$.getJSON("modules/secure/MasterPiece.php",{"identifier":ident,"user":codutil,"name":util,"group":group,"group":group,"pass":passOne,"mode":"UPDATEALL"},function(data)
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
					
					$.getJSON("modules/secure/MasterPiece.php",{"mode":"GETUSERS"},function(data){
						
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
}
	
?>



