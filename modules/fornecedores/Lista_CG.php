<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if(isset($_SESSION['user'])){

//$artigos["Content"] = "";
 
	$artigos = array(); 
	
	$expr = $_GET['exp'];
	$data = $_GET['data'];


		$sqlquery = "
						SELECT codconta FROM CTB_PCG M 
						WHERE M.CODEMP = ".$_SESSION['codemp']."
              			and m.anoplano = " .$data. "
						and m.terceiro = 'F'
						and m.tipoconta = 'V'
              			and (upper(codconta) like upper('%".$expr."%'))
						order by codconta
						";

			//echo $sqlquery;
		 
			$rs=$db->execute($sqlquery);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
					if(is_null($row[0]))
						$artigos[0] = [];
					else{
						$artigos[$counter] = $row[0];
						$counter = $counter +1;
					}
				}
			}
			echo json_encode($artigos);
			
		
}
else{
	header("Location: ../../index.php");
}
?>