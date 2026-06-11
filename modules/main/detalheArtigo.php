<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if(isset($_SESSION['user'])){

//$artigos["Content"] = "";
 
	$artigos = array(); 
	
	$expr = $_GET['exp'];


		$sqlquery = "select distinct ar.nome
						from  stk_artigos   ar
						where ar.codemp  = ".$_SESSION['codemp']."
						  and (upper(ar.codart) like upper('%".$expr."%') or upper(ar.nome) like upper('%".$expr."%'))  
						order by ar.nome";

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