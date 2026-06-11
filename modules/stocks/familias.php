<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$mercados = array();
	$aprocurar = $_GET['aprocurar'];
	//$aprocurar = 'GF';

	$rs=$db->execute("select fa.nome, count(ar.nome)
						from stk_artigos ar, stk_familias fa
						where 
							ar.codemp = ".$_SESSION["codemp"]." and
							fa.codemp = ar.codemp and
							fa.tabela = '".$aprocurar."'
						group by fa.nome 
						order by fa.nome");
	
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$mercados = null;
			else{
				$temp = array();
				$temp['label'] = ($row[0]);
				$temp['data']  = str_replace(',','.',round($row[1]));
				
				$mercados[$counter] = $temp;
				$counter = $counter +1;
			}
		}
	}
	
	echo json_encode($mercados);

}
else{
	header("Location: ../../index.php");
}

?>