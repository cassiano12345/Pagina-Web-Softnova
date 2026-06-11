<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$vendas 	 = array();

	$tipodoc 	= $_GET['tipodoc'];
	$serie 		= $_GET['serie'];
	$nroficial 	= $_GET['nroficial'];
	$anodoc 	= $_GET['anodoc'];


	$count   = 0;
	$sql_query = '';

	if($tipodoc=='GRM'){

		$sql_query = "select  ce.tipodoc, ce.serie, ce.nroficial, ce.datadoc, ce.nome , it.desart, it.qtd, it.pvn, iv.taxa,  ce.nome,  ce.morada, ce.codmoeda, ce.total,
						nvl(ce.totalmercil,0), nvl(ce.totalmercli,0),nvl(ce.totaldescln,0),
						nvl(ce.iva01suj,0),nvl(ce.iva02suj,0), nvl(ce.iva03suj,0), nvl(ce.iva04suj,0),
						nvl(ce.iva01val,0), nvl(ce.iva02val,0), nvl(ce.iva03val,0), nvl(ce.iva04val,0),  nvl(ce.desc1,0), nvl(ce.desc2,0),
						nvl(it.dsc1,0), nvl(it.dsc2,0), nvl(it.dsc3,0), ce.isentoiva, nvl(ce.situacao, '')
							
				from vendas_GRcab ce, vendas_GRitem it, stk_iva iv 
				where  	ce.codemp    = ".$_SESSION['codemp']." 
						and ce.tipodoc   = '".$tipodoc."' 
						and ce.serie     = '".$serie."' 
						and ce.nroficial = ".$nroficial."  
					    and ce.anodoc 	 = ".$anodoc." 
						and it.codemp    = ce.codemp 
						and it.tipodoc 	 = ce.tipodoc 
						and it.serie 	 = ce.serie 
						and it.anodoc 	 = ce.anodoc 
						and it.nroficial = ce.nroficial 
					
						and iv.codemp    = it.codemp 
						and iv.codigo    = it.codiva 
						and iv.ano       = it.anodoc 
						
					order by ce.datadoc desc ";
	}
	else  {
		$sql_query ="select  ce.tipodoc, ce.serie, ce.nroficial, ce.datadoc, ce.nome , it.desart, it.qtd, it.pvn, iv.taxa,  ce.nome,  ce.morada, ce.codmoeda, ce.total,
						nvl(ce.totalmercil,0), nvl(ce.totalmercli,0),nvl(ce.totaldescln,0),
						nvl(ce.iva01suj,0),nvl(ce.iva02suj,0), nvl(ce.iva03suj,0), nvl(ce.iva04suj,0),
						nvl(ce.iva01val,0), nvl(ce.iva02val,0), nvl(ce.iva03val,0), nvl(ce.iva04val,0),  nvl(ce.desc1,0), nvl(ce.desc2,0),
						nvl(it.dsc1,0), nvl(it.dsc2,0), nvl(it.dsc3,0), ce.isentoiva, nvl(ce.situacao, '')
							
				from vendas_vdcab ce, vendas_vditem it, stk_iva iv 
				where  	ce.codemp    = ".$_SESSION['codemp']." 
						and ce.tipodoc   = '".$tipodoc."' 
						and ce.serie     = '".$serie."' 
						and ce.nroficial = ".$nroficial."  
					    and ce.anodoc 	 = ".$anodoc." 
						and it.codemp    = ce.codemp 
						and it.tipodoc 	 = ce.tipodoc 
						and it.serie 	 = ce.serie 
						and it.anodoc 	 = ce.anodoc 
						and it.nroficial = ce.nroficial 
					
						and iv.codemp    = it.codemp 
						and iv.codigo    = it.codiva 
						and iv.ano       = it.anodoc 
						
					order by ce.datadoc desc ";
	}

					  
	
	
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
				$temp['nome']  			= ($row[4]);
					//linhas
				$temp['desart'] 		= ($row[5]);
				$temp['qtd']  			= str_replace(',','.',$row[6]);
				$temp['pvn']  			= str_replace(',','.',$row[7]);
				$temp['taxa']  			= str_replace(',','.',$row[8]);
				$temp['nome']  			= ($row[9]);
				$temp['morada']  		= ($row[10]);
				
				$temp['codmoeda']  		= $row[11];
				//totais_doc
				$temp['total']  		= str_replace(',','.',$row[12]);
				$temp['totalmercil'] 	= str_replace(',','.',$row[13]);
				$temp['totalmercli']  	= str_replace(',','.',$row[14]);
				$temp['totaldescln']  	= str_replace(',','.',$row[15]);
				$temp['iva01suj']  		= str_replace(',','.',$row[16]);
				$temp['iva02suj']  		= str_replace(',','.',$row[17]);
				$temp['iva03suj']  		= str_replace(',','.',$row[18]);
				$temp['iva04suj']  		= str_replace(',','.',$row[19]);
				$temp['iva01val']  		= str_replace(',','.',$row[20]);
				$temp['iva02val']  		= str_replace(',','.',$row[21]);
				$temp['iva03val']  		= str_replace(',','.',$row[22]);
				$temp['iva04val']  		= str_replace(',','.',$row[23]);
				
				$temp['iva03val']  		= str_replace(',','.',$row[24]);
				$temp['iva04val']  		= str_replace(',','.',$row[25]);
				
				//descontolinhas
				
				$temp['dsc1']  	= str_replace(',','.',floatval($row[26]));
				$temp['dsc2']  	= str_replace(',','.',floatval($row[27]));
				$temp['dsc3']  	= str_replace(',','.',floatval($row[28]));
				
				$temp['isentoiva'] = ($row[29]);
				$temp['situacao'] = $row[30];
				
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