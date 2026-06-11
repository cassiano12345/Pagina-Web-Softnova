<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if(isset($_SESSION['user'])){

//$vendas["Content"] = "";
 
	$vendas = array(); 
	
	$mode 	= $_GET['mode'];
	$codart = $_GET['codart'];

	if($mode=='gf'){

		$codgf = $_GET['codgf'];

					
		$sqlquery = "select fa.nome 
				from stk_familias fa, stk_artigos ar
				where fa.codemp = ".$_SESSION['codemp']."
				and ar.codemp = fa.codemp
				and fa.tabela = 'GF'
				and fa.codigo = '".$codgf."'
				and ar.codart = '".$codart."'";

		//echo $sqlquery;

		$rs=$db->execute($sqlquery);
		if($rs){
			$counter = 0;
			While($row = $rs->FetchRow()){
				if (is_null($row[0]))
					$vendas["Content"] = "null";
				else{
					$temp = array();			
					$temp['gf'] = ($row[0]);
					$vendas[$counter] = $temp;
					$counter = $counter +1;
				}
			}
		}
		echo json_encode($vendas);
		
	} else if($mode=='fa'){

		$codgf = $_GET['codgf'];
		$codfa = $_GET['codfa'];

		$sqlquery = "select fa.nome 
				from stk_familias fa, stk_artigos ar
				where fa.codemp = ".$_SESSION['codemp']."
				and ar.codemp = fa.codemp
				and fa.tabela = 'FA'
				and fa.codigo = '".$codgf."'
				and fa.codigof = '".$codfa."'
				and ar.codart = '".$codart."'";


		//echo $sqlquery;
	 
		$rs=$db->execute($sqlquery);
		if($rs){
			$counter = 0;
			While($row = $rs->FetchRow()){
				if (is_null($row[0]))
					$vendas["Content"] = "null";
				else{
					$temp = array();
					$temp['fa'] 		= ($row[0]);
					$vendas[$counter] = $temp;
					$counter = $counter +1;
				}
			}
		}
		echo json_encode($vendas);
	} else if($mode == 'sf') {
		
		$codgf = $_GET['codgf'];
		$codfa = $_GET['codfa'];
		$codsf = $_GET['codsf'];
		

		$sqlquery = "select fa.nome 
				from stk_familias fa, stk_artigos ar
				where fa.codemp = ".$_SESSION['codemp']."
				and ar.codemp = fa.codemp
				and fa.tabela = 'SF'
				and fa.codigo = '".$codgf."'
				and fa.codigof = '".$codfa."'
				and fa.codigos = '".$codsf."'
				and ar.codart = '".$codart."'";

		$rs=$db->execute($sqlquery);
		if($rs){
			$counter = 0;
			While($row = $rs->FetchRow()){
				if (is_null($row[0]))
					$vendas["Content"] = "null";
				else{
					$temp = array();
					$temp['sf'] 		= ($row[0]);
					$vendas[$counter] = $temp;
					$counter = $counter +1;
				}
			}
		}
		echo json_encode($vendas);					
	}
}
else{
	header("Location: ../../index.php");
}
?>