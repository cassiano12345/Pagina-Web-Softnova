<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");
$content = "";
$contentOptions ='';

//session_start();

if (!isset($_SESSION['user'])){
	echo "";
	header("Location: ../../index.php");
}
else{
		
		$tipo = $_GET['tipo'];
		//$dataini 		= $_GET['dataini'];
		$dataini 		= date("d/m/Y");
		$datafim 		= date("d/m/Y");
		//$datafim 		= $_GET['datafim'];
		$tipo_qry 		= $_GET['tipo_qry'];
		//$procurar 		= $_GET['procurar'];
		
		switch($tipo){
			case 1:
				if ($tipo_qry==1){
					$sql =  "select * from ( 

								  select nomeX, sum(totalX) from (
								      (select vd.nome nomeX, 
								              ((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))*decode(tipodoc,'DCL',-1,1)) totalX 
								            from vendas_vdcab ca, ctb_vendedor vd	
								            where ca.codemp  = ".$_SESSION['codemp']." 
								              /*and ca.tipodoc 					<> 'DCL' 
								              and ca.tipodoc 					<> 'FSC' 
								              and ca.tipodoc 					<> 'NCC' */		
								              and vd.codemp 					 = ca.codemp
								              and vd.nrvd 						 = ca.vendedor 
								              and to_char(ca.datadoc,'yyyymmdd') = to_char(to_date('".date('d/m/Y')."','dd/mm/yyyy'), 'yyyymmdd'))
								              
								              
								      UNION 
								      
								     (select vd.nome nomeX, 
								              ((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))*decode(tipodoc,'DCL',-1,1)) totalX
								            from vendas_GRcab ca, ctb_vendedor vd	
								            where ca.codemp  = ".$_SESSION['codemp']."
								              and ca.tipodoc 					 = 'GRM' 	
								              and vd.codemp 					 = ca.codemp
								              and vd.nrvd 						 = ca.vendedor 
								              and to_char(ca.datadoc,'yyyymmdd') = to_char(to_date('".date('d/m/Y')."','dd/mm/yyyy'), 'yyyymmdd')
								             )
								          ) group by nomeX


										) where rownum <= 10";

