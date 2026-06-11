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


$sql_query ="select ca.tipodoc, ca.serie, ca.nroficial, ca.datadoc, ca.nrcliente, ut.nome, it.desart, it.qtd, it.pvn, iv.taxa, cl.nome, nvl(cl.mor,' '), nvl(cl.email, ' '), it.codmoeda, ca.total,
				 nvl(ca.totalmercil,0), nvl(ca.totalmercli,0), nvl(ca.totaldescln,0), nvl(ca.iva01suj,0), nvl(ca.iva02suj,0), nvl(ca.iva03suj,0), nvl(ca.iva04suj,0),
			     nvl(ca.iva01val,0), nvl(ca.iva02val,0), nvl(ca.iva03val,0), nvl(ca.iva04val,0), nvl(it.dsc1,0), nvl(it.dsc2,0), nvl(it.dsc3,0) , nvl(ca.situacao,' ')
			from vendas_vdcab ca, vendas_vditem it, stk_iva iv, ctb_cl cl, ctb_vendedor ut
			where 	  ca.codemp    = ".$_SESSION['codemp']."
				  and it.codemp    = ca.codemp
				  and iv.codemp    = it.codemp
				  and cl.codemp	   = iv.codemp
				  and ut.codemp    = cl.codemp 
				  and ca.nroficial = ".$nroficial."
				  and it.nroficial = ca.nroficial
				  and ca.serie     = '".$serie."'
				  and it.serie     = ca.serie 
				  and ca.tipodoc   = '".$tipodoc."'
				  and it.tipodoc   = ca.tipodoc
				  and iv.codigo    = it.codiva 
				  and iv.ano       = ca.anodoc
				  and cl.nrcl      = it.nrcliente
				  and ut.nrvd	   = ca.vendedor
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
				//cab
				$temp['tipodoc'] 		= $row[0];
				$temp['serie'] 			= $row[1];
				$temp['nroficial'] 		= $row[2];
				$temp['datadoc'] 		= $row[3];
				$temp['nrcliente'] 		= $row[4];
				$temp['vendedor']  		= ($row[5]);
				//linhas
				$temp['desart'] 		= ($row[6]);
				$temp['qtd']  			= str_replace(',','.',$row[7]);
				$temp['pvn']  			= str_replace(',','.',$row[8]);
				$temp['taxa']  			= str_replace(',','.',$row[9]);
				$temp['cliente']  		= ($row[10]);
				$temp['morada']  		= ($row[11]);
				$temp['email']  		= $row[12];
				$temp['codmoeda']  		= $row[13];
				//totais_doc
				$temp['total']  		= str_replace(',','.',$row[14]);
				$temp['totalmercil'] 	= str_replace(',','.',$row[15]);
				$temp['totalmercli']  	= str_replace(',','.',$row[16]);
				$temp['totaldescln']  	= str_replace(',','.',$row[17]);
				$temp['iva01suj']  		= str_replace(',','.',$row[18]);
				$temp['iva02suj']  		= str_replace(',','.',$row[19]);
				$temp['iva03suj']  		= str_replace(',','.',$row[20]);
				$temp['iva04suj']  		= str_replace(',','.',$row[21]);
				$temp['iva01val']  		= str_replace(',','.',$row[22]);
				$temp['iva02val']  		= str_replace(',','.',$row[23]);
				$temp['iva03val']  		= str_replace(',','.',$row[24]);
				$temp['iva04val']  		= str_replace(',','.',$row[25]);
				
				//descontolinhas
				
				$temp['desclinha1']  	= str_replace(',','.',floatval($row[26]));
				$temp['desclinha2']  	= str_replace(',','.',floatval($row[27]));
				$temp['desclinha3']  	= str_replace(',','.',floatval($row[28]));
				
				$temp['situacao']		= $row[29];
				
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