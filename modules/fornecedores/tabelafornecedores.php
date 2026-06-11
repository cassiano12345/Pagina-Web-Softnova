<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$clientes 	 = array();

	$dataini 	 = $_GET['dataini'];
	$datafim 	 = $_GET['datafim'];
	$cg 		 = $_GET['cg'];
	$codig		 = $_GET['codigo'];
	$tipoPesquisa = $_GET['tipoPesquisa'];


	$sql_query = "";

	$sql_query ="
SELECT datadoc, dvc, nrdiario, nrlancto, descmovcnt, nrdocofic, serie, vld, vlc, grupo FROM CTB_MOV M 
WHERE M.CODEMP = ".$_SESSION['codemp']." and
M.plano  in ('G', 'J')
and M.CG = " .$cg. "
AND  M.CX =" .$codig. "
AND M.DATAMOV BETWEEN TO_DATE('".$dataini."', 'DD.MM.YYYY') AND TO_DATE('".$datafim."', 'DD.MM.YYYY') AND

(('" .$tipoPesquisa. "' = '2') or
 ('" .$tipoPesquisa. "' = 'J' and M.Plano = 'J') or

 ((  ('" .$tipoPesquisa. "' = 'J' and M.Plano = 'J') or
 
                             '" .$tipoPesquisa. "' = '2' or
							 
                             ('" .$tipoPesquisa. "' in ('1','3') and M.Grupo is null) or 
							 
                            ('" .$tipoPesquisa. "' in ('1','3') and						
							
							M.Vld>0 and 
							
((             M.Grupo in (select grupo  
                          from ctb_mov m1  
                          where m1.codemp = ".$_SESSION['codemp']." and   
                                m1.plano = 'G' and  
                                m1.cg = " .$cg. " and 
                                m1.cx = " .$codig. " and 
                                m1.grupo = M.Grupo and 
                                m1.grupo <> 9999999 
                          group by m1.grupo    
                          having sum(m1.vld-m1.vlc)<>0) or
						  
(M.Vlc > 0 and M.Grupo not in (select m2.Grupo
                               from CTB_Mov m2
                               where m2.CodEmp = ".$_SESSION['codemp']." and
                                     m2.Plano = 'G' and
                                     m2.CG = " .$cg. " and
                                     m2.CX = " .$codig. " and
                                     m2.Grupo <> 9999999 and
                                     m2.Vld > 0
                               group by m2.Grupo)) or
							   
(M.Vld > 0 and M.Grupo not in (select m3.Grupo
                               from CTB_Mov m3
                               where m3.CodEmp = ".$_SESSION['codemp']." and
                                     m3.Plano = 'G' and
                                     m3.CG = " .$cg. " and
                                     m3.CX = " .$codig. " and
                                     m3.Grupo <> 9999999 and
                                     m3.Vlc > 0 
                                group by m3.Grupo)) ) 
)))))

ORDER BY DataMov,MesMov,NrDiario,NrLancto,CodTipoDoc,NrDocOfic


	";

	$rs=$db->execute($sql_query);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$clientes = null;
			else{
				$temp = array();
				$temp['datadoc']	= $row[0];
				$temp['dvc'] 		= $row[1];
				$temp['nrdiario']	= $row[2];
				$temp['nrlancto'] 	= $row[3];
				$temp['descmovcnt']	= $row[4];
				$temp['nrdocofic']	= $row[5];
				$temp['serie']	= $row[6];
				$temp['vld']	= $row[7];
				$temp['vlc']	= $row[8];
				$temp['grupo']	= $row[9];
				
				$clientes[$counter] = $temp;
				$counter = $counter +1;
			}
		}
	}
	
	
	//echo("<script>console.log('PHP: " . $rs . "');</script>");
	echo json_encode($clientes);

}else{
	header("Location: ../../index.php");
}

?>