<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

	$fornecedor = $_GET['fornecedor'];
	$fornecedores 	 = array();
	$sql_fornecedores = "Select nvl(nome, 'N/A'), nrfr, nvl(mor,'N/A'), nvl(sub,'N/A'), nvl(cp,'N/A'), nvl(loc,'N/A'), data_reg, nvl(pais,'N/A'), nvl(tel,'N/A'),
						nvl(fax,'N/A'), nvl(tlm,'N/A'), nvl(email,'N/A'), nvl(contacto,'N/A'), nvl(nib,'0'), nvl(codmoeda,'N/A'), nvl(idioma,'N/A'),
						nvl(nct ,'N/A')
						from ctb_fr where codemp=".$_SESSION['codemp']." and nrfr = ('".$fornecedor."')";

	//echo $sql_fornecedores;
						
	$rs = $db->execute($sql_fornecedores);

	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
				$temp = array();
				$temp['nome'] 			= ($row[0]);
				$temp['nrfr'] 			= $row[1];
				$temp['mor'] 			= ($row[2]);
				$temp['sub'] 			= ($row[3]);
				$temp['cp'] 			= $row[4];
				$temp['loc'] 			= ($row[5]);
				$temp['data_reg'] 		= $row[6];
				$temp['pais'] 			= ($row[7]);
				$temp['tel'] 			= $row[8];
				$temp['fax'] 			= $row[9];
				$temp['tlm'] 			= $row[10];
				$temp['email'] 			= $row[11];
				$temp['contacto'] 		= $row[12];
				$temp['nib'] 			= $row[13];
				$temp['codmoeda'] 		= $row[14];
				$temp['idioma'] 		= $row[15];
				$temp['contribuinte'] 	= $row[16];
				
				$fornecedores[$counter] = $temp;
				$counter = $counter +1;
		}
	}

	echo json_encode($fornecedores);

} else{
	header("Location: ../../index.php");
}
	
?>