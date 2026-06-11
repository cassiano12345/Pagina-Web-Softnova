<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

$encomendas 	 = array();

$dataini 	 = $_GET['dataini'];
$datafim 	 = $_GET['datafim'];
$nroficial 	 = $_GET['nroficial'];

$sql_query = "";

$sql_query ="  select vd.nroficial , vd.datadoc, vd.fase, ec.descricao, vd.dataeprevista, vd.nome, (vd.dataeprevista - SYSDATE ) DIF
							from Vendas_eccab vd, ecl_tabelas ec
				            where vd.codemp = ".$_SESSION['codemp']."
				            and vd.nroficial = ".$nroficial."
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
				$temp['fasedescricao'] 	= $row[3];
				$temp['dataeprevista'] 	= $row[4];
				$temp['nome'] 			= $row[5];
				$temp['dif']			= $row[6];
				
				$encomendas[$counter] = $temp;
				$counter = $counter +1;
			}
		}
	}
	
	echo json_encode($encomendas);

}
else{
	header("Location: ../../index.php");
}

?>