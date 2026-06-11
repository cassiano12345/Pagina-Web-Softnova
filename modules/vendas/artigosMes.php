<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

if(isset($_SESSION['user'])){

//$vendas["Content"] = "";
 
	$vendas = array(); 
	
	$mes  = $_GET['mes'];
	$expr = $_GET['exp'];
	$ano  = $_GET['ano'];
	$dias = $_GET['dias'];
	$loja = $_GET['loja'];
	
	
	if(strlen($mes)<2)
		$mes = '0'.$mes;
		
	$dataini = '01/'.$mes.'/'.$ano;
	$datafim = $dias.'/'.$mes.'/'.$ano;
	
	if($loja == 'todos')
		$sqlquery = "select ar.codart, ar.nome,vd.tipodoc||' / '||vd.serie||' / '||vd.nroficial, sum(vd.qtd), vd.datadoc, vd.pvn*decode(vd.tipodoc,'DCL',-1,1), vd.codmoeda, vd.situacao
					  from vendas_vditem vd, stk_artigos ar, dash_acessos ac, dash_lojas dl
					  where vd.codemp = ".$_SESSION['codemp']."
							  and ar.codemp = vd.codemp
							  and ar.codart = vd.codigo
							  and (upper(ar.codart) like upper('%".$expr."%') or upper(ar.nome) like upper('%".$expr."%'))
							  and ac.codemp = ar.codemp
							  and ac.idgrupo = '".$_SESSION['grupo']."'
							  and dl.codemp = ac.codemp
							  and dl.id      = ac.idloja
							  and vd.codarm  = dl.armazem
							  and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
																	 and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
					  group by ar.codart, ar.nome, vd.tipodoc||' / '||vd.serie||' / '||vd.nroficial, vd.datadoc, vd.pvn*decode(vd.tipodoc,'DCL',-1,1) , vd.codmoeda, vd.situacao
					  order by vd.datadoc desc, vd.tipodoc||' / '||vd.serie||' / '||vd.nroficial desc";
					  
					  
					  
					  
	else
		$sqlquery = "select ar.codart, ar.nome,vd.tipodoc||' / '||vd.serie||' / '||vd.nroficial , sum(vd.qtd), vd.datadoc, vd.pvn*decode(vd.tipodoc,'DCL',-1,1), vd.codmoeda, vd.situacao
					  from vendas_vditem vd, stk_artigos ar, dash_acessos ac, dash_lojas dl
					  where vd.codemp = ".$_SESSION['codemp']."
							  and ar.codemp = vd.codemp
							  and ar.codart = vd.codigo
							  and (upper(ar.codart) like upper('%".$expr."%') or upper(ar.nome) like upper('%".$expr."%'))
							  and ac.codemp = ar.codemp
							  and ac.idgrupo = '".$_SESSION['grupo']."'
							  and ac.idloja  = ".$loja."
							  and dl.codemp = ac.codemp
							  and dl.id      = ac.idloja
							  and vd.codarm  = dl.armazem
							  and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
																	 and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
							  group by ar.codart, ar.nome, vd.tipodoc||' / '||vd.serie||' / '||vd.nroficial, vd.datadoc, vd.pvn*decode(vd.tipodoc,'DCL',-1,1) , vd.codmoeda, vd.situacao
					  order by vd.datadoc desc, vd.tipodoc||' / '||vd.serie||' / '||vd.nroficial desc";
		



		
	//echo $sqlquery;
 
 
	$rs=$db->execute($sqlquery);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$vendas["Content"] = "null";
			else{
				$temp = array();
				
				$temp['codart'] 	= $row[0];
				$temp['nome'] 		= ($row[1]);
				$temp['qtd'] 		= str_replace(',','.',$row[3]);
				$temp['pvn'] 		= str_replace(',','.',$row[5]);
				$temp['datadoc'] 	= $row[4];
				$temp['documento']  = $row[2];
				$temp['moeda']  	= $row[6];
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