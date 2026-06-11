<?php

include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if (!isset($_SESSION['state'])){
	header("Location: ../../index.php");
}
else{
	// Preparar a consulta SQL
	$sql = "SELECT datadoc,dvc,nrdiario,nrlancto,descmovcnt,nrdocofic,serie,vld,vlc,grupo
        FROM (
             CTB_MOV
        )
        WHERE ROWNUM <= 30";
	$rs = $db->Execute($sql);
$row = $rs->FetchRow();

//	$row = $rs->FetchRow();
//print_r($row);

	// Verificar se a consulta foi executada com sucesso
	if (!$rs) {
	    die('Falha ao executar a consulta: ' . $db->ErrorMsg());
	}

$content = '
		<ul class="breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="index.php">Home</a>
				<i class="icon-angle-right"></i>
			</li>
			<li><a onclick="calendars()">Calendário</a></li>
		</ul>
		<div id="contentMain">
			<div class="box span12">
				<div class="box-header" data-original-title="">
					<h2><i class="halflings-icon edit"></i><span class="break"></span>Tabela CTB_MOV</h2>
					</div>
          <div class="input-group input-group-sm mb-4">
          <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-sm">Nome</span>
          </div>
          <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
          <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">

          </div>
					<table class="table table-dark">
  <thead>
    <tr>
      <th scope="col">datavenc</th>
			<th scope="col">dvc</th>
      <th scope="col">nrdiario</th>
      <th scope="col">nrlancto</th>
      <th scope="col">descmovcnt</th>
			<th scope="col">nrdocofic</th>
			<th scope="col">serie</th>
			<th scope="col">vld</th>
			<th scope="col">vlc</th>
			<th scope="col">grupo</th>
    </tr>
  </thead>
  <tbody>
	';

	    // Adicionar as linhas da tabela com os dados do banco
	    while ($row = $rs->FetchRow()) {
	        $content .= '<tr>';
	        $content .= '<td>' . htmlspecialchars($row['DATADOC']) . '</td>'; //datadoc
	        $content .= '<td>' . htmlspecialchars($row['DVC']) . '</td>'; //dvc
	        $content .= '<td>' . htmlspecialchars($row['NRDIARIO']) . '</td>'; //nrdiario
	        $content .= '<td>' . htmlspecialchars($row['NRLANCTO']) . '</td>'; //nrlancto
	        $content .= '<td>' . htmlspecialchars($row['DESCMOVCNT']) . '</td>'; //descmovcnt
	        $content .= '<td>' . htmlspecialchars($row['NRDOCOFIC']) . '</td>'; //nrdocofic
	        $content .= '<td>' . htmlspecialchars($row['SERIE']) . '</td>'; //serie
	        $content .= '<td>' . htmlspecialchars($row['VLD']) . '</td>'; //vld
	        $content .= '<td>' . htmlspecialchars($row['VLC']) . '</td>'; //vlc
					$content .= '<td>' . htmlspecialchars($row['GRUPO']) . '</td>'; //grupo
	        $content .= '</tr>';
	    }

	    $content .= '
  </tbody>
</table>
				</div>
				</div>
		';



		$script = '
		<script src="js/calendario_softnova.js"></script>
		<script>
		    $(document).ready(function() {
		        $("table").DataTable({
		            "pageLength": 10
		        });
		    });
		</script>
		';



echo ($content.$script);


}
?>
