<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if(isset($_SESSION['user'])){

	$fornecedor = $_GET['fornecedor'];
	$fornecedors 	 = array();
	
	$sql_fornecedors = "Select nome, nrfr from ctb_fr where codemp=".$_SESSION['codemp']." and upper(nome) like upper('%".$fornecedor."%') and activo='S'";

	$rs = $db->execute($sql_fornecedors);


	
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
				$fornecedors[$counter]  = $row[0].' | '.$row[1];
				$counter 				= $counter +1;
		}
	}
	
	echo json_encode($fornecedors);

} else{
	header("Location: ../../index.php");
}
	
?>