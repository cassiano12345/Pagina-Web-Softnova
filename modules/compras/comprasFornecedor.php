<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

	
	$tag = $_GET['tag'];
	
	
	
	switch($tag){
		case 'allDocs':
			$nrfornecedor = $_GET['nrfr'];
			$response = array();
					
			$sql_docs = "select ca.tipodoc, ca.serie, ca.nroficial, ca.datadoc, ca.codmoeda, ca.nrfornecedor, ca.nome, ca.morada, ca.sublocal, ca.cp, 
								ca.localidade, ca.ncontrib, ca.loccar, ca.meiotransp, ca.moradadesc, ca.sublocaldesc, ca.cpdesc, ca.localdesc, 
								ca.totalmercil, ca.totaldescln, ca.totalmercli, ca.iva01taxa, ca.iva02taxa, ca.iva03taxa, ca.iva04taxa, ca.iva01suj,
								ca.iva02suj, ca.iva03suj, ca.iva04suj,  ca.iva01val, ca.iva02val, ca.iva03val, ca.iva04val, ca.total, ca.datacarga,
								it.desart, it.qtd, it.umd, it.pvn, it.dsc1, it.dsc2, it.dsc3, it.valoril, it.valorli, it.codiva, it.taxaiva
						from compras_cpcab ca, compras_cpitem it
						where ca.codemp = ".$_SESSION['codemp']."
						  and it.codemp = ca.codemp
						  and it.serie = ca.serie
						  and it.nroficial = ca.nroficial
						  and it.tipodoc = ca.tipodoc
						  and ca.nrfornecedor =  ".$nrfornecedor."
						order by tipodoc, datadoc desc, nroficial desc ";
						
			//echo $sql_docs;
						
			$rs = $db->execute($sql_docs);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
						$temp = array();
						$temp['tipodoc'] 		= $row[0];
						$temp['serie'] 			= $row[1];
						$temp['nroficial']	 	= $row[2];
						$temp['datadoc'] 		= $row[3];
						$temp['codmoeda'] 		= $row[4];
						$temp['nrfornecedor'] 	= $row[5];
						$temp['nome'] 			= ($row[6]);
						$temp['morada'] 		= ($row[7]);
						$temp['sublocalidade'] 	= ($row[8]);
						$temp['codpostal'] 		= $row[9];
						$temp['localidade'] 	= ($row[10]);
						$temp['ncontrib'] 		= $row[11];
						$temp['loccar'] 		= ($row[12]);
						$temp['matricula'] 		= $row[13];
						$temp['moradadesc'] 	= ($row[14]);
						$temp['sublocaldesc'] 	= ($row[15]);
						$temp['codpostaldesc'] 	= $row[16];
						$temp['localdesc'] 		= ($row[17]);
						$temp['totalmercil'] 	= str_replace(',','.',$row[18]);
						$temp['totaldescln'] 	= str_replace(',','.',$row[19]);
						$temp['totalmercli'] 	= str_replace(',','.',$row[20]);
						$temp['iva01taxa'] 		= str_replace(',','.',$row[21]);
						$temp['iva02taxa'] 		= str_replace(',','.',$row[22]);
						$temp['iva03taxa'] 		= str_replace(',','.',$row[23]);
						$temp['iva04taxa'] 		= str_replace(',','.',$row[24]);
						$temp['iva01suj'] 		= str_replace(',','.',$row[25]);
						$temp['iva02suj'] 		= str_replace(',','.',$row[26]);
						$temp['iva03suj'] 		= str_replace(',','.',$row[27]);
						$temp['iva04suj'] 		= str_replace(',','.',$row[28]);
						$temp['iva01val'] 		= str_replace(',','.',$row[29]);
						$temp['iva02val'] 		= str_replace(',','.',$row[30]);
						$temp['iva03val'] 		= str_replace(',','.',$row[31]);
						$temp['iva04val'] 		= str_replace(',','.',$row[32]);
						$temp['total'] 			= str_replace(',','.',$row[33]);
						$temp['datacarga'] 		= $row[34];
						$temp['datadesc'] 		= $row[35];
						$temp['descvenc'] 		= $row[36];
						$temp['desart'] 		= ($row[37]);
						$temp['qtd'] 			= str_replace(',','.',$row[38]);
						$temp['umd'] 			= $row[39];
						$temp['pvn'] 			= str_replace(',','.',$row[40]);
						$temp['dsc1'] 			= str_replace(',','.',$row[41]);
						$temp['dsc2'] 			= str_replace(',','.',$row[42]);
						$temp['dsc3'] 			= str_replace(',','.',$row[43]);
						$temp['valoril'] 		= str_replace(',','.',$row[44]);
						$temp['valorli'] 		= str_replace(',','.',$row[45]);
						$temp['codiva'] 		= $row[46];
						$temp['taxaiva'] 		= str_replace(',','.',$row[47]);
						$response[$counter] = $temp;
						$counter = $counter +1;
				}
			}
			echo json_encode($response);
		break;
	
		case 'cab':
			$nrfornecedor = $_GET['nrfr'];
			$response = array();
						
			$sql_docs = "select ca.tipodoc, ca.serie, ca.nroficial, ca.datadoc, ca.codmoeda, ca.nrfornecedor, ca.nome, ca.morada, ca.sublocal, ca.cp, 
								ca.localidade, ca.ncontrib, ca.loccar, ca.meiotransp, ca.moradadesc, ca.sublocaldesc, ca.cpdesc, ca.localdesc, 
								ca.totalmercil, ca.totaldescln, ca.totalmercli, ca.iva01taxa, ca.iva02taxa, ca.iva03taxa, ca.iva04taxa, ca.iva01suj,
								ca.iva02suj, ca.iva03suj, ca.iva04suj,  ca.iva01val, ca.iva02val, ca.iva03val, ca.iva04val, ca.total, ca.datacarga
						from compras_cpcab ca
						where ca.codemp = ".$_SESSION['codemp']."
						  and ca.nrfornecedor = ".$nrfornecedor."
						order by tipodoc, datadoc desc, nroficial desc";
            
						
			//echo $sql_docs;
						
			$rs = $db->execute($sql_docs);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
						$temp = array();
						$temp['tipodoc'] 		= $row[0];
						$temp['serie'] 			= $row[1];
						$temp['nroficial']	 	= $row[2];
						$temp['datadoc'] 		= $row[3];
						$temp['codmoeda'] 		= $row[4];
						$temp['nrfornecedor'] 		= $row[5];
						$temp['nome'] 			= ($row[6]);
						$temp['morada'] 		= ($row[7]);
						$temp['sublocalidade'] 	= ($row[8]);
						$temp['codpostal'] 		= $row[9];
						$temp['localidade'] 	= ($row[10]);
						$temp['ncontrib'] 		= $row[11];
						$temp['loccar'] 		= ($row[12]);
						$temp['matricula'] 		= ($row[13]);
						$temp['moradadesc'] 	= ($row[14]);
						$temp['sublocaldesc'] 	= ($row[15]);
						$temp['codpostaldesc'] 	= $row[16];
						$temp['localdesc'] 		= ($row[17]);
						$temp['totalmercil'] 	= str_replace(',','.',$row[18]);
						$temp['totaldescln'] 	= str_replace(',','.',$row[19]);
						$temp['totalmercli'] 	= str_replace(',','.',$row[20]);
						$temp['iva01taxa'] 		= ($row[21]);
						$temp['iva02taxa'] 		= ($row[22]);
						$temp['iva03taxa'] 		= ($row[23]);
						$temp['iva04taxa'] 		= ($row[24]);
						$temp['iva01suj'] 		= ($row[25]);
						$temp['iva02suj'] 		= ($row[26]);
						$temp['iva03suj'] 		= ($row[27]);
						$temp['iva04suj'] 		= ($row[28]);
						$temp['iva01val'] 		= ($row[29]);
						$temp['iva02val'] 		= ($row[30]);
						$temp['iva03val'] 		= ($row[31]);
						$temp['iva04val'] 		= ($row[32]);
						$temp['total'] 			= ($row[33]);
						$temp['datacarga'] 		= $row[34];
						$temp['datadesc'] 		= $row[35];
						$temp['descvenc'] 		= str_replace(',','.',$row[36]);
						$response[$counter] = $temp;
						$counter = $counter +1;
				}
			}
			echo json_encode($response);
		break;
	
	}
	
} else{
	header("Location: ../../index.php");
}
	
?>