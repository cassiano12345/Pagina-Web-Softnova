<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$content = "";

if(isset($_SESSION['user'])){

$encomendas 	 = array();

$dataini 	 = $_GET['dataini'];
$datafim 	 = $_GET['datafim'];

$sql_query = "";

$sql_query ="  select Count(*) AS FASES
							FROM (SELECT DISTINCT vd.fase
							FROM Vendas_eccab vd
							where vd.codemp = ".$_SESSION['codemp']."
							and vd.tipodoc = 'ECL'
							and situacao in ('E','P')		
				            and TO_CHAR(VD.DATADOC,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
				            and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') ) ";


	$rs=$db->execute($sql_query);
	if($rs){
		$counter = 0;
		While($row = $rs->FetchRow()){
			if (is_null($row[0]))
				$encomendas = null;
			else{
				$temp = array();
				
				$temp['nrfases'] 		= $row[0];				
				
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