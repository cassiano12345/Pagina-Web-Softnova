<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

	$encomendas 	 = array();

	$dataini 	 = $_GET['dataini'];
	$datafim 	 = $_GET['datafim'];


	$sql_query = "";

		$sql_query ="  select vd.nroficial , vd.datadoc, ec.descricao, vd.dataeprevista, vd.nome
							from Vendas_eccab vd, ecl_tabelas ec
				            where vd.codemp = ".$_SESSION['codemp']."
				            and vd.tipodoc = 'ECL'
				            and situacao in ('E','P')
				            and vd.fase = ec.codigo
				            and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
				            and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
				            order by datadoc desc ";


	$rs=$db->execute($sql_query);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$encomendas = null;
			else{
				$temp = array();
				
				$temp['nroficial'] 		= $row[0];
				$temp['datadoc'] 		= $row[1];
				$temp['fase'] 			= $row[2];
				$temp['dataeprevista'] 	= $row[3];
				$temp['nome'] 			= $row[4];
			
				
				$encomendas[$counter] = $temp;
				$counter = $counter +1;
			}
		}
	}
	
	echo json_encode($encomendas);

}else{
	header("Location: ../../index.php");
}

?>