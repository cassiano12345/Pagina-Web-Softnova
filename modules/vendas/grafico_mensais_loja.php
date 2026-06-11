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
		
		$loja 		= $_GET['loja'];
		$mes 	= $_GET['mes'];
		$ano 	= $_GET['ano'];
		$dias 	= $_GET['dias'];
		

		if(strlen($mes)<2)
			$mes = '0'.$mes;
		
		$dataini = "01/".$mes."/".$ano;
		$datafim = $dias."/".$mes."/".$ano;

if ($loja == 'todos'){
		
		$sql =  " select dl.nome, sum((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0))*decode(ca.tipodoc,'DCL',-1,1)), dl.cor 
					from vendas_vdcab ca, vendas_vditem it, dash_lojas dl, dash_acessos ac 
					where ca.codemp = ".$_SESSION['codemp']."
						/*and ca.tipodoc  	<> 'DCL' 
						and ca.tipodoc  	<> 'FSC' 
						and ca.tipodoc  	<> 'NCC'*/ 
						and TO_CHAR(ca.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
						and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
						and it.codemp 		= ca.codemp
						and it.tipodoc 		= ca.tipodoc
						and it.serie 		= ca.serie
						and it.nroficial 	= ca.nroficial 
						and it.datadoc 		= ca.datadoc
						and dl.codemp 		= ca.codemp
						and dl.armazem 		= it.codarm
						and ac.codemp		= ca.codemp
						and ac.idgrupo		= ".$_SESSION['grupo']."
						and dl.id			= ac.idloja 
					group by dl.nome, dl.cor
					order by dl.nome asc  ";

	
					
		$rs = $db->Execute($sql);

		if($rs){
			$labels 		= array();
			$nrOfDatasets 	= 0;
			$cores		  	= array();
			
			While($row = $rs->FetchRow()){
				if (is_null($row[0]))
					$nrOfDatasets = "null";
				else{
					$ds[$nrOfDatasets] 		= [str_replace(',','.',$row[1])]; 
					$labels[$nrOfDatasets]  = ($row[0]);
					$cores[$nrOfDatasets]	= $row[2];
					$nrOfDatasets 			+= 1;
				}
			}

			$finalDataSets = array();
			for($i = 0; $i< $nrOfDatasets; $i++){
				$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
			}

			$column = $dataini . ' a ' . $datafim;
			$mainLabel = [$column];

			$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);

			echo json_encode($datasets);
				
		} else {
			echo 'Error';
		}	
	
	} else {
	
		$sql =  "
					select dl.nome, sum((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0))*decode(ca.tipodoc,'DCL',-1,1)), dl.cor 
					from vendas_vdcab ca, vendas_vditem it,  dash_lojas dl , dash_acessos ac 
					where ca.codemp 		= ".$_SESSION['codemp']."
						/*and ca.tipodoc  	<> 'DCL' 
						and ca.tipodoc  	<> 'FSC' 
						and ca.tipodoc  	<> 'NCC' */
						and TO_CHAR(ca.DATADOC,'yyyymmdd') between TO_CHAR(to_date('01/".$mes."/".$ano."', 'dd/mm/yyyy'),'yyyymmdd')
						and TO_CHAR(to_date('".$dias."/".$mes."/".$ano."','dd/mm/yyyy'),'yyyymmdd') 
						and it.codemp 		= ca.codemp
						and it.tipodoc 		= ca.tipodoc
						and it.serie 		= ca.serie
						and it.nroficial 	= ca.nroficial 
						and it.datadoc 		= ca.datadoc
						and dl.codemp 		= ca.codemp
						and dl.armazem 		= it.codarm
						and dl.id			= ".$loja." 
						and ac.codemp		= ca.codemp
						and ac.idgrupo		= ".$_SESSION['grupo']."
						and dl.id			= ac.idloja 
					group by  dl.nome, dl.cor
					order by dl.nome asc ";

		$rs = $db->Execute($sql);

		if($rs){
			$labels 		= array();
			$nrOfDatasets   = 0;
			$cores 			= array();
			While($row = $rs->FetchRow()){
				if (is_null($row[0]))
					$nrOfDatasets = "null";
				else{
					$ds[$nrOfDatasets] 		= [str_replace(',','.',$row[1])]; 
					$labels[$nrOfDatasets]  = ($row[0]);
					$cor[$nrOfDatasets]		= $row[2];
					$nrOfDatasets += 1;
				}
			}

			$finalDataSets = array();
			for($i = 0; $i< $nrOfDatasets; $i++){
				$finalDataSets[$i] = getSomeColors($ds[$i], $labels[$i]); 
			}

			$column = $dataini . ' a ' . $datafim;
			$mainLabel = [$column];

			$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);

			echo json_encode($datasets);

		} else {
			echo 'Error';
		}	
		
		
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