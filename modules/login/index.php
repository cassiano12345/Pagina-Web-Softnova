<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

//SE $EMPRESA_BASE FOR TRUE, FAZ PESQUISA PELO CODEMP QUE VEM NO URL
if (isset($EMPRESA_BASE)){
	$rs=$db->execute("Select CodEmp,Nome From soft_empresa where codemp in (".$EMPRESA_BASE.") and nvl(situacao,'1') = '0' order by codemp asc");
}
else{
	//TODAS AS EMPRESAS: 
	$rs=$db->execute("Select CodEmp,Nome From soft_empresa where codemp > 0 and nvl(situacao,'1') = '0' order by codemp asc");
}


$nomeEmp="";
$FirstName="";
$flag=0;
if($rs){
	While($row = $rs->FetchRow()){
		$nomeEmp.="<option value='".$row[1]."'>".$row[1]."</option>";
		if ($flag==0){
			$FirstName=$row[1];
			$flag=1;
		}
	}
}
$html='	
	<div class="container-fluid-full background_login" >
		<br>
		<div class="row-fluid">
			<div class="titlesoftnova">
				<div style="background-color:#dddddd; height:72; opacity:0.5;" class="navbar-fixed-top">
					<h1  style="margin-left: 50px;  margin-top:15px; font-size:48px; color:#212121; font-weight: bold;" align="left" ><a href="http://www.softnova.pt">Softnov@ </a> <a style="font-size:22" href="http://www.softnova.pt"> Software Empresarial e Sistemas Lda.</a></h1>
				</div>
			</div>
		</div>
		<div class="row-fluid" id="shake">
		
			<div class="login-box" style="opacity:0.9;" >
				<div class="form-horizontal">
					<p align="center" id="nome_emp" style="color: #555; font-size:22px;">'.$FirstName.'</p>	<hr style="background-color:#EBBB6A;">
				</div>
				<h2 lang="1" class="hdois" style="font-family: Arial, Helvetica, sans-serif;"> Login </h2>
				<form class="form-horizontal" method="post">
					<fieldset>
						<div class="input-prepend" title="Username">
							<span class="add-on"><i class="halflings-icon user"></i></span>
							<input class="input-large span10" name="username" id="username" type="text" lang="1" placeholder="nome de utilizador" value=""/>
						</div>
						
						<div class="input-prepend" title="Password">
							<span class="add-on"><i class="halflings-icon lock"></i></span>
							<input class="input-large span10" name="password" id="password" type="password" lang="1" placeholder="password" value=""/>
						</div>
						<div class="clearfix" ></div>
						<div class="input-prepend" title="Empresa" >
							<span class="add-on"><i class="halflings-icon barcode"></i></span>
							<select  class="input-large span10" onchange="onchangeEmpresa()" id="cb_empresa" >
								'.$nomeEmp.'
							</select>
						</div>
						<div class="clearfix"></div>
						<!--<label class="remember" for="remember"><input type="checkbox" lang="1" id="remember" />Remember me</label>-->

						<div class="button-login">	
							<button type="button" value="Validar" onClick="login();" lang="1" class="btn btn-primary" >Validar</button>
						</div>
						<div class="clearfix"></div>

				</form>
				<br>
				<div id="error_div" align="center"></div>

				
				<hr>
				<div class="rodsoftnova"><!--rodape-->
				<p align="center" lang="1"> Desenvolvido por <i>Softnova</i>, Software Empresarial e Sistemas, Lda </p>

			</div><!--/span-->
			
			
			</div><!--/row-->
			
		</div>
	</div><!--/.fluid-container-->
	</div><!--/fluid-row-->';
	
$script="<script type='text/javascript'>

		$(document).ready(function() {
			
		});

		function onchangeEmpresa(){
			$('#nome_emp').text('');
			$('#nome_emp').text($('#cb_empresa option:selected').text());
			$('#error_div').html('');
		}


		function login(){
			var user=$('#username').val();
			var pass=$('#password').val();
			var emp =$('#cb_empresa').val();
			$.get('modules/login/validar_login.php', {empresa: emp, password:pass, user:user}, function(data){
				if(data==1){
					window.location.replace('index.php?');
				}else{
					$('#error_div').html('<div class=\"alert alert-error\" role=\"alert\" style=\"color:red;\">Login inválido!</div>');
				}
			});
		}

</script>";

echo $html.$script;
?>