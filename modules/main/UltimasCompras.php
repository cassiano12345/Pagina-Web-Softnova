<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

	$totalVendasSemanal = 0;
	$today = date("Y/m/d");
			
	$sql_compras = "select sum(nvl((total - IVA01VAL - IVA02VAL - iva03val - iva04val),0)*decode(tipodoc,'DFR',-1,1))
						from compras_cpcab cp
						where codemp = ".$_SESSION['codemp']." 
						 and to_char(cp.datadoc, 'yyyymmddHH24MISS') between 
									  to_char(to_date('".$today." 00:00:00','yyyy/mm/dd HH24:MI:SS'), 'yyyymmddHH24MISS') 
									  and
									  to_char(to_date('".$today." 23:59:59','yyyy/mm/dd HH24:MI:SS'), 'yyyymmddHH24MISS') ";
	
	$rs=$db->execute($sql_compras);
	if($rs){
		While($row = $rs->FetchRow()){
		if (is_null($row[0]))
			$totalVendasSemanal = 0;
		else
			$totalVendasSemanal = str_replace(',','.',$row[0]);
		}
	}
	echo $totalVendasSemanal;
}
else{
	header("Location: ../../index.php");
}
?>