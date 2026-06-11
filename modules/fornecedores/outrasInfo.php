<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

	$tag = $_GET['tag'];
	
	
	
	switch($tag){
		case 'pais':
			$response = array();
			$pais = $_GET['pais'];
			$sql_pais = "Select Nome from soft_tabelas where Codigo = '".$pais."' and tabela = 'PAI'";
			$rs = $db->execute($sql_pais);
			if($rs){
				While($row = $rs->FetchRow()){
						$temp = array();
						$temp['pais'] 	= ($row[0]);
						$response[0] = $temp;
						break;
				}
			}
			echo json_encode($response);
		break;
		
		case 'contatos':
			$response = array();
			$nrfornecedor = $_GET['nrfr'];
			$sql_contatos = "Select nome, nvl(cargo,'N/A'), nvl(tlm,'N/A'), nvl(email,'N/A'), nvl(obs,'') from ctb_frcontactos where codemp = ".$_SESSION['codemp']." and  nrfr=".$nrfornecedor;
			//echo $sql_contatos;
			$rs = $db->execute($sql_contatos);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
						$temp = array();
						$temp['nome'] 	= ($row[0]);
						$temp['cargo'] 	= ($row[1]);
						$temp['tlm'] 	= $row[2];
						$temp['email'] 	= $row[3];
						$temp['obs'] 	= ($row[4]);
						$response[$counter] = $temp;
						$counter = $counter + 1;
				}
			}
			echo json_encode($response);
		break;
	
		case 'topfive':
			$when = $_GET['desde'];
			$response = array();
			
			if($when == 'sempre'){
			
				$sql_topfive = "select * 
									from (select cp.nrfornecedor, cp.nome, sum(cp.total), cp.codmoeda
											  from compras_cpcab cp, ctb_fr fr
											  where cp.codemp = ".$_SESSION['codemp']." 
											  and (cp.tipodoc = 'CPC' or cp.tipodoc = 'CPD') 
											  and fr.codemp = cp.codemp
											  and fr.nrfr = cp.nrfornecedor and fr.activo = 'S'
											  group by cp.nrfornecedor, cp.nome, cp.codmoeda 
											  order by sum(cp.total) desc
											)
									where rownum <=5";
			}else{
				$sql_topfive = "select * 
									from (select cp.nrfornecedor, cp.nome, sum(cp.total), cp.codmoeda 
											  from compras_cpcab cp, ctb_fr fr
											  where cp.codemp = ".$_SESSION['codemp']." 
											  and (cp.tipodoc = 'CPC' or cp.tipodoc = 'CPD')
											  and cp.anodoc = ".$when." 
											  and fr.codemp = cp.codemp
											  and fr.nrfr = cp.nrfornecedor and fr.activo = 'S'
											  group by cp.nrfornecedor, cp.nome, cp.codmoeda
											  order by sum(cp.total) desc
											)
									where rownum <=5"; 
			}
			
			$rs=$db->execute($sql_topfive);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
					if(is_null($row[0]))
						$response[0] = [];
					else{
						$temp = array();
						$temp['nrfornecedor'] 	= $row[0];
						$temp['nome'] 			= ($row[1]);
						$temp['montante'] 		= str_replace(',','.',$row[2]);
						$temp['moeda'] 			= $row[3];
						
						$response[$counter] = $temp;
						$counter = $counter +1;
					}
				}
			} else {
				echo "Error";
			}
			echo json_encode($response);
			
		break;
	
		case 'topNovosFornecedores':
	
			$sql_fornecedores ="select * from (
										select cp.nrfornecedor, cp.nome, sum(cp.total), cp.codmoeda, fr.DATA_REG
											from compras_cpcab cp, ctb_fr fr
											where cp.codemp = ".$_SESSION['codemp']." 
												and (cp.tipodoc = 'CPC' or cp.tipodoc = 'CPD') 
												and fr.codemp = cp.codemp
												and fr.nrfr = cp.nrfornecedor and fr.activo = 'S'
												group by cp.nrfornecedor, cp.nome, cp.codmoeda, fr.DATA_REG 
												order by fr.DATA_REG desc,sum(cp.total) desc
									) where rownum <= 5";
            
            $rs=$db->execute($sql_fornecedores);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
					if(is_null($row[0]))
						$response[0] = [];
					else{
						$temp = array();
						$temp['nrfornecedor'] 	= $row[0];
						$temp['nome'] 			= ($row[1]);
						$dataUnt = $row[2];
						$dataReg = explode("-",$dataUnt);
						//$datalabel = $dataReg[2].'-'.$dataReg[1].'-'.$dataReg[0];**********************************
						
						//$temp['datareg'] 	= $datalabel;************************************************************
						
						$response[$counter] = $temp;
						$counter = $counter +1;
					}
				}
			} else {
				echo "Error";
			}
			echo json_encode($response);
	
		break;
		
	
		case 'comprasMesAno':
			$nrfornecedor = $_GET['nrfornecedor'];
			
			$sql_fornecedores ="select * from (
									select ca.anodoc, 
									sum(decode(to_char(ca.datadoc,'MM'),'01',nvl(ca.total,0),0)) janeiro, 
									sum(decode(to_char(ca.datadoc,'MM'),'02',nvl(ca.total,0),0)) fevereiro, 
									sum(decode(to_char(ca.datadoc,'MM'),'03',nvl(ca.total,0),0)) marco, 
									sum(decode(to_char(ca.datadoc,'MM'),'04',nvl(ca.total,0),0)) abril, 
									sum(decode(to_char(ca.datadoc,'MM'),'05',nvl(ca.total,0),0)) maio, 
									sum(decode(to_char(ca.datadoc,'MM'),'06',nvl(ca.total,0),0)) junho, 
									sum(decode(to_char(ca.datadoc,'MM'),'07',nvl(ca.total,0),0)) julho, 
									sum(decode(to_char(ca.datadoc,'MM'),'08',nvl(ca.total,0),0)) agosto, 
									sum(decode(to_char(ca.datadoc,'MM'),'09',nvl(ca.total,0),0)) setembro, 
									sum(decode(to_char(ca.datadoc,'MM'),'10',nvl(ca.total,0),0)) outubro, 
									sum(decode(to_char(ca.datadoc,'MM'),'11',nvl(ca.total,0),0)) novembro, 
									sum(decode(to_char(ca.datadoc,'MM'),'12',nvl(ca.total,0),0)) dezembro 
								from compras_cpcab ca 
								where ca.codemp       = ".$_SESSION['codemp']."
									and ca.nrfornecedor = ".$nrfornecedor."
									and ( tipodoc = 'CPC' or tipodoc = 'CPD' )
								group by ca.anodoc 
								order by ca.anodoc desc )
								where rownum <= 5";
            
			$rs=$db->execute($sql_fornecedores);
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
				
				$meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
				
				$finalDataSets = array();
				for($i = 0; $i< sizeof($datasetData); $i++){
					$finalDataSets[$i] = getSomeColors($datasetData[$i], $series[$i]); 
				}
			
				$datasets = array("labels" => ($meses), "datasets" => ($finalDataSets));
				echo json_encode($datasets);	
			} else {
				echo 'Error: '.$db->ErrorMsg();
			}
		break;
			
		case 'comprasFornecedor':
			$nrfornecedor = $_GET['nrfornecedor'];
			
			$sql_query = "select * 
							from (select sum(total), datadoc
										from compras_cpcab
										where codemp = ".$_SESSION['codemp']."
										and ( tipodoc = 'CPC' or tipodoc = 'CPD' )
										and nrfornecedor = ".$nrfornecedor." 
										group by datadoc
										order by datadoc desc
										)
							where rownum <=10
							order by datadoc asc";
			
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
						
						$datasetData[$counter]	= str_replace(',','.',$row[0]);
						$counter = $counter +1;
					}
				}
				
				$finalDataSets = array();

				$finalDataSets[0] = getSomeColors($datasetData, $labels); 
				
				$datasets = array("labels" => ($labels), "datasets" => ($finalDataSets));
				echo json_encode($datasets);	
				
				
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