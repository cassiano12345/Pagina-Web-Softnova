 <?php
	include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");



	if(isset($_SESSION['user'])){

		$vendedores = array(); 
		$expr = $_GET['exp'];
	
		$sqlquery = "select NOME, NRVD 
					from ctb_vendedor
					where codemp = ".$_SESSION['codemp']."
						and upper(NOME) like upper('%".$expr."%')";

		//echo $sqlquery;
		
		$rs=$db->execute($sqlquery);
		if($rs){
			$counter = 0;
			While($row = $rs->FetchRow()){
				if(is_null($row[0]))
					$vendedores[0] = [];
				else{
					$vendedores[$counter] = ($row[0]." | ".$row[1]);
					$counter = $counter +1;
				}
			}
		}
		echo json_encode($vendedores);
		
	}else{
		header("Location: ../../index.php");
	}

?>