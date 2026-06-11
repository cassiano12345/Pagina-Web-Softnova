<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if(isset($_SESSION['user'])){

	$clientes = array(); 
	
	$mode = $_GET['mode'];

	switch($mode){
		case 'topfive':
		
			$when = $_GET['desde'];
		
			if($when == 'sempre'){
				$sqlquery = "select * 
								from (select nrcliente, nome, sum(nvl(total,0)- nvl(iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)), codmoeda
										  from vendas_vdcab 
										  where codemp = ".$_SESSION['codemp']."
												  and tipodoc <> 'DCL' 
												  and tipodoc <> 'FSC' 
												  and tipodoc <> 'NCC' 
												  and nvl(situacao,'*') <> 'A' 
										  group by nrcliente, nome, codmoeda
										  order by sum(total) desc
									  )
								where rownum <=5";
			} else {
				$sqlquery = "select * 
								from (select nrcliente, nome, sum(nvl(total,0)- nvl(iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)), codmoeda
									  from vendas_vdcab 
									  where codemp = ".$_SESSION['codemp']."
											  and tipodoc <> 'DCL' 
											  and tipodoc <> 'FSC' 
											  and tipodoc <> 'NCC' 
											  and anodoc = ".$when."
											  and nvl(situacao,'*') <> 'A'
									  group by nrcliente, nome, codmoeda
									  order by sum(total) desc
									)
								where rownum <=5";
			}
			
			
				//echo $sqlquery;
			 
				$rs=$db->execute($sqlquery);
				if($rs){
					$counter = 0;
					While($row = $rs->FetchRow()){
						if(is_null($row[0]))
							$clientes[0] = [];
						else{
							$temp = array();
							$temp['nrcl'] 		= $row[0];
							$temp['nome'] 		= ($row[1]);
							$temp['montante'] 	= str_replace(',','.',$row[2]);
							$temp['moeda'] 		= $row[3];
							
							$clientes[$counter] = $temp;
							$counter = $counter +1;
						}
					}
				}
				echo json_encode($clientes);
			
			break;
		
		case 'topNovosClientes':
		
			$sqlquery = "select * from (
							  select nrcl, nome, data_reg 
							  from ctb_cl 
							  where codemp = ".$_SESSION['codemp']."
							  order by data_reg desc
						) where rownum <= 5";

			//echo $sqlquery;
		 
			$rs=$db->execute($sqlquery);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
					if(is_null($row[0]))
						$clientes[0] = [];
					else{
						$temp = array();
						$temp['nrcl'] 		= $row[0];
						$temp['nome'] 		= ($row[1]);
						
						$dataUnt = $row[2];
						$dataReg = explode("-",$dataUnt);
						$datalabel = $dataReg[2].'-'.$dataReg[1].'-'.$dataReg[0];
						
						$temp['datareg'] 	= $datalabel;
						
						$clientes[$counter] = $temp;
						$counter = $counter +1;
					}
				}
			}
			echo json_encode($clientes);
		
			break;
			
		case 'vendasCliente':
			
			$nrcl = $_GET['nrcl'];
			
			$sql_query = "select * from (
								select sum(nvl(total,0)- nvl(iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)), datadoc
									from vendas_vdcab
									where codemp = ".$_SESSION['codemp']."
									and tipodoc <> 'DCL' 
									and tipodoc <> 'FSC' 
									and tipodoc <> 'NCC' 
									and nvl(situacao,'*') <> 'A'
									and nrcliente = ".$nrcl."							
									group by datadoc
									order by datadoc desc) where rownum <= 10";
			
			$rs=$db->execute($sql_query);
			if($rs){
				$counter 		= 0;
				$labels 		= array();
				$datasetData	= array();
				
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$datasets = "null";
					else{
						$dataVenda = $row[1];
						$dataPartida = explode("-",$dataVenda);
						$datalabel = $dataPartida[2].'-'.$dataPartida[1].'-'.$dataPartida[0];
						$labels[$counter] = $datalabel;
						
						$datasetData[$counter]	= $row[0];
						$counter = $counter +1;
					}
				}
				
				$fillColorR 			= rand(101,244);
				$fillColorG 			= rand(101,244);
				$fillColorB 			= rand(101,244);
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
				
				$myDataset = array(array("fillColor" => $fillColor, "strokeColor" => $strokeColor, "pointColor" => $pointColor, "pointHighlightFill" => $pointHighlightFill, "data" => ($datasetData)));
				$datasets = array("labels" => ($labels), "datasets" => ($myDataset));
				 
				echo json_encode($datasets);	
			}
			
			break;
		
		case 'vendasMensaisCliente':
			$nrcl = $_GET['nrcl'];
						
			$sql_query = "select * FROM 
							(select ca.anodoc, 
								sum(decode(to_char(ca.datadoc,'MM'),'01',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) janeiro, 
								sum(decode(to_char(ca.datadoc,'MM'),'02',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) fevereiro, 
								sum(decode(to_char(ca.datadoc,'MM'),'03',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) marco, 
								sum(decode(to_char(ca.datadoc,'MM'),'04',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) abril, 
								sum(decode(to_char(ca.datadoc,'MM'),'05',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) maio, 
								sum(decode(to_char(ca.datadoc,'MM'),'06',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) junho, 
								sum(decode(to_char(ca.datadoc,'MM'),'07',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) julho, 
								sum(decode(to_char(ca.datadoc,'MM'),'08',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) agosto, 
								sum(decode(to_char(ca.datadoc,'MM'),'09',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) setembro, 
								sum(decode(to_char(ca.datadoc,'MM'),'10',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) outubro, 
								sum(decode(to_char(ca.datadoc,'MM'),'11',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) novembro, 
								sum(decode(to_char(ca.datadoc,'MM'),'12',nvl((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0)),0),0)) dezembro 
							from vendas_vdcab ca 
							where ca.codemp = ".$_SESSION['codemp']."
							 and ca.nrcliente = ".$nrcl."
							 and tipodoc <> 'DCL' 
							 and tipodoc <> 'FSC' 
							 and tipodoc <> 'NCC'  
							 and nvl(situacao,'*') <> 'A'
							 group by ca.anodoc
							 order by anodoc desc)where rownum <=5";
			
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
						$tempDataSet[0]  = str_replace(',','.',$row[1]);
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
				
				
				$meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
				
				$finalDataSets = array();

				for($i = 0; $i< sizeof($datasetData); $i++){
					$finalDataSets[$i] = getSomeColors($datasetData[$i], $series[$i]); 
					//echo "\n".json_encode($finalDataSets[$i]);
				}
				
				//echo $finalDataSets;
			
				$datasets = array("labels" => ($meses), "datasets" => ($finalDataSets));
				 
				//echo json_encode($finalDataSets[0]);
				 
				echo json_encode($datasets);	
			} else {
				echo 'Error: '.$db->ErrorMsg();
			}
			
			break;
			
	}  
}
else{
	header("Location: ../../index.php");
}



function getSomeColors($dataset, $serie){
	$fillColorR 			= rand(101,244);
	$fillColorG 			= rand(101,244);
	$fillColorB 			= rand(101,244);
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


?>