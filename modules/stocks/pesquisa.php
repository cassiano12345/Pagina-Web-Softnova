<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

$mercados 	 = array();
$aprocurar 	 = $_GET['aprocurar'];
$nomeloja 	 = $_GET["nomeloja"];
$ano		 = $_GET["ano"];
$mes 		 = $_GET["mes"];


$sql_lojas_td   = '';
$count   = 0;


	if($nomeloja=="todos"){
		
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
								$sql_lojas_td .= '(sa.CODARM = \''.$rowP[0].'\')';	
							} else {
								$sql_lojas_td .= ' OR (sa.CODARM = \''.$rowP[0].'\')';	
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

	}
	else {
		try{
			//echo $nomeloja;
			$rsP=$db->execute('Select dl.armazem from dash_lojas dl where dl.codemp = '.$_SESSION["codemp"].' and dl.id='.$nomeloja.'');
			if($rsP){
				While($rowP = $rsP->FetchRow()){
					$sql_lojas_td .= ' (sa.CODARM = \''.$rowP[0].'\') ';
				}
			}	
		} catch (Exception $e) {
			echo $e;
		}
	}
		


$sql_query = "";


$sql_query = "select ar.codart, ar.nome, ta.nome, sum(sa.qtd)
				from stk_artigos ar, dash_lojas dl, stk_saldos sa, stk_tabelas ta
				where sa.codemp = ".$_SESSION['codemp']."
				  and dl.codemp   = sa.codemp
				  and ta.codemp   = dl.codemp
				  and ar.codemp   = ta.codemp
				  and ta.tabela   = 'AR'
				  and ta.codigo   = dl.armazem
				  and sa.codarm   = ta.codigo
				  and sa.codart   = ar.codart
				  and ( upper(ar.codart) like upper('%".$aprocurar."%')
						or upper(ar.nome) like upper('%".$aprocurar."%')) ";


$sql_param = " ";


if($ano != 'todos')
	$sql_param .= " and sa.ano = '".$ano."' ";

if($mes != 'todos')
	$sql_param .= " and sa.mes = '".$mes."' ";
  
$sql_order = " group by ar.codart, ar.nome, ta.nome order by ar.nome ";

$sql_query .= " and ".$sql_lojas_td." ".$sql_param." ".$sql_order;

//echo $sql_query;

	$rs=$db->execute($sql_query);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$mercados = null;
			else{
				$temp = array();
				$temp['codart']  = $row[0];
				$temp['nome'] 	 = ($row[1]);
				$temp['armazem'] = ($row[2]);
				$temp['qtd'] 	 = str_replace(',','.',$row[3]);
				
				$mercados[$counter] = $temp;
				$counter = $counter +1;
			}
		}
	}
	
	echo json_encode($mercados);

}
else{
	header("Location: ../../index.php");
}
	



?>