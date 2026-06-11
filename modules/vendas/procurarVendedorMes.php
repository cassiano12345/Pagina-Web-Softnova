<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$vendas 	 	= array();
	$loja 			= $_GET['loja'];
	if (isset($_GET['dataini'])){
		$dataini 	= $_GET['dataini'];
	}
	else{
		$dataini 	= "";
	}
	if (isset($_GET['datafim'])){
		$datafim 	= $_GET['datafim'];
	}
	else{
		$datafim 	= "";
	}			
	$aprocurar 	 	= $_GET['aprocurar'];

	$mes 	    	= $_GET['mes'];
	$ano  			= $_GET['ano'];
	$dias 			= $_GET['dias'];

	if(strlen($mes)<2)
			$mes = '0'.$mes;
			
	$dataini = '01/'.$mes.'/'.$ano;
	$datafim = $dias.'/'.$mes.'/'.$ano;


	$sql_query = "";



	if ($loja == 'todos') {

		try{
						
				$sql_query = "select  ca.vendedor, ve.nome, sum((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0))*decode(ca.tipodoc,'DCL',-1,1)),ca.codmoeda, it.codarm, dl.nome
					from vendas_vdcab ca, vendas_vditem it,  ctb_vendedor ve, dash_lojas dl 
					where ca.codemp 	= ".$_SESSION['codemp']."
					  /*and ca.tipodoc  	<> 'DCL' 
					  and ca.tipodoc  	<> 'FSC' 
					  and ca.tipodoc  	<> 'NCC'*/ 
					  and nvl(ca.situacao, '') <> 'A' 
					  and TO_CHAR(ca.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
															 and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
					  and it.codemp 	= ca.codemp
					  and it.tipodoc 	= ca.tipodoc
					  and it.serie 		= ca.serie
					  and it.nroficial 	= ca.nroficial 
					  and it.datadoc 	= ca.datadoc
					  and (upper(ve.nome) like upper('%".$aprocurar."%'))
					  and dl.codemp 	= ca.codemp
					  and dl.armazem 	= it.codarm
					  and ve.codemp 	= ca.codemp
					  and ve.nrvd 		= ca.vendedor
					group by ca.vendedor, ve.nome, ca.codmoeda, it.codarm, dl.nome 
					order by ve.nome asc, dl.nome asc";
	  
		} catch (Exception $e) {
			echo $e;
		}
		
	} 
	else{
		try{
				
			$sql_query = "select  ca.vendedor, ve.nome, sum((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0))*decode(ca.tipodoc,'DCL',-1,1)),ca.codmoeda, it.codarm, dl.nome
					from vendas_vdcab ca, vendas_vditem it,  ctb_vendedor ve, dash_lojas dl 
					where ca.codemp = ".$_SESSION['codemp']."
					  /*and ca.tipodoc  	<> 'DCL' 
					  and ca.tipodoc  	<> 'FSC' 
					  and ca.tipodoc  	<> 'NCC'*/ 
					  and nvl(ca.situacao,'')	<> 'A'
					  and TO_CHAR(ca.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
															 and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
					  and it.codemp 	= ca.codemp
					  and it.tipodoc 	= ca.tipodoc
					  and it.serie 		= ca.serie
					   and (upper(ve.nome) like upper('%".$aprocurar."%'))
					  and it.nroficial 	= ca.nroficial 
					  and it.datadoc 	= ca.datadoc
					  and dl.codemp 	= ca.codemp
					  and dl.armazem 	= it.codarm
					  and dl.id 		= '".$loja."'
					  and ve.codemp 	= ca.codemp
					  and ve.nrvd 		= ca.vendedor
					group by ca.vendedor, ve.nome, ca.codmoeda, it.codarm, dl.nome 
					order by ve.nome asc, dl.nome asc";
					
				} catch (Exception $e) {
			echo $e;
		}
		
	}
  
  // echo $sql_query;
  


	$rs=$db->execute($sql_query);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$vendas = null;
			else{
				$temp = array();

				$temp['vendedor'] 	= ($row[1]);
				$temp['loja'] 		= ($row[5]);
				$temp['montante'] 	= str_replace(',','.',$row[2]);
				$temp['moeda'] 		= $row[3];
				$vendas[$counter] 	= $temp;
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