<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

$vendas 	 = array();

$loja 	 = $_GET["loja"];


if ($loja == 'todos') {

	try{
		$sql_query = "Select dl.Nome 
					  from dash_lojas dl, dash_acessos ac 
					  where dl.codemp 	=".$_SESSION['codemp']."
					  and ac.codemp   	= dl.codemp 
					  and ac.idloja  	= dl.id 
					  and ac.idgrupo  	= ".$_SESSION['grupo']."";
		
		
	} catch (Exception $e) {
		echo $e;
	}
	
} 
else{
	try{
	$sql_query = "Select dl.nome
					  from dash_acessos ac, dash_lojas dl 
					  where ac.codemp=".$_SESSION['codemp']."
					  and ac.idgrupo=".$_SESSION['grupo']."
					  and dl.id=".$loja."
					  
					  group by dl.nome
					  ORDER BY dl.nome";
		
		
	} catch (Exception $e) {
		echo $e;
	}
	
}

//echo $sql_query;

	$rs=$db->execute($sql_query);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$vendas = null;
			else{
				$temp = array();
				$temp['loja'] 	  = ($row[0]);
				$vendas[$counter] = $temp;
				$counter = $counter +1;
			}
		}
	}
	
	echo json_encode($vendas);

}
else{
	header("Location: ../../index.php");
}

?>