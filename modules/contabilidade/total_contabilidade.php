<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$clientes 	 = array();

	$datafim 	 = $_GET['datafim'];
	$cg 		 = $_GET['cg'];


	$sql_query ="SELECT SUM(vld - vlc) AS total
	FROM ctb_mov 
	WHERE codemp = '". $_SESSION['codemp'] ."'
 	 AND CG = '" . $cg . "'
  	 AND datamov <= TO_DATE('". $datafim ."', 'DD.MM.YYYY')

	";

	$rs=$db->execute($sql_query);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$clientes = null;
			else{
				$temp = array();
				$temp['total']	= $row[0];
				
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