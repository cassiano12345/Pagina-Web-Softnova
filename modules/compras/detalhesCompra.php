<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$vendas 	 = array();

	$tipodoc 	= $_GET['tipodoc'];
	$serie 		= $_GET['serie'];
	$nroficial 	= $_GET['nroficial'];

	$sql_lojas_td   = '';
	$count   = 0;


	$sql_query =" select CA.NROFICIAL, CA.SERIE, CA.TIPODOC, CA.DATADOC, CA.NOME, nvl(CA.MORADA,' '), nvl(CA.CP,' '), nvl(CA.LOCALIDADE,' '), nvl(CA.NCONTRIB,'000000000'), nvl(CA.TOTALMERCIL,'0'),
							(nvl(CA.TOTALMERCLI,'0')*decode(ca.tipodoc,'DFR',-1,1)), nvl(CA.TOTALDESCLN,'0'), nvl(CA.IVA01VAL,'0'), CA.CODMOEDA, IT.DESART, IT.QTD, IT.PVN, nvl(IT.DSC1,'0'), IV.TAXA, nvl(CA.TOTAL,'0'),
							nvl(ca.iva01suj,0), nvl(ca.iva02suj,0), nvl(ca.iva03suj,0), nvl(ca.iva04suj,0),
							nvl(ca.iva01val,0), nvl(ca.iva02val,0), nvl(ca.iva03val,0), nvl(ca.iva04val,0), nvl(IT.DSC2,'0'), nvl(IT.DSC3,'0')
					FROM COMPRAS_CPCAB CA, COMPRAS_CPITEM IT, STK_IVA IV 
					WHERE
						  CA.CODEMP    = ".$_SESSION['codemp']."
					  AND IT.CODEMP    = CA.CODEMP
					  AND IV.CODEMP    = IT.CODEMP
					  AND CA.SERIE     = '".$serie."'
					  AND IT.SERIE     = CA.SERIE
					  AND CA.TIPODOC   = '".$tipodoc."'
					  AND IT.TIPODOC   = CA.TIPODOC
					  AND CA.NROFICIAL = '".$nroficial."'
					  AND IT.NROFICIAL = CA.NROFICIAL
					  AND IV.CODIGO    = IT.CODIVA
					  AND IV.ANO       = CA.ANODOC
			  ";


//echo $sql_query;


	$rs=$db->execute($sql_query);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$vendas = null;
			else{
				$temp = array();
				
				$temp['nroficial'] 		= $row[0];
				$temp['serie'] 			= $row[1];
				$temp['tipodoc'] 		= $row[2];
				$temp['datadoc'] 		= $row[3];
				$temp['fornecedor'] 	= ($row[4]);
				$temp['morada']  		= ($row[5]);
				$temp['codpostal'] 		= $row[6];
				$temp['localidade']  	= ($row[7]);
				$temp['ncontrib']  		= $row[8];
				$temp['totalmercil']  	= str_replace(',','.',$row[9]);
				$temp['totalmercli']  	= str_replace(',','.',$row[10]);
				$temp['totaldescln']  	= str_replace(',','.',$row[11]);
				$temp['valiva']  		= str_replace(',','.',$row[12]);
				$temp['codmoeda']  		= $row[13];
				$temp['desart']  		= str_replace(',','.',$row[14]);
				$temp['qtd']  			= ($row[15]);
				$temp['pvn']  			= str_replace(',','.',$row[16]);
				$temp['dsc']  			= str_replace(',','.',($row[17]+$row[28]+$row[29]));
				$temp['taxaiva']  		= str_replace(',','.',$row[18]);
				$temp['totalcompra'] 	= str_replace(',','.',$row[19]);
				$temp['iva01suj']  		= str_replace(',','.',$row[20]);
				$temp['iva02suj']  		= str_replace(',','.',$row[21]);
				$temp['iva03suj']  		= str_replace(',','.',$row[22]);
				$temp['iva04suj']  		= str_replace(',','.',$row[23]);
				$temp['iva01val']  		= str_replace(',','.',$row[24]);
				$temp['iva02val']  		= str_replace(',','.',$row[25]);
				$temp['iva03val']  		= str_replace(',','.',$row[26]);
				$temp['iva04val']  		= str_replace(',','.',$row[27]);
				
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