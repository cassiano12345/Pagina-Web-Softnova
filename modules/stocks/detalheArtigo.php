<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if(isset($_SESSION['user'])){

//$artigos["Content"] = "";
 
	$artigos = array(); 
	
	$expr = $_GET['exp'];
	$mode = $_GET['mode'];

	
	switch($mode){
	
		case 'cab':
		
			$sqlquery = "select distinct ar.codart,ar.nome
						from  stk_artigos   ar, 
							  stk_familias  fa,
							  stk_tipos     ti, 
							  stk_iva       iv,
							  stk_precos    pr,
							  stk_mercado	me
						where ar.codemp  = ".$_SESSION['codemp']."
						  and fa.codemp  = ar.codemp
						  and ti.codemp  = ar.codemp 
						  and iv.codemp  = ar.codemp
						  and pr.codemp  = ar.codemp 
						  and me.codemp  = ar.codemp
						  and (upper(ar.codart) like upper('%".$expr."%') or upper(ar.nome) like upper('%".$expr."%'))  
						  and fa.codigo  = ar.gfm
						  and fa.codigof = ar.fam
						  and fa.codigos = ar.sfm 
						  and ti.tabela  = 'A'
						  and ti.codigo  = ar.tipo
						  and iv.codigo  = ar.codiva
						  and iv.ano     = '".date('Y')."'
						  and pr.codart  = ar.codart
						  and me.codigo	 = ar.mercado
						order by ar.nome";

			//echo $sqlquery;
		 
			$rs=$db->execute($sqlquery);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
					if(is_null($row[0]))
						$artigos[0] = [];
					else{
						$artigos[$counter] = ($row[1]) .' | '.$row[0];
						$counter = $counter + 1;
					}
				}
			}
			echo json_encode($artigos);
			
			break;
			
		case 'nome':
		
			$sqlquery = "select distinct ar.codart,ar.nome
						from  stk_artigos ar 
						where ar.codemp = ".$_SESSION['codemp']."
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
						$artigos[$counter] = ($row[1]);
						$counter = $counter +1;
					}
				}
			}
			echo json_encode($artigos);
			
			break;
			
		case 'det':
		
			$sqlquery = "select ar.codart,ar.nome, ar.classif, fa.nome , ti.nome, iv.nome, iv.taxa,  
								pr.data, nvl(pr.p1,'0'), nvl(ar.pum,'0'),ar.datapum, nvl(ar.pc,0), nvl(pr.p2,'0'), nvl(pr.p3,'0'), nvl(pr.p4,'0'),nvl(pr.p5,'0'), me.nome,
								ar.gfm, ar.sfm, ar.fam
						from  stk_artigos   ar, 
							  stk_familias  fa,
							  stk_tipos     ti, 
							  stk_iva       iv,
							  stk_precos    pr,
							  stk_mercado	me
						where ar.codemp  = ".$_SESSION['codemp']."
						  and fa.codemp  = ar.codemp
						  and ti.codemp  = ar.codemp 
						  and iv.codemp  = ar.codemp
						  and pr.codemp  = ar.codemp 
						  and me.codemp  = ar.codemp
						  and upper(ar.codart) = upper('".$expr."')  
						  and fa.codigo  = ar.gfm
						  and fa.codigof = ar.fam
						  and fa.codigos = ar.sfm 
						  and ti.tabela  = 'A'
						  and ti.codigo  = ar.tipo
						  and iv.codigo  = ar.codiva
						  and iv.ano     = '".date('Y')."'
						  and pr.codart  = ar.codart
						  and me.codigo	 = ar.mercado
						order by ar.codart, pr.data desc";

			//echo $sqlquery;
		 
			$rs=$db->execute($sqlquery);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$artigos["Content"] = "null";
					else{
						$temp = array();
						
						$temp['codart'] 		= ($row[0]);
						$temp['artigo'] 		= ($row[1]);
						$temp['classificacao'] 	= ($row[2]);
						$temp['tipo'] 			= $row[3];
						$temp['ivadesc'] 		= $row[4]." - ".$row[5];
						$temp['taxaiva'] 		= $row[6];
						$temp['datapreco'] 		= $row[7];
						$temp['p1'] 			= str_replace(',','.',$row[8]);
						$temp['pum'] 			= str_replace(',','.',$row[9]);
						$temp['datapum'] 		= $row[10];
						$temp['pcusto'] 		= str_replace(',','.',$row[11]);
						$temp['p2'] 			= str_replace(',','.',$row[12]);
						$temp['p3'] 			= str_replace(',','.',$row[13]);
						$temp['p4'] 			= str_replace(',','.',$row[14]);
						$temp['p5'] 			= str_replace(',','.',$row[15]);	
						$temp['mercado'] 		= ($row[16]);	
						$temp['gf'] 			= ($row[17]);	
						$temp['sf'] 			= ($row[18]);	
						$temp['fa'] 			= ($row[19]);	
						
						$artigos[$counter] = $temp;
						$counter = $counter +1;
					}
				}
			}
			echo json_encode($artigos);
			
			break;

		case 'pvGraph':
			
			$sqlquery = "select nvl(pr.p1,'0'), max(pr.data)
						from  stk_artigos   ar, 
							  stk_familias  fa,
							  stk_precos    pr
						where ar.codemp  = ".$_SESSION['codemp']."
						  and fa.codemp  = ar.codemp
						  and upper(ar.codart) = upper('".$expr."')  
						  and pr.codart  = ar.codart
						  and fa.codigo  = ar.gfm
						  and fa.codigof = ar.fam
						  and fa.codigos = ar.sfm 
						group by nvl(pr.p1,'0'), ar.datapv
						order by max(pr.data) asc";
			
			
			//echo $sqlquery;

			$rs=$db->execute($sqlquery);
			if($rs){
				$counter 		= 0;
				$data	 		= array();
				$labels 		= array();
				$datasetData	= array();
				
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$datasets = "null";
					else{
						$dataPreco = $row[1];
						$dataPartida = explode("-",$dataPreco);
						$datalabel = $dataPartida[2].'-'.$dataPartida[1].'-'.$dataPartida[0];
						$labels[$counter] = $datalabel;
						
						$datasetData[$counter]	= $row[0];
						$counter = $counter +1;
					}
				}
				
				$fillColorR 	= rand(101,244);
				$fillColorG 	= rand(101,244);
				$fillColorB 	= rand(101,244);
				$strokeColorR 	= $fillColorR + 10;
				$strokeColorG 	= $fillColorG + 10;
				$strokeColorB 	= $fillColorB + 10;
				$pointColorR 	= $fillColorR - 10;
				$pointColorG 	= $fillColorG - 10;
				$pointColorB 	= $fillColorB - 10;

				$fillColor 		= "rgba(".$fillColorR.",".$fillColorG.",".$fillColorB.",0.4)";
				$strokeColor 	= "rgba(".$strokeColorR.",".$strokeColorG.",".$strokeColorB.",0.7)";
				$pointColor 	= "rgba(".$pointColorR.",".$pointColorG.",".$pointColorB.",0.9)";
			
				
				$myDataset = array(array("fillColor" => $fillColor, "strokeColor" => $strokeColor, "pointColor" => $pointColor, "data" => ($datasetData)));
				$datasets = array("labels" => ($labels), "datasets" => ($myDataset)); 
				 
			
				
				echo json_encode($datasets);	
				
				break;
			} else {
				echo 'error';
			}
			
		case 'pcustoGraph':
	
			$sqlquery = "select mo.pc, max(mo.data), mo.codmov 
							from stk_mov mo
							where mo.codemp = ".$_SESSION['codemp']." 
							  and mo.codart = '".$expr."'
							  and mo.sinal = '+' 
						group by mo.pc, mo.codmov
						order by max(mo.data) asc";
			
			//echo $sqlquery;

			$rs=$db->execute($sqlquery);
			if($rs){
				$counter 		= 0;
				$data	 		= array();
				$labels 		= array();
				$datasetData	= array();
				
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$datasets = "null";
					else{
						$dataPreco = $row[1];
						$dataPartida = explode("-",$dataPreco);
						$datalabel = $dataPartida[2].'-'.$dataPartida[1].'-'.$dataPartida[0];
						$labels[$counter] = $datalabel;
						
						
						//verificar se ha custos associados
						$custo  = 0;
						$sqlcustos = "select sum(valor), codmov
										from stk_mov_custos
										where codemp = ".$_SESSION['codemp']."
										and codmov	= ".$row[2]."
										group by codmov ";
										
						$rsC=$db->execute($sqlcustos);
						if($rsC){
							While($custosLinha = $rsC->FetchRow()){			
								$custo += $custosLinha[0];
							}
						}
						//_________________________________________
						
						$custo += $row[0];
						$datasetData[$counter]	= round($custo, 3);
						$counter = $counter +1;
					}
				}
				
				$fillColorR 	= rand(101,244);
				$fillColorG 	= rand(101,244);
				$fillColorB 	= rand(101,244);
				$strokeColorR 	= $fillColorR + 10;
				$strokeColorG 	= $fillColorG + 10;
				$strokeColorB 	= $fillColorB + 10;
				$pointColorR 	= $fillColorR - 10;
				$pointColorG 	= $fillColorG - 10;
				$pointColorB 	= $fillColorB - 10;

				$fillColor 		= "rgba(".$fillColorR.",".$fillColorG.",".$fillColorB.",0.4)";
				$strokeColor 	= "rgba(".$strokeColorR.",".$strokeColorG.",".$strokeColorB.",0.7)";
				$pointColor 	= "rgba(".$pointColorR.",".$pointColorG.",".$pointColorB.",0.9)";

				
				$myDataset = array(array("fillColor" => $fillColor, "strokeColor" => $strokeColor, "pointColor" => $pointColor, "data" => ($datasetData)));
				$datasets = array("labels" => ($labels), "datasets" => ($myDataset)); 
				 
			
				
				echo json_encode($datasets);	
			}

			break;
	
		case 'vendasDiariasGraph': 
			
			$sqlquery = "select sum(it.qtd), it.datadoc 
							from vendas_vditem it 
							where it.codemp = ".$_SESSION['codemp']."
							and it.codigo = '".$expr."' 
							group by it.datadoc";

			//echo $sqlquery;

			$rs=$db->execute($sqlquery);
			if($rs){
				$counter 		= 0;
				$data	 		= array();
				$labels 		= array();
				$datasetData	= array();
				
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$datasets = "null";
					else{
						$dataPreco = $row[1];
						$dataPartida = explode("-",$dataPreco);
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
			
		case 'PUMGraph':
		
			$sqlquery = "select nvl(pum,0), max(data)  
							from stk_mov 
							where codemp = ".$_SESSION['codemp']."
							and codart = '".$expr."' 
							group by pum
							order by max(data) asc";

			//echo $sqlquery;

			$rs=$db->execute($sqlquery);
			if($rs){
				$counter 		= 0;
				$data	 		= array();
				$labels 		= array();
				$datasetData	= array();
				
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$datasets = "null";
					else{
						$dataPreco = $row[1];
						$dataPartida = explode("-",$dataPreco);
						$datalabel = $dataPartida[2].'-'.$dataPartida[1].'-'.$dataPartida[0];
						$labels[$counter] = $datalabel;
						
						$datasetData[$counter]	= round($row[0], 2);
						$counter = $counter +1;
					}
				}
				
				$fillColorR 	= rand(101,244);
				$fillColorG 	= rand(101,244);
				$fillColorB 	= rand(101,244);
				$strokeColorR 	= $fillColorR + 10;
				$strokeColorG 	= $fillColorG + 10;
				$strokeColorB 	= $fillColorB + 10;
				$pointColorR 	= $fillColorR - 10;
				$pointColorG 	= $fillColorG - 10;
				$pointColorB 	= $fillColorB - 10;

				$fillColor 		= "rgba(".$fillColorR.",".$fillColorG.",".$fillColorB.",0.4)";
				$strokeColor 	= "rgba(".$strokeColorR.",".$strokeColorG.",".$strokeColorB.",0.7)";
				$pointColor 	= "rgba(".$pointColorR.",".$pointColorG.",".$pointColorB.",0.9)";
				
				$myDataset = array(array("fillColor" => $fillColor, "strokeColor" => $strokeColor, "pointColor" => $pointColor, "data" => ($datasetData)));
				$datasets = array("labels" => ($labels), "datasets" => ($myDataset)); 
			
			
				echo json_encode($datasets);	
			} else {
				echo 'error';
			}
			
			break;	
	} 
}
else{
	header("Location: ../../index.php");
}
?>