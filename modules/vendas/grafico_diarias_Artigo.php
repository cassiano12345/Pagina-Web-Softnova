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
		$dataini 	= $_GET['dataini'];
		$datafim 	= $_GET['datafim'];
		$aprocurar 	= $_GET['aprocurar'];

	

if ($loja == 'todos'){


		$sql = " select nome ,sum(qtd) from (
 				           (select ar.nome nome, (vd.qtd*decode(tipodoc,'DCL',-1,1)) qtd
				                      from vendas_vditem vd, stk_artigos ar, dash_acessos ac, dash_lojas dl
				                      where vd.codemp = ".$_SESSION['codemp']."
				                        and nvl(vd.situacao,'') <> 'A'
				                        and ar.codemp = vd.codemp
				                        and ar.codart = vd.codigo
				                        and ac.codemp = ar.codemp
				                        and dl.codemp = ac.codemp
										and (upper(ar.codart) like upper('%".$aprocurar."%') or upper(ar.nome) like upper('%".$aprocurar."%'))
				                        and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
				                                           and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
				                        and ac.idgrupo = '".$_SESSION['grupo']."'
				                        and dl.id      = ac.idloja
				                        and vd.codarm  = dl.armazem 
				          )
				          UNION
				          (select ar.nome nome, (vd.qtd*decode(tipodoc,'DCL',-1,1)) qtd
				             from vendas_GRitem vd, stk_artigos ar, dash_acessos ac, dash_lojas dl
				                      where vd.codemp = ".$_SESSION['codemp']."
				                        and ar.codemp = vd.codemp
				                        and ar.codart = vd.codigo
				                        and ac.codemp = ar.codemp
				                        and dl.codemp = ac.codemp
				                        and (upper(ar.codart) like upper('%".$aprocurar."%') or upper(ar.nome) like upper('%".$aprocurar."%'))
				                        and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
				                                           and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
				                        and ac.idgrupo = '".$_SESSION['grupo']."'
				                        and dl.id      = ac.idloja
				                        and vd.codarm  = dl.armazem 
				            )
				    )where rownum <= 10 
				     group by nome 
				     order by sum(qtd) desc";


	
		//echo $sql;
		$rs = $db->Execute($sql);

		if($rs){
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

			$column 	= 'Top 10 Qtd/Artigo: '.$dataini . ' a ' . $datafim;
			$mainLabel 	= [$column];

			$datasets = array("labels" => $mainLabel, "datasets" => $finalDataSets);

			echo json_encode($datasets);	
		} else {
			echo 'Error';
		}	
	
	} else {
	
		$sql =  "
					select  ar.nome,sum(vd.qtd*decode(tipodoc,'DCL',-1,1))
					from vendas_vditem vd, stk_artigos ar, dash_acessos ac, dash_lojas dl
					where vd.codemp = ".$_SESSION['codemp']."
						and nvl(vd.situacao,'') <> 'A'
						and ar.codemp = vd.codemp
						and ar.codart = vd.codigo
						and ac.codemp = ar.codemp
						and ac.idgrupo = '".$_SESSION['grupo']."'
						and dl.codemp = ac.codemp
						and dl.id= ".$loja."
						and (upper(ar.codart) like upper('%".$aprocurar."%') or upper(ar.nome) like upper('%".$aprocurar."%'))
						and TO_CHAR(vd.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
						and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
						and vd.codarm  = dl.armazem 
					group by ar.nome 
					order by ar.nome  ";

		$rs = $db->Execute($sql);

		if($rs){
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