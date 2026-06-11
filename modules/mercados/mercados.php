<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

$mercados = array();
$mercados["Content"] = "";


	//$rs=$db->execute("select NOME from STK_MERCADO where codemp=".$_SESSION['codemp']);

	$rs=$db->execute("SELECT DISTINCT(ME.CODIGO),ME.NOME FROM STK_MERCADO ME, VENDAS_VDCAB VD WHERE ME.CODEMP = ".$_SESSION['codemp']." AND VD.CODEMP = ME.CODEMP AND VD.MERCADO = ME.CODIGO");

	if($rs){
		
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$mercados["Content"] .= "<li ><a class='submenu'><i class='icon-file-alt'></i><span class='hidden-tablet'>Sem Mercados</span></a></li>";
			else
				$mercados["Content"] .= "<li onclick='openMarket(".$row[0].")' ><a class='submenu'><i class='icon-file-alt'></i><span class='hidden-tablet'>".$row[1]."</span></a></li>";
			
		}
	}
	echo $mercados["Content"];

}
else{
	header("Location: ../../index.php");
}
?>