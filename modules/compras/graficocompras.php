<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$vendas 	 = array();

	$dataini 		= $_GET['dataini'];
	$datafim 		= $_GET['datafim'];
	$tipoPesquisa 	= $_GET['tipoPesquisa'];
	$expressao 		= $_GET['exp'];

	$loja 			= $_GET['loja'];

	$sql_query = "";


	$sql_lojas_td   = '';
	$count   = 0;

	if ($loja == 'todos') {

		//$sql_lojas = 'Select dl.idloja from dash_lojas dl where dl.idgrupo='.$_SESSION["grupo"].'';
		try{
			$rsX=$db->execute('Select ac.idloja from dash_acessos ac where ac.codemp = '.$_SESSION["codemp"].' and ac.idgrupo='.$_SESSION["grupo"].'');
			if($rsX){
				$sql_lojas_td  .= ' (';
				$count = 0;
				While($rowX = $rsX->FetchRow()){

					$rsP=$db->execute('Select dl.armazem from dash_lojas dl where dl.id='.$rowX[0].'');
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
			$rsP=$db->execute('Select dl.armazem from dash_lojas dl where dl.codemp = '.$_SESSION["codemp"].' and dl.id='.$loja.'');
			if($rsP){
				While($rowP = $rsP->FetchRow()){
					$sql_lojas_td .= ' (IT.CODARM = \''.$rowP[0].'\') ';
				}
			}	
		} catch (Exception $e) {
			echo $e;
		}
	}



	$sql_query ="select CP.NOME,CP.DATADOC,nvl(CP.TOTAL,0)*decode(cp.tipodoc,'DFR',-1,1)
					FROM COMPRAS_CPCAB CP, COMPRAS_CPITEM IT

					WHERE CP.CODEMP    = ".$_SESSION['codemp']."
					AND   IT.CODEMP    = CP.CODEMP
					AND   IT.TIPODOC   = CP.TIPODOC
					AND   IT.SERIE     = CP.SERIE
					AND   IT.NROFICIAL = CP.NROFICIAL
					AND   TO_CHAR(CP.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."','dd/mm/yyyy'),'yyyymmdd') 
					AND  TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
					AND ((IT.CODARM = '99') OR (IT.CODARM = '01') OR (IT.CODARM = 'EQA')) 
					AND   (upper(IT.CODIGO) like upper('%".$expressao."%') or upper(IT.DESART) like upper('%".$expressao."%') OR upper(CP.NOME) like upper('%".$expressao."%'))";  



	$sql_queryFinal = " GROUP BY CP.NOME,CP.DATADOC, nvl(CP.TOTAL,0)*decode(cp.tipodoc,'DFR',-1,1)
                    ORDER BY 3 DESC, CP.DATADOC DESC";

	$sql_clause = '';

	if ($tipoPesquisa == 'artigo'){
		$sql_clause = " AND   (upper(IT.CODIGO) like upper('%".$expressao."%') or upper(IT.DESART) like upper('%".$expressao."%')) ";
	} else if ($tipoPesquisa == 'fornecedor'){
		$sql_clause = " AND   (upper(CP.NOME) like upper('%".$expressao."%')) ";
	} else if ($tipoPesquisa == 'todos'){
		$sql_clause = " AND   (upper(IT.CODIGO) like upper('%".$expressao."%') or upper(IT.DESART) like upper('%".$expressao."%') OR upper(CP.NOME) like upper('%".$expressao."%')) ";
	}	

	$sqlFinal = '';
	$sqlFinal =  " select * from ( ".$sql_query."and".$sql_lojas_td.$sql_clause.$sql_queryFinal." ) where rownum <= 10";
		

//echo $sqlFinal;
	$rs = $db->Execute($sqlFinal);

	if($rs){
		$labels = array();
		$nrOfDatasets = 0;

		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$nrOfDatasets = "null";
			else{
				$ds[$nrOfDatasets] 		= [$row[2]]; 
				$labels[$nrOfDatasets]  = $row[0];
				$nrOfDatasets += 1;
			}
		}

		$finalDataSets = array();
		for($i = 0; $i< $nrOfDatasets; $i++){
			$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
		}


		$column = "Top 10: ".$dataini . ' a ' . $datafim;
		$mainLabel = [$column];

		$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);
		echo json_encode($datasets);	

	} else {
		echo 'Error: '.$db->ErrorMsg();
	}
}		


function getSomeColors($dataset, $serie){
	$fillColorR 			= getColorR();//rand(101,244);
	$fillColorG 			= getColorG();//rand(101,244);
	$fillColorB 			= getColorB();//rand(101,244);
	$strokeColorR 			= $fillColorR + 10;
	$strokeColorG 			= $fillColorG + 10;
	$strokeColorB 			= $fillColorB + 10;
	$pointColorR 			= $fillColorR - 10;
	$pointColorG 			= $fillColorG - 10;
	$pointColorB 			= $fillColorB - 10;
	$pointHighlightFillR 	= $fillColorR - 20;
	$pointHighlightFillG 	= $fillColorG - 20;
	$pointHighlightFillB 	= $fillColorB - 20;
	
	$fillColor 			= "rgba(".$fillColorR.",".$fillColorG.",".$fillColorB.",0.4)";
	$strokeColor 		= "rgba(".$strokeColorR.",".$strokeColorG.",".$strokeColorB.",0.7)";
	$pointColor 		= "rgba(".$pointColorR.",".$pointColorG.",".$pointColorB.",0.9)";
	$pointHighlightFill = "rgba(".$pointHighlightFillR.",".$pointHighlightFillG.",".$pointHighlightFillB.",0.9)";

	//echo $dataset;	
	return array("label" => $serie, "fillColor" => $fillColor, "strokeColor" => $strokeColor, "pointColor" => $pointColor, "pointHighlightFill" => $pointHighlightFill, "data" => ($dataset));
}


function getColorR(){
	$colorR = rand(101,244);
	return $colorR;
}

function getColorG(){
	$colorG = rand(101,244);
	return $colorG;
}

function getColorB(){
	$colorB = rand(101,244);
	return $colorB;
}


?>
