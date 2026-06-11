<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$vendas 	 = array();

	$dataini 	 = $_GET['dataini'];
	$datafim 	 = $_GET['datafim'];
	$aprocurar 	 = $_GET['aprocurar'];
	$nomeloja 	 = $_GET["loja"];


	$sql_query = "";

	if($nomeloja == "todos"){
		$sql_query ="  select codart, nome, doc, sum(total), datadoc, pvn, codmoeda, situacao from (
 				           (select ar.codart codart, ar.nome nome, vd.tipodoc||' / '||vd.serie||' / '||vd.nroficial doc , (vd.qtd) total, vd.datadoc datadoc, vd.pvn*decode(vd.tipodoc,'DCL',-1,1) pvn, vd.codmoeda codmoeda, vd.situacao situacao
				                      from vendas_vditem vd, stk_artigos ar, dash_acessos ac, dash_lojas dl
				                      where vd.codemp = ".$_SESSION['codemp']."
				                        /*and vd.tipodoc <> 'NCC'
				                        and vd.tipodoc <> 'FSC'
				                        and vd.tipodoc <> 'DCL'*/
				                        and ar.codemp = vd.codemp
				                        and ar.codart = vd.codigo
				                        and ac.codemp = ar.codemp
				                        and dl.codemp = ac.codemp
				                        and (upper(ar.codart) like upper('%".$aprocurar."%') or upper(ar.nome) like upper('%".$aprocurar."%'))
				                        and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
				                                           and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
				                        and ac.idgrupo = '".$_SESSION['grupo']."'
				                        and dl.id      = ac.idloja
				                        and vd.codarm  = dl.armazem 
				          )
				          UNION
				          
				          (select ar.codart codart, ar.nome nome, vd.tipodoc||' / '||vd.serie||' / '||vd.nroficial doc , (vd.qtd) total, vd.datadoc datadoc, vd.pvn*decode(vd.tipodoc,'DCL',-1,1) pvn, vd.codmoeda codmoeda, vd.situacao situacao
				             from vendas_GRitem vd, stk_artigos ar, dash_acessos ac, dash_lojas dl
				                      where vd.codemp = 1
				                      	and vd.tipodoc = 'GRM'
				                        and ar.codemp = vd.codemp
				                        and ar.codart = vd.codigo
				                        and ac.codemp = ar.codemp
				                        and dl.codemp = ac.codemp
				                        and (upper(ar.codart) like upper('%".$aprocurar."%') or upper(ar.nome) like upper('%".$aprocurar."%'))
				                        and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
				                                           and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
				                        and ac.idgrupo = '".$_SESSION['grupo']."'
				                        and dl.id      = ac.idloja
				                        and vd.codarm  = dl.armazem 
				                       
				            )
				    )group by codart, nome, doc, datadoc, pvn, codmoeda, situacao
				     order by datadoc desc, doc desc ";







	} else{

		$sql_query = "select codart, nome, doc, sum(total), datadoc, pvn, codmoeda, situacao from (
 				           (select ar.codart codart, ar.nome nome, vd.tipodoc||' / '||vd.serie||' / '||vd.nroficial doc , (vd.qtd) total, vd.datadoc datadoc, vd.pvn*decode(vd.tipodoc,'DCL',-1,1) pvn, vd.codmoeda codmoeda, vd.situacao situacao
				                      from vendas_vditem vd, stk_artigos ar, dash_acessos ac, dash_lojas dl
				                      where vd.codemp = ".$_SESSION['codemp']."
				                        and ar.codemp = vd.codemp
				                        and ar.codart = vd.codigo
				                        and ac.codemp = ar.codemp
				                        and dl.codemp = ac.codemp
				                        and (upper(ar.codart) like upper('%".$aprocurar."%') or upper(ar.nome) like upper('%".$aprocurar."%'))
				                        and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
				                                           and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
				                        and ac.idgrupo = '".$_SESSION['grupo']."'
				                        and dl.id      = ".$nomeloja."
				                        and vd.codarm  = dl.armazem 
				          )
				          UNION
				          
				          (select ar.codart codart, ar.nome nome, vd.tipodoc||' / '||vd.serie||' / '||vd.nroficial doc , (vd.qtd) total, vd.datadoc datadoc, vd.pvn*decode(vd.tipodoc,'DCL',-1,1) pvn, vd.codmoeda codmoeda, vd.situacao situacao
				             from vendas_GRitem vd, stk_artigos ar, dash_acessos ac, dash_lojas dl
				                      where vd.codemp = ".$_SESSION['codemp']."
				                        and ar.codemp = vd.codemp
				                        and ar.codart = vd.codigo
				                        and ac.codemp = ar.codemp
				                        and dl.codemp = ac.codemp
				                        and (upper(ar.codart) like upper('%".$aprocurar."%') or upper(ar.nome) like upper('%".$aprocurar."%'))
				                        and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
				                                           and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
				                        and ac.idgrupo = '".$_SESSION['grupo']."'
				                        and dl.id      = ".$nomeloja."
				                        and vd.codarm  = dl.armazem 
				                       
				            )
				    )group by codart, nome, doc, datadoc, pvn, codmoeda, situacao
				     order by datadoc desc, doc desc ";
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

}else{
	header("Location: ../../index.php");
}

?>