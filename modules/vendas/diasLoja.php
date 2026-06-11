<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if(isset($_SESSION['user'])){

//$vendas["Content"] = "";
 
	$vendas = array(); 
	
	$dia  = $_GET['dia'];
	$diafim = $_GET['diafim'];
	$loja = $_GET['loja'];

	
	
	if($loja == 'todos')
	{
		$sqlquery = "select nome, nroficial, serie, tipodoc, sum(total), datadoc, codmoeda, situacao from (
						    select dl.nome nome, vd.nroficial nroficial, vd.serie serie, vd.tipodoc tipodoc, (( nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))*decode(vd.tipodoc,'DCL',-1,1)) total, vd.datadoc datadoc, vd.codmoeda codmoeda, ca.situacao situacao 
						                from vendas_vdcab ca,vendas_vditem vd, dash_acessos ac, dash_lojas dl
						                where ca.codemp    = ".$_SESSION['codemp']."
						                  /*and ca.tipodoc <> 'NCC'
						                  and ca.tipodoc <> 'FSC'
						                  and ca.tipodoc <> 'DCL'*/	
						                  and vd.codemp    = ca.codemp
						                  and vd.tipodoc   = ca.tipodoc
						                  and vd.serie     = ca.serie
						                  and vd.nroficial = ca.nroficial
						                  and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dia."', 'dd/mm/yyyy'),'yyyymmdd') and TO_CHAR(to_date('".$diafim."', 'dd/mm/yyyy'),'yyyymmdd')
						                  and ac.codemp    = vd.codemp
						                  and ac.idgrupo = '".$_SESSION['grupo']."'
						                  and dl.codemp    = ac.codemp
						                  and dl.id      = ac.idloja
						                  and vd.codarm  = dl.armazem
						    
						    UNION 
						    
						    select dl.nome nome, vd.nroficial nroficial, vd.serie serie, vd.tipodoc tipodoc, (( nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))*decode(vd.tipodoc,'DCL',-1,1)) total, vd.datadoc datadoc, vd.codmoeda codmoeda, nvl(ca.situacao,'*') situacao 
						                from vendas_GRcab ca,vendas_GRitem vd, dash_acessos ac, dash_lojas dl
						                where ca.codemp    = ".$_SESSION['codemp']."
						                  and ca.tipodoc   = 'GRM'
						                  and vd.codemp    = ca.codemp
						                  and vd.tipodoc   = ca.tipodoc
						                  and vd.serie     = ca.serie
						                  and vd.nroficial = ca.nroficial
						                  and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dia."', 'dd/mm/yyyy'),'yyyymmdd') and TO_CHAR(to_date('".$diafim."', 'dd/mm/yyyy'),'yyyymmdd')
						                  and ac.codemp    = vd.codemp
						                  and ac.idgrupo = '".$_SESSION['grupo']."'
						                  and dl.codemp    = ac.codemp
						                  and dl.id      = ac.idloja
						                  and vd.codarm  = dl.armazem
						      ) group by nome, nroficial, serie, tipodoc, datadoc, codmoeda, situacao ";

		//echo $sqlquery;

	}
	else
	{
		
		$sqlquery = "select nome, nroficial, serie, tipodoc, sum(total), datadoc, codmoeda, situacao from (
						    select dl.nome nome, vd.nroficial nroficial, vd.serie serie, vd.tipodoc tipodoc, (( nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))*decode(vd.tipodoc,'DCL',-1,1)) total, vd.datadoc datadoc, vd.codmoeda codmoeda, ca.situacao situacao 
						                from vendas_vdcab ca,vendas_vditem vd, dash_acessos ac, dash_lojas dl
						                where ca.codemp    = ".$_SESSION['codemp']."
						                  /*and ca.tipodoc <> 'NCC'
						                  and ca.tipodoc <> 'FSC'
						                  and ca.tipodoc <> 'DCL'*/	
						                  and vd.codemp    = ca.codemp
						                  and vd.tipodoc   = ca.tipodoc
						                  and vd.serie     = ca.serie
						                  and vd.nroficial = ca.nroficial
						                  and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dia."', 'dd/mm/yyyy'),'yyyymmdd') and TO_CHAR(to_date('".$diafim."', 'dd/mm/yyyy'),'yyyymmdd')
						                  and ac.codemp    = vd.codemp
						                  and ac.idgrupo = '".$_SESSION['grupo']."'
						                  and ac.idloja  = ".$loja."
						                  and dl.codemp    = ac.codemp
										  and dl.id      = ac.idloja
						                  and vd.codarm  = dl.armazem
						    
						    UNION 
						    
						    select dl.nome nome, vd.nroficial nroficial, vd.serie serie, vd.tipodoc tipodoc, (( nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))*decode(vd.tipodoc,'DCL',-1,1)) total, vd.datadoc datadoc, vd.codmoeda codmoeda, nvl(ca.situacao,'*') situacao 
						                from vendas_GRcab ca,vendas_GRitem vd, dash_acessos ac, dash_lojas dl
						                where ca.codemp    = ".$_SESSION['codemp']."
						                  and ca.tipodoc   = 'GRM'
						                  and vd.codemp    = ca.codemp
						                  and vd.tipodoc   = ca.tipodoc
						                  and vd.serie     = ca.serie
						                  and vd.nroficial = ca.nroficial
						                  and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dia."', 'dd/mm/yyyy'),'yyyymmdd') and TO_CHAR(to_date('".$diafim."', 'dd/mm/yyyy'),'yyyymmdd')
						                  and ac.codemp    = vd.codemp
						                  and ac.idgrupo = '".$_SESSION['grupo']."'
										  and ac.idloja  = ".$loja."
						                  and dl.codemp    = ac.codemp
						                  and dl.id      = ac.idloja
						                  and vd.codarm  = dl.armazem
						      ) group by nome, nroficial, serie, tipodoc, datadoc, codmoeda, situacao ";



	}
	// echo $sqlquery;
 
 
	$rs=$db->execute($sqlquery);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$vendas["Content"] = "null";
			else{
				$temp = array();
				
				$temp['loja'] 		= $row[0];
				$temp['nroficial'] 	= $row[1];
				$temp['serie'] 		= $row[2];
				$temp['tipodoc'] 	= $row[3];
				$temp['total'] 		= str_replace(',','.',$row[4]);
				$temp['datadoc'] 	= $row[5];
				$temp['moeda'] 		= $row[6];
				$temp['situacao']	= $row[7];
				
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