<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$datasets 	 = array();
	$aprocurar 	 = $_GET['aprocurar'];
	$nomeloja 	 = $_GET["nomeloja"];
	$modo 		 = $_GET["modo"];
	$datalimite  = $_GET["datalimite"];
	
	
	
	$sql_lojas_td   = '';
	$count   = 0;

	
	if($nomeloja=="todos"){
			
		try{
			$rsX=$db->execute('Select ac.idloja from dash_acessos ac where ac.codemp = '.$_SESSION["codemp"].' and ac.idgrupo='.$_SESSION["grupo"].'');
			if($rsX){
				$sql_lojas_td  .= ' (';
				$count = 0;
				While($rowX = $rsX->FetchRow()){
					$rsP=$db->execute('Select dl.armazem from dash_lojas dl where dl.codemp = '.$_SESSION["codemp"].' and dl.id='.$rowX[0].'');
					if($rsP){
						While($rowP = $rsP->FetchRow()){
							if ($count == 0){
								$sql_lojas_td .= '(sa.codarm = \''.$rowP[0].'\')';	
							} else {
								$sql_lojas_td .= ' OR (sa.codarm = \''.$rowP[0].'\')';	
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

	} else {
		
		try{
			//echo $nomeloja;
			$rsP=$db->execute('Select dl.armazem from dash_lojas dl where dl.codemp = '.$_SESSION["codemp"].' and dl.id='.$nomeloja.'');
			if($rsP){
				While($rowP = $rsP->FetchRow()){
					$sql_lojas_td .= ' (sa.codarm = \''.$rowP[0].'\') ';
				}
			}	
		} catch (Exception $e) {
			echo $e;
		}
	}
				
	if($modo == 'existencias'){

		$sql_query = "select ar.codart, ar.nome, sum(sa.qtd)
		from stk_artigos ar, dash_lojas dl, stk_saldos sa, stk_tabelas ta
		where sa.codemp = ".$_SESSION['codemp']."
		  and dl.codemp   = sa.codemp
		  and ta.codemp   = dl.codemp
		  and ar.codemp   = ta.codemp
		  and ta.tabela   = 'AR'
		  and ta.codigo   = dl.armazem
		  and sa.codarm   = ta.codigo
		  and sa.codart   = ar.codart
		  and (upper(ar.codart) like upper('%".$aprocurar."%')
				or upper(ar.nome) like upper('%".$aprocurar."%') )";
		  
		$sql_param = "";

		if($ano != 'todos')
			$sql_param .= " and sa.ano = '".$ano."' ";

		if($mes != 'todos')
			$sql_param .= " and sa.mes = '".$mes."' ";
		   
			$sqlGroupBy = " group by ar.codart, ar.nome order by ar.nome";
			  
			$sqlFinal =  " ".$sql_query." AND ".$sql_lojas_td." ".$sql_param." ".$sqlGroupBy;
				
		//VERIFICAR O MODO PARA SABER SE O ARRAY E CONSTRUIDO DE FORMA A SER UMA TABELA OU SE DEVE SER CONSTRUIDO DE FORMA A SER GRAFICO

		//if($modo == 'tabela'){
		$rs=$db->execute($sqlFinal);
		if($rs){
			$counter = 0;
			While($row = $rs->FetchRow()){
				if (is_null($row[0]))
					$datasets = null;
				else{
					$temp = array();
					$temp['codart'] = ($row[0]);
					$temp['nome'] 	= ($row[1]);
					$temp['qtd'] 	= str_replace(',','.',$row[2]);
					
					$datasets[$counter] = $temp;
					$counter = $counter +1;
				}
			}
		}
	
		echo json_encode($datasets);

	}
	
}
else{
	header("Location: ../../index.php");
}
	



?>