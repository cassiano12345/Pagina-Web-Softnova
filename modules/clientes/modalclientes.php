<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

$encomendas 	 = array();

$dataini 	 = $_GET['dataini'];
$datafim 	 = $_GET['datafim'];
$cg 		 = $_GET['cg'];
$codig		 = $_GET['codigo'];
$nrdocofic 	 = $_GET['nrdocofic'];


	$sql_query = "";

	$sql_query ="select datadoc, dvc, nrdiario, nrlancto, descmovcnt, nrdocofic, serie, vld, vlc, grupo 
	from CTB_MOV where codemp =".$_SESSION['codemp']."
	AND NRDOCOFIC = '".$nrdocofic."'
	AND DATAMOV BETWEEN TO_DATE('".$dataini."', 'DD.MM.YYYY') AND TO_DATE('".$datafim."', 'DD.MM.YYYY')
	AND CG =" .$cg. "
	AND CX =" .$codig. "
	";

	$rs=$db->execute($sql_query);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$encomendas = null;
			else{
				$temp = array();
				
				$temp['datadoc']	= $row[0];
				$temp['dvc'] 		= $row[1];
				$temp['nrdiario']	= $row[2];
				$temp['nrlancto'] 	= $row[3];
				$temp['descmovcnt']	= $row[4];
				$temp['nrdocofic']	= $row[5];
				$temp['serie']	= $row[6];
				$temp['vld']	= $row[7];
				$temp['vlc']	= $row[8];
				$temp['grupo']	= $row[9];
				
				$encomendas[$counter] = $temp;
				$counter = $counter +1;
			}
		}
	}
	
	
	//echo("<script>console.log('PHP: " . $rs . "');</script>");
	echo json_encode($encomendas);
}
else{
	header("Location: ../../index.php");
}

?>