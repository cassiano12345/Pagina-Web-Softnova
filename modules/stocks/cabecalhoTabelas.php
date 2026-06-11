<?php
error_reporting(E_ALL);
ini_set("display_errors",1);

include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$datasets 	 = array();
	//$aprocurar 	 = $_GET['aprocurar'];
	$nomeloja 	 = $_GET["nomeloja"];
	/*
	$ano		 = $_GET["ano"];
	$mes 		 = $_GET["mes"];
	*/
	$modo 		 = $_GET["modo"];
	$datalimite  = $_GET["datalimite"];
	
	$sql_lojas_td   = '';
	$count   = 0;

	//echo " ".$nomeloja." - ".$ano." - ".$mes." - ".$modo." <br> ";



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
								$sql_lojas_td .= '(codarm = \''.$rowP[0].'\')';	
							} else {
								$sql_lojas_td .= ' OR (codarm = \''.$rowP[0].'\')';	
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
			$rsP=$db->execute('Select dl.armazem from dash_lojas dl where dl.codemp = '.$_SESSION["codemp"].' and dl.id='.$nomeloja.'');
			if($rsP){
				While($rowP = $rsP->FetchRow()){
					$sql_lojas_td .= ' (codarm = \''.$rowP[0].'\') ';
				}
			}	
		} catch (Exception $e) {
			echo $e;
		}
	}
				
	if($modo == 'existencias'){
		$sqlQuery = "";
		$sqlQuery = "
						select m.codart, m.codarm, a.nome, max(m.data), nvl(sum(decode(m.sinal,'+',m.qtd,-m.qtd)),0) , nvl(sum(decode(m.sinal,'+',m.qtd2,-m.qtd2)),0), nvl(sum(decode(m.sinal,'+',m.qtd3,-m.qtd3)),0)
						from stk_mov m, stk_artigos a
						where m.codemp = ".$_SESSION['codemp']."
						and m.codart = a.codart
						and ".$sql_lojas_td." 
						and to_char(data,'yyyymmdd') < to_char(to_date('".$datalimite."','dd/mm/yyyy'), 'yyyymmdd')
						group by m.codart, m.codarm, a.nome
						order by m.codart ";
		
		//echo $sqlQuery;
		$rs=$db->execute($sqlQuery);
		if($rs){
			$counter = 0;
			While($row = $rs->FetchRow()){
				if (is_null($row[0]))
					$datasets = null;
				else{		
					$sqlLoja = "select nome from dash_lojas where codemp =".$_SESSION['codemp']." and armazem='".$row[1]."'";
					$rsS=$db->execute($sqlLoja);
					if($rsS){
						While($rowZ = $rsS->FetchRow()){
							$temp = array();
							$temp['codart'] = $row[0];
							$temp['loja'] 	= ($rowZ[0]);
							$temp['nome'] 	= ($row[2]);
							$temp['ultmov'] = $row[3];
							$temp['qtd'] 	= str_replace(',','.',$row[4]);
							
							$datasets[$counter] = $temp;
						}
					}
							
					$counter = $counter +1;
				}
			}
		}
		echo json_encode($datasets);
	
	} else if($modo == 'rotura'){
		//UTILIZAR O PROCEDIMENTO QUE ESTA NA BASE DE DADOS
		
		$partini 	= "0";
		$partfim 	= "ZZZZZZZ";
		$parmini 	= "0";
		$parmfim 	= "ZZZZ";
		
		$datalimite = str_replace('/', '-', $datalimite);
		$pdatadia = date("Y/m/d", strtotime($datalimite));
		
		
		
		
		//$sql = 'BEGIN cria_saldosecefstar(:codemp,:partini, :partfim, :parmini, :parmfim, :pdatadia, :version); END;';
		

		$sql = "BEGIN cria_saldosecefstar(".$_SESSION['codemp'].",'".$partini."', '".$partfim."', '".$parmini."', '".$parmfim."', to_date('".$datalimite."','dd/mm/yyyy'), :version); END;";

        

		$conn = OCILogon($userOCI, $passOCI, $connOCI);
		$stmt = OCIparse($conn, $sql);
		//in
		//oci_bind_by_name($stmt, ":codemp", $_SESSION['codemp']);
		//oci_bind_by_name($stmt, ":partini", $partini);
		//oci_bind_by_name($stmt, ":partfim", $partfim);
		//oci_bind_by_name($stmt, ":parmini", $parmini);
		//oci_bind_by_name($stmt, ":parmfim", $parmfim);
		//i_bind_by_name($stmt, ":pdatadia", $pdatadia);
		//out
		oci_bind_by_name($stmt,":version",$version, 100);
		$res = OCIExecute($stmt);
		
		
		//UMA VEZ GERADA A TABELA TEMPORARIA DEVE SER LIDA
		
		$sql_lojas_td = str_replace('codarm', 'tp.codarm',$sql_lojas_td);
					
		$sql = "select tp.codart, ar.nome, tp.codarm, dl.nome, ta.nome, nvl(sum(tp.qtdstk),0), nvl(sum(tp.qtdecl),0), nvl(sum(tp.qtdref),0), nvl(sum(tp.qtd2),0)
					from temp_saldosecefst tp, stk_artigos ar, stk_tabelas ta, dash_lojas dl
					where ar.codemp 	= ".$_SESSION['codemp']."
						and   tp.codemp 	= ".$version."
						and   ta.codemp = ar.codemp
						and   dl.codemp = ta.codemp
						
						and   ".$sql_lojas_td." 
            
						and	  tp.codart = ar.codart
						and   ta.tabela 	= 'AR' 
						and   tp.codarm  = ta.codigo
						and   dl.armazem = ta.codigo
						
				group by tp.codarm, ta.nome, tp.codart,dl.nome, ar.nome
				order by tp.codarm desc";
					
					
		$rs	 = $db->execute($sql);
		if($rs){
			$counter = 0;
			while($linha = $rs->FetchRow()){
				if (is_null($linha[0])){
					$datasets = null;
				}else{
					$temp = array();
					$temp['codart'] 	= ($linha[0]);
					$temp['nome'] 		= ($linha[1]);
					$temp['codarm'] 	= $linha[2];
					$temp['loja'] 		= ($linha[3]);
					$temp['armazem'] 	= ($linha[4]);
					$temp['qtdstk'] 	= str_replace(',','.',$linha[5]);
					$temp['qtdecl'] 	= str_replace(',','.',$linha[6]);
					$temp['qtdref'] 	= str_replace(',','.',$linha[7]);
					$temp['qtd2'] 		= str_replace(',','.',$linha[8]);
					$rotura 			= ($linha[5] - $linha[8] - $linha[6] + $linha[7]); //cálculo da rotura
					$temp['rotura'] 	= $rotura;
					
					$datasets[$counter] = $temp;
				}
				$counter ++;
			}
		}
		echo json_encode($datasets);
	
	//Stock Disponivel
	} else if($modo == 'disponivel'){
		
		
		$partini 	= "0";
		$partfim 	= "zz";
		$parmini 	= "0";
		$parmfim 	= "zz";
		$time 	= strtotime($datalimite);
		
		$datalimite = str_replace('/', '-', $datalimite);
		$pdatadia = date("Y/m/d", strtotime($datalimite));
		
		
		//$sql = 'BEGIN cria_saldosecefstar(:codemp,:partini, :partfim, :parmini, :parmfim, :pdatadia, :version); END;';
		$sql = "BEGIN cria_saldosecefstar(".$_SESSION['codemp'].",'".$partini."', '".$partfim."', '".$parmini."', '".$parmfim."', to_date('".$datalimite."','dd/mm/yyyy'), :version); END;";


		$conn = OCILogon($userOCI, $passOCI, $connOCI);
		$stmt = OCIparse($conn, $sql);
		//in
		//oci_bind_by_name($stmt, ":codemp", $_SESSION['codemp']);
		//oci_bind_by_name($stmt, ":partini", $partini);
		//oci_bind_by_name($stmt, ":partfim", $partfim);
		//oci_bind_by_name($stmt, ":parmini", $parmini);
		//oci_bind_by_name($stmt, ":parmfim", $parmfim);
		//oci_bind_by_name($stmt, ":pdatadia", $pdatadia);
		//out
		oci_bind_by_name($stmt,":version",$version, 100);
		$res = OCIExecute($stmt);
		
		
		//UMAA VEZ GERADA A TABELA TEMPORARIA DEVE SER LIDA
		
		$sql_lojas_td = str_replace('codarm', 'tp.codarm',$sql_lojas_td);
					
		$sql = "select tp.codart, ar.nome, tp.codarm, dl.nome, ta.nome, nvl(sum(tp.qtdstk),0), nvl(sum(tp.qtdecl),0), nvl(sum(tp.qtdref),0), nvl(sum(tp.qtd2),0)
					from temp_saldosecefst tp, stk_artigos ar, stk_tabelas ta, dash_lojas dl
					where ar.codemp 	= ".$_SESSION['codemp']."
						and   tp.codemp 	= ".$version."
						and   ta.codemp = ar.codemp
						and   dl.codemp = ta.codemp
						
						and   ".$sql_lojas_td." 
            
						and	  tp.codart = ar.codart
						and   ta.tabela 	= 'AR' 
						and   tp.codarm = ta.codigo
						and   dl.armazem = ta.codigo
						 
				group by tp.codarm, ta.nome, tp.codart,dl.nome, ar.nome
				order by tp.codarm desc";
					
					
		
					
					
		$rs	 = $db->execute($sql);
		if($rs){
			$counter = 0;
			while($linha = $rs->FetchRow()){
				if (is_null($linha[0])){
					$datasets = null;
				}else{
					$temp = array();
					$temp['codart'] 	= $linha[0];
					$temp['nome'] 		= ($linha[1]);
					$temp['codarm'] 	= ($linha[2]);
					$temp['loja'] 		= ($linha[3]);
					$temp['armazem'] 	= $linha[4];
					
					//if($linha[0] == '0002')
					//	echo "qtdstk "+ $linha[5];
					
					$temp['qtdstk'] 	= str_replace(',','.',$linha[5]);
					$temp['qtdecl'] 	= str_replace(',','.',$linha[6]);
					$temp['qtdref'] 	= str_replace(',','.',$linha[7]);
					$temp['qtd2'] 		= str_replace(',','.',$linha[8]);
					
					
					$disponivel 		= ($linha[5] - $linha[6] + $linha[7]); //cálculo da disponibilidade
					
					
					$temp['disponivel'] = str_replace(',','.',$disponivel);
					
					$datasets[$counter] = $temp;
				}
				$counter ++;
			}
		}
		echo json_encode($datasets);
	
	}
	
}
else{
	header("Location: ../../index.php");
}

?>