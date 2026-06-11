<?php
//Encomendas de clientes 

include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

	$totalEncomendasFornecedores = 0;
	$rs=$db->execute("select sum(TOTAL)?");
	if($rs){
		While($row = $rs->FetchRow()){
		if (is_null($row[0]))
			$totalEncomendasFornecedores = 0;
		else
			$totalEncomendasFornecedores = $row[0];
		}
	}
	echo $totalEncomendasFornecedores;
}
else{
	header("Location: ../../index.php");
}
?>