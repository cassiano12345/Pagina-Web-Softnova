<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$clientes 	 = array();

	$dataini 	 = $_GET['dataini'];
	$datafim 	 = $_GET['datafim'];
	$cg 		 = $_GET['cg'];

	$sql_query = "";

	$sql_query ="
    Select datamov, nrlancto, cg, cx, descmovcnt, nrdocofic, serie, vld, vlc, grupo
        from CTB_MOV
        WHERE CodEmp='".$_SESSION['codemp']."' and 
        Plano = 'G' and 
        nrdiario = '" .$cg. "' and 
        datamov between TO_DATE('".$dataini."', 'DD.MM.YYYY') AND TO_DATE('".$datafim."', 'DD.MM.YYYY') 
    ORDER BY datamov,MesMov, nrdiario,nrlancto,nrlinha ";

	$rs=$db->execute($sql_query);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$clientes = null;
			else{
				$temp = array();
				$temp['datamov']	= $row[0];
				$temp['nrlancto'] 		= $row[1];
				$temp['cg']	= $row[2];
				$temp['cx'] 	= $row[3];
				$temp['descmovcnt']	= $row[4];
				$temp['nrdocofic']	= $row[5];
				$temp['serie']	= $row[6];
				$temp['vld']	= $row[7];
				$temp['vlc']	= $row[8];
				$temp['grupo']	= $row[9];
				
				$clientes[$counter] = $temp;
				$counter = $counter +1;
			}
		}
	}
	
	//echo("<script>console.log('PHP: " . $rs . "');</script>");
	echo json_encode($clientes);

}else{
	header("Location: ../../index.php");
}

?>