<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

$vendas 	 = array();

$loja 	 = $_GET["loja"];


//echo $loja;



if ($loja == 'todos') {

	//$sql_lojas = 'Select dl.idloja from dash_lojas dl where dl.idgrupo='.$_SESSION["grupo"].'';
	try{
		$rsX=$db->execute('Select ac.idloja from dash_acessos ac where ac.codemp='.$_SESSION["codemp"].' and ac.idgrupo='.$_SESSION["grupo"].'');
		
		if($rsX){
			$sql_lojas_td  .= ' (';
			$count = 0;
			While($rowX = $rsX->FetchRow()){

				$rsP=$db->execute('Select dl.armazem  from dash_lojas dl where dl.codemp = '.$_SESSION["codemp"].' and dl.id='.$rowX[0].'');
				if($rsP){
					While($rowP = $rsP->FetchRow()){
						if ($count == 0){
							$sql_lojas_td .= '(VD.CODARM = \''.$rowP[0].'\')';	
						} else {
							$sql_lojas_td .= ' OR (VD.CODARM = \''.$rowP[0].'\')';	
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
	
	try{
		$rsX1=$db->execute('Select dl.nome from dash_acessos ac, dash_lojas dl where ac.codemp='.$_SESSION["codemp"].' and ac.idgrupo='.$_SESSION["grupo"].' group by  dl.nome');
	
		if($rsX){
			$sql_lojas_td1  .= ' (';
			$count1 = 0;
				$rsP1=$db->execute('Select dl.id from dash_lojas dl where dl.codemp = '.$_SESSION["codemp"].' group by  dl.id');
				if($rsP1){
					While($rowP = $rsP1->FetchRow()){
						if ($count1 == 0){
							$sql_lojas_td1 .= '(dl.id = \''.$rowP[0].'\')';	
						} else {
							$sql_lojas_td1 .= ' OR (dl.id = \''.$rowP[0].'\')';	
						}
						$count1 = $count1 + 1;
					}
				}
			$sql_lojas_td1  .= ')';
		}
	} catch (Exception $e) {
		echo $e;
	}
	
}

//echo $sql_lojas_td1;


	$sql_query = "select vd.datadoc, dl.nome	
					from vendas_vditem vd, dash_lojas dl
					where ". $sql_lojas_td ."
					and ". $sql_lojas_td1 ."
					group by  vd.datadoc, dl.nome
					ORDER BY  vd.datadoc, dl.nome";
			  
	$rs=$db->execute($sql_query);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$vendas = null;
			else{
				$temp = array();

				$temp['datadoc'] = $row[0];
				$temp['nome'] = str_replace(',','.',$row[1]); 
				
				$vendas[$counter] = $temp;
				$counter = $counter +1;
			}
		}
	}
	
	echo json_encode($vendas);

}
else{
	header("Location: ../../index.php");
}

?>