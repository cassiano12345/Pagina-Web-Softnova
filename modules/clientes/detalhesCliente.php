<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

	$cliente 	= $_GET['cliente'];
	$mode 		= $_GET['mode'];
	
	
	switch($mode){
		case 'det':
			$clientes 	 = array();
			$sql_clientes = "Select nvl(nome, 'N/A'), nrcl, nvl(mor,'N/A'), nvl(sub,'N/A'), nvl(cp,'N/A'), nvl(loc,'N/A'), data_reg, nvl(pais,'N/A'), nvl(tel,'N/A'),
								nvl(fax,'N/A'), nvl(tlm,'N/A'), nvl(email,'N/A'), nvl(contacto,'N/A'), nvl(limite_credito,'0'), nvl(valseguro,'0'), nvl(codmoeda,'N/A'),
								nvl(idioma,'N/A'), nvl(nct ,'N/A'), cp.descritivo
							from ctb_cl cl, ctb_tabcf cp
							where cl.codemp 		=  ".$_SESSION['codemp']." 
							  and cl.nrcl   	=  '".$cliente."' 
							  and cp.codemp 	=  ".$_SESSION['codemp']." 
							  and cp.tabela 	=  'CL02' 
							  and cp.codigo   	=  cl.cpg";

		  					  
			$rs = $db->execute($sql_clientes);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
						$temp = array();
						$temp['nome'] 			= ($row[0]);
						$temp['nrcl'] 			= $row[1];
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
						$temp['contacto'] 		= ($row[12]);
						$temp['limite_credito'] = $row[13];
						$temp['valseguro'] 		= $row[14];
						$temp['codmoeda'] 		= $row[15];
						$temp['idioma'] 		= $row[16];
						$temp['contribuinte'] 	= $row[17];
						$temp['condpag'] 		= $row[18];
						
						
						$clientes[$counter] = $temp;
						$counter = $counter +1;
				}
			}
			echo json_encode($clientes);
			break;
		
		case 'vendassoc':
			$clientes 	 = array();
			$sql_clientes = "select nvl(vd.nome, 'Sem vendedor associado')
								from ctb_cl cl, ctb_vendedor vd
								where cl.codemp = ".$_SESSION['codemp']."
								  and vd.codemp = cl.codemp
								  and cl.nrcl = ".$cliente."
								  and vd.nrvd = cl.vendedor";

		  					  
			$rs = $db->execute($sql_clientes);
			if($rs){
				$counter = 0;
				While($row = $rs->FetchRow()){
						$temp = array();
						$temp['vendassoc'] 	= ($row[0]);						
						$clientes[$counter] = $temp;
						$counter = $counter +1;
				}
			}
			echo json_encode($clientes);
			break;
		break;
	}
} else{
	header("Location: ../../index.php");
}
	
?>