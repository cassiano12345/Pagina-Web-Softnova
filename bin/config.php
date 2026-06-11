<?php

session_start();

$db = NewADOConnection('oci8');
$db->Connect("192.168.0.204/XE","soft","soft");
$userOCI = "soft";
$passOCI = "soft";
$connOCI = "192.168.0.204/XE";

//SE $_GET['codemp'] FOR TRUE VAI BUSCAR O VALOR AO URL, SENÃO $EMPRESA_BASE RETORNA FALSE PARA O MÓDULO LOGIN
if(isset($_GET['codemp'])){
	$EMPRESA_BASE = htmlspecialchars($_GET['codemp']);
}
else{
	unset ($EMPRESA_BASE);
}

?>
