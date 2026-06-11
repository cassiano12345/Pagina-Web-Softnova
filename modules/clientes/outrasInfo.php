<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

	$tag = $_GET['tag'];
	
	
	
	switch($tag){
		case 'pais':
			$response = array();
			$pais = $_GET['pais'];
			$sql_pais = "Select Nome from soft_tabelas where Codigo = '".$pais."' and tabela = 'PAI'";
			$rs = $db->execute($sql_pais);
			if($rs){
				While($row = $rs->FetchRow()){
					$temp = array();
					$temp['pais'] 	= ($row[0]);
					$response[0] = $temp;
					break;
				}
			}
			echo json_encode($response);
		break;
		
		case 'contatos':
			$response = array();
			$nrcliente = $_GET['nrcl'];
			$sql_contatos = "Select nome, nvl(cargo,'N/A'), nvl(tlm,'N/A'), nvl(email,'N/A'), nvl(obs,'') from ctb_clcontactos where codemp = ".$_SESSION['codemp']." and  nrcl=".$nrcliente;
			//echo $sql_contatos;
			$rs = $db->execute($sql_contatos);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
					$temp = array();
					$temp['nome'] 	= ($row[0]);
					$temp['cargo'] 	= ($row[1]);
					$temp['tlm'] 	= $row[2];
					$temp['email'] 	= $row[3];
					$temp['obs'] 	= ($row[4]);
					$response[$counter] = $temp;
					$counter = $counter + 1;
				}
			}
			echo json_encode($response);
		break;
	
		case 'anoconst':
			$response = array();
			$sql_anconst = "select anoconst from soft_empresa where codemp = ".$_SESSION['codemp'];

			$rs = $db->execute($sql_anconst);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
						$temp = array();
						$temp['anoconst'] 	= $row[0];
						$response[$counter] = $temp;
						$counter = $counter + 1;
				}
			}
			echo json_encode($response);
		break;
	
	}
	
} else{
	header("Location: ../../index.php");
}
	
?>