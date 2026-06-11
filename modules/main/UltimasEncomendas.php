<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

	$totalRequisicoes = 0;
	$rs=$db->execute("select nvl(sum(total - IVA01VAL - IVA02VAL - iva03val - iva04val),0) 
						from vendas_eccab
						where codemp  = ".$_SESSION['codemp']."
						  and tipodoc = 'ECL' 
						  and to_char(datadoc,'yyyymmdd') = to_char(sysdate, 'yyyymmdd')");
	if($rs){
		While($row = $rs->FetchRow()){
		if (is_null($row[0]))
			$totalRequisicoes = 0;
		else
			$totalRequisicoes = str_replace(',','.',$row[0]);
		}
	}
	echo $totalRequisicoes;
}
else{
	header("Location: ../../index.php");
}
?>