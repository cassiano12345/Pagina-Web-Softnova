<?php
/*::TODO::
	:: ADICIONAR A LOJA NA QUERY

*/


include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

$mercados = array();
$dataini 		= $_GET['dataini'];
$datafim 		= $_GET['datafim'];
$tipoPesquisa 	= $_GET['tipoPesquisa'];
$expressao 		= $_GET['exp'];

$loja 			= $_GET['loja'];

$sql_lojas_td   = '';
$count   = 0;

if ($loja == 'todos') {

	//$sql_lojas = 'Select dl.idloja from dash_lojas dl where dl.idgrupo='.$_SESSION["grupo"].'';
	try{
		$rsX=$db->execute('Select ac.idloja from dash_acessos ac where ac.codemp='.$_SESSION["codemp"].' and ac.idgrupo='.$_SESSION["grupo"].'');
		if($rsX){
			$sql_lojas_td  .= ' (';
			$count = 0;
			While($rowX = $rsX->FetchRow()){

				$rsP=$db->execute('Select dl.armazem from dash_lojas dl where dl.codemp = '.$_SESSION["codemp"].' and dl.id='.$rowX[0].'');
				if($rsP){
					While($rowP = $rsP->FetchRow()){
						if ($count == 0){
							$sql_lojas_td .= '(IT.CODARM = \''.$rowP[0].'\')';	
						} else {
							$sql_lojas_td .= ' OR (IT.CODARM = \''.$rowP[0].'\')';	
						}
						$count = $count + 1;
					}
				}
			
			}
			$sql_lojas_td  .= ')';
		}
	} catch (Exception $e) {
		echo $e;
	}
	

		
} else {
	try{
		//echo $loja;
		$rsP=$db->execute('Select dl.armazem from dash_lojas dl where dl.codemp='.$_SESSION["grupo"].' and dl.id='.$loja.'');
		if($rsP){
			While($rowP = $rsP->FetchRow()){
				$sql_lojas_td .= ' (IT.CODARM = \''.$rowP[0].'\') ';
			}
		}	
	} catch (Exception $e) {
		echo $e;
	}
}


	$sql_query = "SELECT CP.CODUTIL1,CP.TIPODOC,CP.SERIE,CP.NROFICIAL,CP.DATADOC,CP.NRFORNECEDOR,CP.NOME,CP.MERCADO,CP.CONDPAG,
								SUM(IT.QTD),IT.CODARM,IT.CODIGO,IT.DESART,IT.PVN
								FROM COMPRAS_CPCAB CP, COMPRAS_CPITEM IT
								WHERE CP.CODEMP    = ".$_SESSION['codemp']."
								AND   IT.CODEMP    = CP.CODEMP
								AND   IT.TIPODOC   = CP.TIPODOC
								AND   IT.SERIE     = CP.SERIE
								AND   IT.NROFICIAL = CP.NROFICIAL
								AND   TO_CHAR(CP.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."','dd/mm/yyyy'),'yyyymmdd') and  TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') ";
								
	$sql_queryFinal = " GROUP BY CP.CODUTIL1, CP.TIPODOC, CP.SERIE, CP.NROFICIAL, CP.DATADOC, CP.NRFORNECEDOR, CP.NOME, CP.MERCADO, CP.CONDPAG, IT.CODARM, IT.CODIGO,IT.DESART,IT.PVN 
							ORDER BY CP.DATADOC DESC, CP.NROFICIAL";

	$sql_clause = '';
 
 	if ($tipoPesquisa == 'artigo'){
		$sql_clause = " AND   (upper(IT.CODIGO) like upper('%".$expressao."%') or upper(IT.DESART) like upper('%".$expressao."%')) ";
	} else if ($tipoPesquisa == 'fornecedor'){
		$sql_clause = " AND   (upper(CP.NOME) like upper('%".$expressao."%')) ";
	} else if ($tipoPesquisa == 'todos'){
		$sql_clause = " AND   (upper(IT.CODIGO) like upper('%".$expressao."%') or upper(IT.DESART) like upper('%".$expressao."%') OR  upper(CP.NOME) like upper('%".$expressao."%')) ";
	}	
	
	$sqlFinal = '';
	$sqlFinal =  "".$sql_query." AND ".$sql_lojas_td.$sql_clause.$sql_queryFinal;
	
	//echo $sqlFinal;
	
	$rs=$db->execute($sqlFinal);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$mercados["Content"] = "";
			else{
				
				$temp = array();
				$temp['codutil'] 		= $row[0];
				$temp['tipodoc'] 		= $row[1];
				$temp['serie'] 			= $row[2];
				$temp['nroficial'] 		= $row[3];
				$temp['datadoc'] 		= $row[4];
				$temp['nrfornecedor'] 	= $row[5];
				$temp['nome'] 			= ($row[6]);
				$temp['mercado'] 		= $row[7];
				/*$temp['condpag'] = $row[8];*/
				$temp['qtd'] 			= str_replace(',','.',$row[9]);
				$temp['codarm'] 		= $row[10];
				$temp['codigo'] 		= $row[11];
				$temp['desart'] 		= ($row[12]);
				$temp['pvn'] 			= str_replace(',','.',$row[13]);

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