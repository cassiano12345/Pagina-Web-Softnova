<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if(isset($_SESSION['user'])){
 
	$vendas = array(); 
	
	$util  = $_GET['util'];
	
	switch($util){
		case 'decimaispv':
			$sqlquery = 'Select decimaispv from soft_configura where codemp = '+$_SESSION['codemp']+' and codutil = '+$_SESSION['codutil'];
	
			$rs=$db->execute($sqlquery);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$vendas["Content"] = "null";
					else{
						$temp = array();
						$temp['decimaispv'] = $row[0];
						$vendas[$counter] = $temp;
						$counter = $counter +1;
					}
				}
			}
			echo json_encode($vendas);
		break;
	}
	
}
else{
	header("Location: ../../index.php");
}
?>