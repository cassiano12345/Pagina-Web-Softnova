<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

	$cliente = $_GET['cliente'];
	$clientes 	 = array();
	
	$sql_clientes = "
    SELECT nome, nrdiario from ctb_diarios where codemp=".$_SESSION['codemp']." and upper(nome) like upper('%".$cliente."%')";

	$rs = $db->execute($sql_clientes);

	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
				$clientes[$counter] = ($row[0]).' | '.$row[1];
				$counter = $counter +1;
		}
	}

	echo json_encode($clientes);

} else{
	header("Location: ../../index.php");
}
	
?>