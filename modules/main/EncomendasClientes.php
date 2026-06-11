<?php
//Encomendas de clientes da ultima semana

include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

	$totalEncomendasClientes = 0;
	$rs=$db->execute("	select sum(TOTAL) 
						from vendas_eccab 
						where codemp  	= ".$_SESSION['codemp']." 
						  and tipodoc 	= 'ECL' 
						  and (situacao = 'E' or situacao = 'P' or situacao = 'C' or situacao = 'D')");
								  
	if($rs){
		While($row = $rs->FetchRow()){
		if (is_null($row[0]))
			$totalEncomendasClientes = 0;
		else
			$totalEncomendasClientes = $row[0];
		}
	}
	echo str_replace(',','.',$totalEncomendasClientes);
}
else{
	header("Location: ../../index.php");
}
?>