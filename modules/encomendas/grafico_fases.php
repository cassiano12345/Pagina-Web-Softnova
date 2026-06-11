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
		
		$dataini 	= $_GET['dataini'];
		$datafim 	= $_GET['datafim'];
	
		
		$sql = " select ec.ordem, vd.nroficial, ec.descricao
							from Vendas_eccab vd, ecl_tabelas ec
				            where vd.codemp = ".$_SESSION['codemp']."
				            and vd.tipodoc = 'ECL'
				            and situacao in ('E','P')
				            and vd.fase = ec.codigo
				            and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
				            and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
				            
				            order by ordem asc ";
				            
				            //and rownum <= 10 


	
		//echo $sql;
		$rs = $db->Execute($sql);

		if($rs){
			$labels = array();
			$nrOfDatasets = 0;
			$column = array();
			While($row = $rs->FetchRow()){
				if (is_null($row[0]))
					$nrOfDatasets = "null";
				else{
					$ds[$nrOfDatasets] 		= ($row[0]); 
					$labels[$nrOfDatasets]  = ($row[1]);
					$column[$nrOfDatasets]  = ($row[2]);					
					$nrOfDatasets += 1;				
				}
			}	

			
			$finalDataSets = array();
			for($i = 0; $i< $nrOfDatasets; $i++){
				$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i],$column[$i]); 
				
			}

			//$column = "Encomendas";
			//$column = [$labels];
			//$column2 = implode("\n\n\n",$column);
			//$mainLabel 	= [$column2];
			$mainLabel 	= " "; 

			$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);

			echo json_encode($datasets);	
		} else {
			echo 'Error: '.$db->ErrorMsg();
		}	 
}	
	

function getSomeColors($dataset, $serie,$number){
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
	return array("label" => $serie, "desc" =>$number, "fillColor" => $fillColor, "strokeColor" => $strokeColor, "pointColor" => $pointColor, "pointHighlightFill" => $pointHighlightFill, "data" => ($dataset));
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