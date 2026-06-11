<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if(isset($_SESSION['user'])){

//$vendas["Content"] = "";
 
	$vendas = array(); 
	
	
	$mes  = $_GET['mes'];
	if (isset($_GET['exp'])){
		$expr = $_GET['exp'];
	}
	else{
		$expr = "";
	}	
	$ano  = $_GET['ano'];
	$dias = $_GET['dias'];
	$loja = $_GET['loja'];
	
	
	if(strlen($mes) < 2)
		$mes = '0'.$mes;
		
	$dataini = '01/'.$mes.'/'.$ano;
	$datafim = $dias.'/'.$mes.'/'.$ano;
	
	
	
	if($loja == 'todos')
		$sqlquery = "select dl.nome, vd.nroficial, vd.serie, vd.tipodoc , (nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0))*decode(ca.tipodoc,'DCL',-1,1), vd.datadoc, vd.codmoeda, ca.situacao
						from vendas_vdcab ca,vendas_vditem vd, dash_acessos ac, dash_lojas dl
						where ca.codemp    = ".$_SESSION['codemp']."
						  /*and ca.tipodoc   <> 'DCL' 
						  and ca.tipodoc   <> 'FSC' 
						  and ca.tipodoc   <> 'NCC'*/ 
						  and vd.codemp    = ca.codemp
		  				  and ac.codemp    = vd.codemp
						  and dl.codemp    = ac.codemp
						  and vd.nroficial = ca.nroficial
						  and vd.serie     = ca.serie
						  and vd.tipodoc   = ca.tipodoc
						  and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
																 and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
						  and ac.idgrupo   = '".$_SESSION['grupo']."'
						  and dl.id        = ac.idloja
						  and vd.codarm    = dl.armazem
					order by vd.datadoc desc";
	else
		$sqlquery = "select dl.nome, vd.nroficial, vd.serie, vd.tipodoc , (nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0))*decode(ca.tipodoc,'DCL',-1,1), vd.datadoc, vd.codmoeda, ca.situacao
						from vendas_vdcab ca,vendas_vditem vd, dash_acessos ac, dash_lojas dl
						where ca.codemp    = ".$_SESSION['codemp']."
						  /*and ca.tipodoc   <> 'DCL' 
						  and ca.tipodoc   <> 'FSC' 
  						  and ca.tipodoc   <> 'NCC'*/ 
						  and vd.codemp    = ca.codemp
		  				  and ac.codemp    = vd.codemp
						  and dl.codemp    = ac.codemp
						  and vd.nroficial = ca.nroficial
						  and vd.serie     = ca.serie
						  and vd.tipodoc   = ca.tipodoc
						  and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
																 and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
						  and ac.idgrupo   = '".$_SESSION['grupo']."'
						  and ac.idloja    = ".$loja."
						  and dl.id        = ac.idloja
						  and vd.codarm    = dl.armazem
					/*group by dl.nome, vd.nroficial, vd.serie, vd.tipodoc, ca.total, vd.datadoc, ca.situacao*/
					order by vd.datadoc desc";
		
	// echo $sqlquery; 
		
	$rs=$db->execute($sqlquery);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$vendas["Content"] = "null";
			else{
				$temp = array();
				$temp['loja'] 		= ($row[0]);
				$temp['nroficial'] 	= $row[1];
				$temp['serie'] 		= $row[2];
				$temp['tipodoc'] 	= $row[3];
				$temp['total'] 		= str_replace(',','.',$row[4]);
				$temp['datadoc'] 	= $row[5];
				$temp['moeda'] 		= $row[6];
				$temp['situacao']   = $row[7];
				
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