//echo $sql;

					$rs = $db->Execute($sql);
					if($rs){
						$labels = array();
						$nrOfDatasets = 0;
						
						While($row = $rs->FetchRow()){
							if (is_null($row[0]))
								$nrOfDatasets = "null";
							else{
								$ds[$nrOfDatasets] 		= [($row[1])]; 
								$labels[$nrOfDatasets]  = str_replace(',','.',$row[0]);
								$nrOfDatasets += 1;
							}
						}
						
						$finalDataSets = array();
						for($i = 0; $i< $nrOfDatasets; $i++){
							$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
						}
						
						$column = "Top 10";
						$mainLabel = [$column];
						
						$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);
						
						echo json_encode($datasets);	
					} else {
						echo 'Error';
					}
				}
				else if ($tipo_qry==2){
				
					$sql =  " select * from ( 

								  select nomeX, sum(totalX) from (
								      (select vd.nome nomeX, 
								              ((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))*decode(tipodoc,'DCL',-1,1)) totalX 
								            from vendas_vdcab ca, ctb_vendedor vd	
								            where ca.codemp  = ".$_SESSION['codemp']." 
								              /*and ca.tipodoc 					<> 'DCL' 
								              and ca.tipodoc 					<> 'FSC' 
								              and ca.tipodoc 					<> 'NCC'*/ 	
							              	  and TO_CHAR(ca.datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
								  											and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 	
								              and vd.codemp 					 = ca.codemp
								              and vd.nrvd 						 = ca.vendedor 
								              and to_char(ca.datadoc,'yyyymmdd') = to_char(to_date('".date('d/m/Y')."','dd/mm/yyyy'), 'yyyymmdd'))
								              
								              
								      UNION 
								      
								     (select vd.nome nomeX, 
								              ((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))*decode(tipodoc,'DCL',-1,1)) totalX
								            from vendas_GRcab ca, ctb_vendedor vd	
								            where ca.codemp  = ".$_SESSION['codemp']."
								              and ca.tipodoc 					 = 'GRM' 	
							              	  and TO_CHAR(ca.datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
								  											and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
								              and vd.codemp 					 = ca.codemp
								              and vd.nrvd 						 = ca.vendedor 
								              and to_char(ca.datadoc,'yyyymmdd') = to_char(to_date('".date('d/m/Y')."','dd/mm/yyyy'), 'yyyymmdd')
								             )
								          ) group by nomeX


										) where rownum <= 10 ";

					//echo $sql;
					$rs = $db->Execute($sql);
					if($rs){
						if($dataini==$datafim)
							$data=$datafim;
						else
							$data= $dataini . ' a ' . $datafim;
							
						$labels = array();
						$nrOfDatasets = 0;
						
						While($row = $rs->FetchRow()){
							if (is_null($row[0]))
								$nrOfDatasets = "null";
							else{
								$ds[$nrOfDatasets] 		= [str_replace(',','.',$row[1])]; 
								$labels[$nrOfDatasets]  = ($row[0]);
								$nrOfDatasets += 1;
							}
						}
						
						$finalDataSets = array();
						for($i = 0; $i< $nrOfDatasets; $i++){
							$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
						}
						
						$column = "Top 10: ".$data;
						$mainLabel = [$column];
						
						$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);
						
						echo json_encode($datasets);	
					} else {
						echo 'Error';
					}
				}
			
			break;
	
			
			case 2:
				if($tipo_qry==1){
					//select ca.nome, (nvl((total - IVA01VAL - IVA02VAL - iva03val - iva04val),0)*decode(tipodoc,'DFR',-1,1)) -->sem estar agrupado pelo fornecedor
					$sql =  "select * from (select ca.nome, sum(nvl((total - IVA01VAL - IVA02VAL - iva03val - iva04val),0)*decode(tipodoc,'DFR',-1,1))soma
								from compras_cpcab ca
								where ca.codemp  = ".$_SESSION['codemp']." 
									/*and (tipodoc = 'CPC' or tipodoc='CPD') */
									and (situacao = 'E' or situacao = 'P' or situacao = 'C' or situacao = 'D')
									and TO_CHAR(ca.datadoc,'yyyymmdd') = TO_CHAR(to_date('".date('d/m/Y')."', 'dd/mm/yyyy'),'yyyymmdd')
									group by ca.nome  order by soma desc) where rownum <= 10";
						
					$rs = $db->Execute($sql);
							
					if($rs){
						if($dataini==$datafim)
							$data=$datafim;
						else
							$data= $dataini . ' a ' . $datafim;
						$labels = array();
						$nrOfDatasets = 0;
						
						While($row = $rs->FetchRow()){
							if (is_null($row[0]))
								$nrOfDatasets = "null";
							else{
								$ds[$nrOfDatasets] 		= [str_replace(',','.',$row[1])]; 
								$labels[$nrOfDatasets]  = ($row[0]);
								$nrOfDatasets += 1;
							}
						}
						
						$finalDataSets = array();
						for($i = 0; $i< $nrOfDatasets; $i++){
							$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
						}
						
						$column = "Top 10: ".$data;
						$mainLabel = [$column];
						
						$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);
						
						echo json_encode($datasets);	
					} else {
						echo 'Error';
					}
				}
				else if($tipo_qry==2){							
						//sum(nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))
						$sql =  "select * from (select ca.nome, sum((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))*decode(tipodoc,'DFR',-1,1)) soma 
										from compras_cpcab ca
										where ca.codemp  = ".$_SESSION['codemp']." 
										and (tipodoc = 'CPC' or tipodoc='CPD') 
										and (situacao = 'E' or situacao = 'P' or situacao = 'C' or situacao = 'D')
										and TO_CHAR(datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
										and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
										group by ca.nome order by soma desc) where rownum <= 10";
								
							$rs = $db->Execute($sql);
									
							if($rs){
								if($dataini==$datafim)
									$data=$datafim;
								else
									$data= $dataini . ' a ' . $datafim;
								$labels = array();
								$nrOfDatasets = 0;
								
								While($row = $rs->FetchRow()){
									if (is_null($row[0]))
										$nrOfDatasets = "null";
									else{
										$ds[$nrOfDatasets] 		= [str_replace(',','.',$row[1])]; 
										$labels[$nrOfDatasets]  = ($row[0]);
										$nrOfDatasets += 1;
									}
								}
								
								$finalDataSets = array();
								for($i = 0; $i< $nrOfDatasets; $i++){
									$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
								}
								
								$column = "Top 10: ".$data;
								$mainLabel = [$column];
								
								$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);
								
								echo json_encode($datasets);	
							} else {
								echo 'Error';
							}			
				}
			break;
			
			case 3:
				if($tipo_qry==1){
					$sql =  "select * from (select sum(total) soma, nome 
							 from vendas_eccab 
							 where codemp  = ".$_SESSION['codemp']."
								and tipodoc = 'ECL' 
								and (situacao = 'E' or situacao = 'P' or situacao = 'C' or situacao = 'D')
							 group by nome  order by soma desc) where rownum <= 10";
								
					$rs = $db->Execute($sql);
					if($rs){
						$labels = array();
						$nrOfDatasets = 0;
						
						While($row = $rs->FetchRow()){
							if (is_null($row[0]))
								$nrOfDatasets = "null";
							else{
								$ds[$nrOfDatasets] 		= [str_replace(',','.',$row[0])]; 
								$labels[$nrOfDatasets]  = ($row[1]);
								$nrOfDatasets += 1;
							}
						}
						$finalDataSets = array();
						for($i = 0; $i< $nrOfDatasets; $i++){
							$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
						}
						$column = "Top 10";
						$mainLabel = [$column];
						$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);
						echo json_encode($datasets);	
					} else {
						echo 'Error';
					}
				}
				if($tipo_qry==2){
					$sql =  "select * from (select sum((nvl(totalmercli,0) - nvl(iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0))) soma, nome 
								from vendas_eccab 
								where codemp  = ".$_SESSION['codemp']."
								  and tipodoc = 'ECL'
								  and (situacao = 'E' or situacao = 'P' or situacao = 'C' or situacao = 'D')
								  and TO_CHAR(datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
								  and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd')
								group by nome  order by soma desc) where rownum <=10 ";
					// echo $sql;
					$rs = $db->Execute($sql);
					if($rs){
						$labels = array();
						$nrOfDatasets = 0;
						
						While($row = $rs->FetchRow()){
							if (is_null($row[0]))
								$nrOfDatasets = "null";
							else{
								$ds[$nrOfDatasets] 		= [str_replace(',','.',$row[0])]; 
								$labels[$nrOfDatasets]  = ($row[1]);
								$nrOfDatasets += 1;
							}
						}
						$finalDataSets = array();
						for($i = 0; $i< $nrOfDatasets; $i++){
							$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
						}
						$column = date('Y-m-d');
						$mainLabel = [$column];
						$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);
						echo json_encode($datasets);	
					} else {
						echo 'Error';
					}
				}
				
				if($tipo_qry==3){
				
					if($dataini==$datafim)
						$data=$datafim;
					else
						$data= $dataini . ' a ' . $datafim;
					
					if($procurar==""){
						$sql =  "				
							select * from ( select sum(it.valorli) soma, it.desart
								from vendas_eccab ec, vendas_ecitem it
								where ec.codemp   = ".$_SESSION['codemp']."
									and ec.tipodoc  = 'ECL'
									and it.codemp	= ec.codemp
									and it.tipodoc	= ec.tipodoc
									and it.nroficial= ec.nroficial	
									and (upper(desart) like upper('%%'))
									and TO_CHAR(it.datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
									and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
									and (ec.situacao = 'E' or ec.situacao = 'P' or ec.situacao = 'C' or ec.situacao = 'D')
								group by it.desart  order by soma desc) where rownum <= 10 ";
									
						// echo $sql;
									
						$rs = $db->Execute($sql);
						if($rs){
							$labels = array();
							$nrOfDatasets = 0;
							
							While($row = $rs->FetchRow()){
								if (is_null($row[0]))
									$nrOfDatasets = "null";
								else{
									$ds[$nrOfDatasets] 		= [str_replace(',','.',$row[0])]; 
									$labels[$nrOfDatasets]  = ($row[1]);
									$nrOfDatasets += 1;
								}
							}
							$finalDataSets = array();
							for($i = 0; $i< $nrOfDatasets; $i++){
								$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
							}
							$column = "Top 10: ".$data;
							$mainLabel = [$column];
							$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);
							echo json_encode($datasets);	
						} else {
							echo 'Error';
						}
					}
					else{
						$sql =  "select * from (select sum(ec.total) soma, ec.nome
										from vendas_eccab ec, vendas_ecitem it
										where ec.codemp   	 = ".$_SESSION['codemp']."
											and ec.tipodoc   = 'ECL'
											and it.codemp	 = ec.codemp
											and it.tipodoc 	 = ec.tipodoc
											and it.nroficial = ec.nroficial	
											and ec.total 	!= 0
											and (upper(desart) like upper('%".$procurar."%'))
											and TO_CHAR(it.datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
											and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
											and (ec.situacao = 'E' or ec.situacao = 'P' or ec.situacao = 'C' or ec.situacao = 'D')
										group by ec.nome  order by soma desc ) where rownum <=10";
									
						// echo $sql;
									
						$rs = $db->Execute($sql);
						if($rs){
							$labels = array();
							$nrOfDatasets = 0;
							
							While($row = $rs->FetchRow()){
								if (is_null($row[0]))
									$nrOfDatasets = "null";
								else{
									$ds[$nrOfDatasets] 		= [str_replace(',','.',$row[0])]; 
									$labels[$nrOfDatasets]  = ($row[1]);
									$nrOfDatasets += 1;
								}
							}
							$finalDataSets = array();
							for($i = 0; $i< $nrOfDatasets; $i++){
								$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
							}
							$column = "Top 10: ".$data;
							$mainLabel = [$column];
							$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);
							echo json_encode($datasets);	
						} else {
							echo 'Error';
						}				
					}
				}
			break;

			
			case 4:
				if($tipo_qry==1){
					
					$sql =  "select * from (select sum(nvl(totalmercli,0)+nvl(iva01val,0)+nvl(iva02val,0)+nvl(iva03val,0)+nvl(iva04val,0)) soma, nrfornecedor, nome, datadoc
										from compras_efcab
										where codemp  	= ".$_SESSION['codemp']."
											and tipodoc 	= 'REF'  
											and (situacao = 'E' or situacao = 'P' or situacao = 'C' or situacao = 'D')
										group by nrfornecedor, nome, datadoc
										order by soma desc, datadoc desc, nrfornecedor asc,nome asc)
									where rownum <=10";
								
					// echo $sql;
					
					$rs = $db->Execute($sql);
					if($rs){
					
						$labels = array();
						$nrOfDatasets = 0;
						While($row = $rs->FetchRow()){
							if (is_null($row[0]))
								$nrOfDatasets = "null";
							else{
								$ds[$nrOfDatasets] 		= [str_replace(',','.',$row[0])]; 
								$labels[$nrOfDatasets]  = ($row[2]);
								$nrOfDatasets += 1;
							}
						}
						$finalDataSets = array();
						for($i = 0; $i< $nrOfDatasets; $i++){
							$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
						}
						//$column = date('Y-m-d');
						$column = "Top 10";
						$mainLabel = [$column];
						$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);
						echo json_encode($datasets);	
					} else {
						echo 'Error';
					}
				}
			
				if($tipo_qry==2){
					if($dataini==$datafim)
						$data=$datafim;
					else
						$data= $dataini . ' a ' . $datafim;
					
					$sql =  "select * from (select sum(nvl(totalmercli,0)+nvl(iva01val,0)+nvl(iva02val,0)+nvl(iva03val,0)+nvl(iva04val,0)) soma, nrfornecedor, nome
										from compras_efcab
										where codemp  		= ".$_SESSION['codemp']."
											and TO_CHAR(datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
											and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
											and (situacao 	= 'E' or situacao = 'P' or situacao = 'C' or situacao = 'D')
										group by nrfornecedor, nome
										order by soma desc, nrfornecedor ,nome) where ROWNUM <= 10 ";

					//echo $sql;
					$rs = $db->Execute($sql);
					if($rs){
						$labels = array();
						$nrOfDatasets = 0;

						While($row = $rs->FetchRow()){
							if (is_null($row[0]))
								$nrOfDatasets = "null";
							else{
								$ds[$nrOfDatasets] 		= [str_replace(',','.',$row[0])]; 
								$labels[$nrOfDatasets]  = ($row[2]);
								$nrOfDatasets += 1;
							}
						}
						$finalDataSets = array();
						for($i = 0; $i< $nrOfDatasets; $i++){
							$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
						}
						//$column = date('Y-m-d');
						$column = "Top 10: ".$data;
						$mainLabel = [$column];
						$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);
						echo json_encode($datasets);	
					} else {
						echo 'Error';
					}
				}
			break;
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
	$colorR = rand(80,200);
	return $colorR;
}

function getColorG(){
	$colorG = rand(80,200);
	return $colorG;
}

function getColorB(){
	$colorB = rand(80,200);
	return $colorB;
}


?>