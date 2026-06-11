<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if(isset($_SESSION['user'])){

	$clientes = array(); 
	
	$mode = $_GET['mode'];

	switch($mode){
		case 'comparativoAnoMes':
						
			$anoini = $_GET['anoini'];
			$anofim = $_GET['anofim'];
			
			$sql_query = "	select ca.anodoc, 
								sum(decode(to_char(ca.datadoc,'MM'),'01',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) janeiro, 
								sum(decode(to_char(ca.datadoc,'MM'),'02',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) fevereiro, 
								sum(decode(to_char(ca.datadoc,'MM'),'03',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) marco, 
								sum(decode(to_char(ca.datadoc,'MM'),'04',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) abril, 
								sum(decode(to_char(ca.datadoc,'MM'),'05',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) maio, 
								sum(decode(to_char(ca.datadoc,'MM'),'06',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) junho, 
								sum(decode(to_char(ca.datadoc,'MM'),'07',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) julho, 
								sum(decode(to_char(ca.datadoc,'MM'),'08',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) agosto, 
								sum(decode(to_char(ca.datadoc,'MM'),'09',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) setembro, 
								sum(decode(to_char(ca.datadoc,'MM'),'10',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) outubro, 
								sum(decode(to_char(ca.datadoc,'MM'),'11',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) novembro, 
								sum(decode(to_char(ca.datadoc,'MM'),'12',nvl(ca.total,0)*decode(ca.tipodoc,'DFR',-1,1),0)) dezembro 
							from compras_cpcab ca
							where ca.codemp = ".$_SESSION['codemp']." 
								and (to_char(ca.datadoc,'yyyy') = ".$anoini." or to_char(ca.datadoc,'yyyy') = ".$anofim." )
							group by ca.anodoc
							order by ca.anodoc desc";
			
			//echo $sql_query; 
			
			$rs=$db->execute($sql_query);
			if($rs){
				$counter 		= 0;
				$series 		= array();
				$datasetData	= array();
				
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$datasets = "null";
					else{
						$series[$counter] = $row[0];
						
						$tempDataSet 	 = array();
						$tempDataSet[0]  = $row[1];
						$tempDataSet[1]  = str_replace(',','.',$row[2]);
						$tempDataSet[2]  = str_replace(',','.',$row[3]);
						$tempDataSet[3]  = str_replace(',','.',$row[4]);
						$tempDataSet[4]  = str_replace(',','.',$row[5]);
						$tempDataSet[5]  = str_replace(',','.',$row[6]);
						$tempDataSet[6]  = str_replace(',','.',$row[7]);
						$tempDataSet[7]  = str_replace(',','.',$row[8]);
						$tempDataSet[8]  = str_replace(',','.',$row[9]);
						$tempDataSet[9]  = str_replace(',','.',$row[10]);
						$tempDataSet[10] = str_replace(',','.',$row[11]);
						$tempDataSet[11] = str_replace(',','.',$row[12]);
						
						
						$datasetData[$counter]	= $tempDataSet;
						$counter = $counter +1;
					}
				}
				
				$meses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
				
				$finalDataSets = array();

				for($i = 0; $i < sizeof($datasetData); $i++){
					$finalDataSets[$i] = getSomeColorsManipulated($datasetData[$i], $series[$i], $i); 
				}
				$datasets = array("labels" => ($meses), "datasets" => ($finalDataSets));
				 
				echo json_encode($datasets);	
			} else {
				echo 'Error: '.$db->ErrorMsg();
			}
			
			break;
			
			
			case 'anosCompras': 
				$sql = "select to_char(datadoc, 'yyyy')
							from compras_cpcab
							where codemp = ".$_SESSION['codemp']."
							group by to_char(datadoc, 'yyyy')
							order by to_char(datadoc, 'yyyy') desc";
			
			
				$rs=$db->execute($sql);
				if($rs){
					$counter 		= 0;
					$datasetData	= array();
					
					While($row = $rs->FetchRow()){
						if (is_null($row[0]))
							$datasets = "null";
						else{
							$tempDataSet 	 = array();
							$tempDataSet[0]  = $row[0];
							
							$datasetData[$counter]	= $tempDataSet;
							$counter = $counter +1;
						}
					}
					
					
					echo json_encode($datasetData);	
				}
			
			break;
	}  
}
else{
	header("Location: ../../index.php");
}



function getSomeColorsManipulated($dataset, $serie, $manipulacao){
	
	if($manipulacao == 0){
		$fillColorR 			= 250;
		$fillColorG 			= 0;
		$fillColorB 			= 0;
	} else if($manipulacao == 1) {
		$fillColorR 			= 0;
		$fillColorG 			= 0;
		$fillColorB 			= 250;
	} else{
		$fillColorR 			= rand(101,244);
		$fillColorG 			= rand(101,244);
		$fillColorB 			= rand(101,244);
	}
	
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
	
	return array("label" => $serie, "fillColor" => $fillColor, "strokeColor" => $strokeColor, "pointColor" => $pointColor, "pointHighlightFill"=> $pointHighlightFill, "data" => ($dataset));
}


?>