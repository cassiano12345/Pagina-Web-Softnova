<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(isset($_SESSION['user'])){

	$totalVendasSemanal = 0;
	

	$rs = $db->execute("select sum(ValorLI) from (
					-- Vendas
					    select sum(decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
					                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
					                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
					                                                      /  DI.Cambio) ValorLI
					    from Vendas_VDCab DI
					    where DI.CodEmp = ".$_SESSION['codemp']."
					      and DI.TipoDoc not in ('DAF','FAF')
					      and DI.AnoDoc = ".$_SESSION['ano']."
					      and to_char(DI.datadoc,'yyyymmdd') = to_char(sysdate,'yyyymmdd')
					      and nvl(DI.Situacao,'*') <> 'A' 
					      
					  UNION    
					  
					  -- Guias de remessa ainda não faturadas
					    select sum(decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
					                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
					                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
					                                                          /  DI.Cambio) ValorLI
					    from Vendas_GrCab DI
					    where CodEmp = ".$_SESSION['codemp']."
					    	and DI.TipoDoc = 'GRM'            
					      and DI.AnoDoc = ".$_SESSION['ano']."
					      and to_char(DI.datadoc,'yyyymmdd') = to_char(sysdate,'yyyymmdd')
					      and nvl(DI.Situacao,'*') <> 'A'       
					      and DocFact_N is null)");


	if($rs){
		While($row = $rs->FetchRow()){
		if (is_null($row[0]))
			$totalVendasSemanal = 0;
		else
			$totalVendasSemanal = str_replace(',','.',$row[0]);
		}
	}
	echo $totalVendasSemanal;
}
else{
	header("Location: ../../index.php");
}
?>
