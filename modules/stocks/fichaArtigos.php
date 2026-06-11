<?php


include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");
$content = '';
$contentOptions = '';
$yearOptions = '';
$monthOptions = '';

if (!isset($_SESSION['state'])){
	echo 0;
	header("Location: ../../index.php");
}
else{
	

	$cabecalho = '
		<ul class="breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="index.php">Home</a> 
				<i class="icon-angle-right"></i>
			</li>
			<li><a href="#">Ficha de Artigos</a></li>
		</ul>

		<div class="row-fluid sortable ui-sortable">
			<div class="box span12">
				<div class="clearfix"></div>
				<div class="box-header" data-original-title="">
					<h2><i class="halflings-icon edit"></i><span class="break"></span> Ficha de Artigos  </h2>
				</div>
				
				<div style="display: block;" class="box-content">
					<div class="clearfix"></div>
					<br>
						<form class="form-horizontal" id="formSearch">
							<fieldset>
								<div class="control-group">
									<label class="control-label" for="typeahead"> Nome </label>
									<div class="controls">
										<input autocomplete="off" class="span6 typeahead" id="typeahead" data-provide="typeahead"  placeholder="Pesquisar Artigo para visualizar dados" data-items="4" data-source="[]" type="text" onclick="this.select()">
									</div>
									<div class="controls">
										<br><button onclick="procurar()" onmousedown="limparProgressBar()" type="button" class="btn btn-success">Pesquisar <i class="halflings-icon search"></i></button>
									</div>
								</div>
								
								<div id="waitingBar" class="progress progress-striped progress-success active" style="display:inherit">
									<div id="waitingBarColor" class="bar" style="width:0%;"></div>
								</div>	
									
								<div id="divMenuInterior" class="box-icon pull-right">
									<table>
										<tr><td align="center">Info</td><td align="center">Gráficos</td></tr>
										<tr>
											<td><img onClick="setListMode()" title="Lista" src="img/list_icon.png" style="width: 70px; height: 70px;"></td>
											<td><img onClick="setGraphMode()" title="Gráficos" src="img/stats_icon.png" style="width: 70px; height: 70px;"></td>
										</tr>
									</table>		
								</div>
							
								<dl>
									<dt style="color: #19B624;" id="nomeArtigo"></dt>
								</dl>

								<div id="detailsProduct" >
									<div class="span6">
										<div class="controls">
											<dl>
												<dt> Grande Família </dt>
												<dd id="grandFamiliaArtigo"></dd>
											</dl>	
										</div>
										<div class="controls">
											<dl>
												<dt> Família </dt>
												<dd id="familiaArtigo"></dd>
											</dl>	
										</div>
										<div class="controls">
											<dl>
												<dt> Sub-Família </dt>
												<dd id="subFamiliaArtigo"></dd>
											</dl>	
										</div>
										<div class="controls">
											<dl>
												<dt> Classificação </dt>
												<dd id="classificacaoArtigo"></dd>
											</dl>	
										</div>
										<div class="controls">
											<dl>
												<dt> Tipo Artigo </dt>
												<dd id="tipoArtigo"></dd>
											</dl>	
										</div>
									</div>
									<div class="span5 noMarginLeft">
										<div class="controls">
											<dl>
												<dt> Mercado </dt>
												<dd id="mercadoArtigo"></dd>
											</dl>	
										</div>
										<div class="controls row-fluid" >
											<dl>
												<dt> IVA </dt>
												<dd id="descTaxaArtigo"></dd>
												<dd id="taxaArtigo"></dd>
											</dl>	
										</div>
										<div class="controls">
											<dl>
												<dt> Preço Unit. Médio </dt>
												<dd id="pumArtigo"></dd>
											</dl>	
										</div>
										<div class="controls">
											<dl>
												<dt> Preço Custo (Últ) </dt>
												<dd id="precoCustoArtigo"></dd>
											</dl>	
										</div>
									</div>		
								</div>
									
							</fieldset>
						</form>
					
						
						
					
		
				</div>
				
			</div>	
		</div>
			
			
		<div id="divTabelaPrecosVenda" style="overflow:hidden; height:50%; width:100%;" >
			<dl>
				<dt> Preços de Venda </dt>
			</dl>
			<div class="table table-responsive" style="overflow: auto; height: 100%; width: 100%; padding-right: 15px;" >
				<table id="tabelaPrecosVenda" class="table">
					<thead>
						<tr>
							<td>H</td>
							<td>U</td>
							<td>G</td>
							<td>O</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
			
			
				
		<div id="divGraficos" class="row-fluid">
			<div class="span12" id="divPrecoVenda" >
				<h2 style="text-align:center;"> Preço Venda </h2>
				<canvas id="PRECO_ArtigoGrafico" height="350px">
				</canvas>
			</div>
			<div class="span12" id="divPrecoCusto">
				<h2 style="text-align:center;"> Preço Custo </h2>
				<canvas id="PRECO_CUSTO_ArtigoGrafico" height="350px">
				</canvas>
			</div>
			<div class="span12" id="divVendaUnidadesDiarias">
				<h2 style="text-align:center;"> Venda de Unidades / Dia </h2>
				<canvas id="VENDAS_UNIDADES_DIA_ArtigoGrafico" height="350px">
				</canvas>
			</div>	
			<div class="span12" id="divPUM">
				<h2 style="text-align:center;"> Preços Unitários Médios </h2>
				<canvas id="PUM_ArtigoGrafico" height="350px">
				</canvas>
			</div>	
		</div>
		
		<div id="divData"></div>
			
		<div id="divLayoutCorrection" style="display: block">
			<img src="img/barra.png"/>
		</div>
			
			';
	

	$script="<script>


	
		$(document).ready(function(){
			$('#dataExistencias').hide();
			$('#dataRotura').hide();
			$('#dataDisponivel').hide();
			
			clearFields();
			$('#detailsProduct').hide();
			$('#divTabelaPrecosVenda').hide();
			
			$('#divMenuInterior').hide();
			
			$('#divGraficos').hide();
		});
	
	
		function clearFields(){
			//$('#nomeArtigo').prop( 'disabled', true );
			$('#grandFamiliaArtigo').prop( 'disabled', true );
			$('#familiaArtigo').prop( 'disabled', true );
			$('#subFamiliaArtigo').prop( 'disabled', true );
			$('#classificacaoArtigo').prop( 'disabled', true );
			$('#tipoArtigo').prop( 'disabled', true );
			$('#mercadoArtigo').prop( 'disabled', true );
			$('#descTaxaArtigo').prop( 'disabled', true );
			$('#taxaArtigo').prop( 'disabled', true );
			$('#pumArtigo').prop( 'disabled', true );
			$('#precoCustoArtigo').prop( 'disabled', true );
					
			$('#nomeArtigo').html('');
			$('#grandFamiliaArtigo').html('');
			$('#familiaArtigo').html('');
			$('#subFamiliaArtigo').html('');
			$('#classificacaoArtigo').html('');
			$('#tipoArtigo').html('');
			$('#mercadoArtigo').html('');
			$('#descTaxaArtigo').html('');
			$('#taxaArtigo').html('');
			$('#pumArtigo').html('');
			$('#precoCustoArtigo').html('');
			
			$('#divMenuInterior').hide();
			$('#divGraficos').hide();
		}
		
				
		function limparProgressBar(){
			$('#waitingBarColor').css('width', '0%' );
			$('#waitingBarColor').html('');				
		}

	
		
		function procurar(){
			clearFields();
			
			$('#waitingBarColor').css('width','0%');
			$('#waitingBarColor').html('');
			
			var sizeSearch = $('#typeahead').val().split('|').length;
			
			if(sizeSearch > 1){
				var codart 	= $('#typeahead').val().split('|')[1].trim();
				$('#waitingBarColor').css('width','30%');
				
				$.getJSON('modules/stocks/detalheArtigo.php',{'exp':codart, 'mode':'det'},function(data){
				
					if(data.length >0){
					
						$('#divGraficos').hide();
						$('#divMenuInterior').show();
					
						$('#divLayoutCorrection').css('display','none');
						$('#divTabelaPrecosVenda').show();
						$('#detailsProduct').show();
						$('#divData').hide();
			
						$('#nomeArtigo').html('<h1><span style=\"font-size : 22px; font-weight: bold;\">'+data[0].codart+'</span>  '+data[0].artigo+' </h1>');
						
						$('#classificacaoArtigo').html(data[0].classificacao);
						$('#tipoArtigo').html(data[0].tipo);
						$('#mercadoArtigo').html(data[0].mercado);
						$('#descTaxaArtigo').html(data[0].ivadesc);
						$('#taxaArtigo').html(data[0].taxaiva+'%');
						var auxDate = data[0].datapum.split('-');
						var date = auxDate[2]+'-'+auxDate[1]+'-'+auxDate[0];
						$('#pumArtigo').html($.number(data[0].pum, 3, ',','.')+' ".$_SESSION['moeda']."  em '+date);
						$('#precoCustoArtigo').html($.number(data[0].pcusto, 3, ',','.')+' ".$_SESSION['moeda']."');
						
						//var precosVendaTabela = '<div class=\"controls row-fluid\" >';
						var precosVendaTabela = '<dl>';
						precosVendaTabela += '<dt> Preços de Venda </dt>';
						precosVendaTabela += '</dl>';	
						precosVendaTabela += '<div class=\"span11 table-responsive\"  style=\"overflow: auto; height: 100%; width: 100%; padding-right: 15px;\">';
						precosVendaTabela += '<table id=\"tabelaPrecosVenda\" class=\"table table-bordered\">';
						
						precosVendaTabela += '<tr class=\"green\"><td>Preço 1</td><td>Preço 2</td><td>Preço 3</td><td>Preço 4</td><td>Preço 5</td><td>Data Preço</td></tr>';
						
						for(i=0; i<data.length; i++){
							precosVendaTabela += '<tr class=\"table table-condensed\">';
							precosVendaTabela += '<td>'+$.number(data[i].p1, 3, ',','.')+' ".$_SESSION['moeda']."</td>';
							precosVendaTabela += '<td>'+$.number(data[i].p2, 3, ',','.')+' ".$_SESSION['moeda']."</td>';
							precosVendaTabela += '<td>'+$.number(data[i].p3, 3, ',','.')+' ".$_SESSION['moeda']."</td>';
							precosVendaTabela += '<td>'+$.number(data[i].p4, 3, ',','.')+' ".$_SESSION['moeda']."</td>';
							precosVendaTabela += '<td>'+$.number(data[i].p5, 3, ',','.')+' ".$_SESSION['moeda']."</td>';
							var aux = data[i].datapreco.split('-');
							precosVendaTabela += '<td>'+aux[2]+'-'+aux[1]+'-'+aux[0]+'</td>';
							precosVendaTabela += '</tr>';	
						}
						
						precosVendaTabela += '</table>';
						precosVendaTabela += '</div>';
						//precosVendaTabela += '</div>';
						
						$('#divTabelaPrecosVenda').html(precosVendaTabela);
					
						$.getJSON('modules/vendas/familias.php',{'mode':'gf', 'codart':data[0].codart ,'codgf':data[0].gf},function(dataGF){
							$('#grandFamiliaArtigo').html(dataGF[0].gf);
						});
					
						$.getJSON('modules/vendas/familias.php',{'mode':'fa', 'codart':data[0].codart ,'codgf':data[0].gf,'codfa':data[0].fa },function(dataFA){
							$('#familiaArtigo').html(dataFA[0].fa);
						});
					
						$.getJSON('modules/vendas/familias.php',{'mode':'sf', 'codart':data[0].codart ,'codgf':data[0].gf,'codfa':data[0].fa,'codsf':data[0].sf},function(dataSF){
							$('#subFamiliaArtigo').html(dataSF[0].sf);
						});
					} else {
						$('#divData').html('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Deve selecionar primeiro um Artigo!</div>');
						$('#divData').show();	
						$('#divLayoutCorrection').css('display','block');
						$('#divTabelaPrecosVenda').hide();
						$('#detailsProduct').hide();
					}
				});
				
			} else {
				$('#divData').html('<div class=\"alert alert-error\" role=\"alert\"><span class=\"halflings-icon exclamation-sign \" aria-hidden=\"true\"></span><span class=\"sr-only\">Erro:</span>Deve selecionar primeiro um Artigo!</div>');
				$('#divData').show();			
				$('#divLayoutCorrection').css('display','block'); 
			
				$('#divTabelaPrecosVenda').hide();
				$('#detailsProduct').hide();
				$('#waitingBarColor').css('width','100%');
				$('#waitingBarColor').html('Completo');
			}
			
			
			$('#waitingBarColor').css('width','100%');
			$('#waitingBarColor').html('Completo');
		};
		
		var autocomplete = $('#typeahead').typeahead().on('keyup', function(ev){
			ev.stopPropagation();
			ev.preventDefault();
		}); 
					
		jQuery('#typeahead').on('input', function() {
			if($('#typeahead').val().length >= 1){
				var expr = $('#typeahead').val();
				$.getJSON('modules/stocks/detalheArtigo.php',{'exp':expr, 'mode':'cab'},function(infoArtigo){
					$('#typeahead').data('typeahead').source = infoArtigo;
				});
			} else {
				$('#typeahead').data('typeahead').source = [];
			}
		});

		
		function setListMode(){
			$('#divGraficos').hide();
			$('#divTabelaPrecosVenda').show();
			$('#detailsProduct').show();
		}
		function setGraphMode(){
			$('#divGraficos').show();
			$('#divTabelaPrecosVenda').hide();
			$('#detailsProduct').hide();
		
			setGraphics();
		}
		
		
		
		// GRAPHIC JS CODE
		
		
		function setGraphics(){
			
			var codart 	= $('#typeahead').val().split('|')[1].trim();
			
			$.getJSON('modules/stocks/detalheArtigo.php',{'exp':codart, 'mode':'pvGraph'},function(dataPr){
				if(dataPr.datasets[0].data.length > 0 ){
					$('#divPrecoVenda').show();
					
					$('#divPrecoVenda').html('');
					var htmlToDiv= '<h2 style=\"text-align:center;\"> Preço Venda </h2><canvas id=\"PRECO_ArtigoGrafico\" height=\"350px\"></canvas>';
					$('#divPrecoVenda').html(htmlToDiv);
					
					var max = (Math.max.apply(Math,dataPr.datasets[0].data));;
					var steps = 10;
					var stepSize = max / steps;
					
					var optionsPr = {
							graphTitle : \"Preços de Venda\",
							scaleOverride: true,
							scaleStartValue: 0,
							scaleStepWidth: stepSize,
							scaleSteps: steps,
							scaleShowGridLines : true,
							scaleGridLineWidth : 1,
							scaleShowHorizontalLines : true,
							bezierCurve : true,
							bezierCurveTension : 0.4,
							pointDot : true,
							pointDotRadius : 4,
							pointDotStrokeWidth : 2,
							pointHitDetectionRadius : 20,
							datasetStroke : true,
							datasetStrokeWidth : 2,
							datasetFill : true,
							scaleStartValue: 0,
							scaleLabel : \"<%= Number(value).toFixed(2).replace('.', ',') + ' '%>\",
							annotateDisplay : true,
							inGraphDataShow : true
					};
					
					//NECESSARIO PARA O GRAFICO FICAR RESPONSIVO
					var ctx = $(\"#PRECO_ArtigoGrafico\").get(0).getContext(\"2d\");
					var width = $('#PRECO_ArtigoGrafico').parent().width();
					$('#PRECO_ArtigoGrafico').attr('width',width);
					new Chart(ctx).Line(dataPr,optionsPr);
					
				
				} else {
					$('#divPrecoVenda').hide();
				}
			});
			
			$.getJSON('modules/stocks/detalheArtigo.php',{'exp':codart, 'mode':'pcustoGraph'},function(dataPr){
				if(dataPr.datasets[0].data.length > 0){
					$('#divPrecoCusto').show();
					
					$('#divPrecoCusto').html('');
					var htmlToDiv = '<h2 style=\"text-align:center;\"> Preço Custo </h2><canvas id=\"PRECO_CUSTO_ArtigoGrafico\" height=\"350px\"></html>';
					$('#divPrecoCusto').html(htmlToDiv);
				
					
					
					var max = (Math.max.apply(Math,dataPr.datasets[0].data));;
					var steps = 10;
					var stepSize = max / steps;
					
					var optionsPr = {
					scaleOverride: true,
							graphTitle : \"Preços de Custo\",
							scaleStartValue: 0,
							scaleStepWidth: stepSize,
							scaleSteps: steps,
							scaleShowGridLines : true,
							scaleGridLineWidth : 1,
							scaleShowHorizontalLines : true,
							bezierCurve : true,
							bezierCurveTension : 0.4,
							pointDot : true,
							pointDotRadius : 4,
							pointDotStrokeWidth : 2,
							pointHitDetectionRadius : 30,
							datasetStroke : true,
							datasetStrokeWidth : 2,
							datasetFill : true,
							scaleStartValue: 0,
							annotateDisplay : true,
							inGraphDataShow : true,
							scaleLabel : \"<%= Number(value).toFixed(2).replace('.', ',') + ' '%>\"
					};

					
					//NECESSARIO PARA O GRAFICO FICAR RESPONSIVO
					var ctx = $(\"#PRECO_CUSTO_ArtigoGrafico\").get(0).getContext(\"2d\");
					var width = $('#PRECO_CUSTO_ArtigoGrafico').parent().width();
					$('#PRECO_CUSTO_ArtigoGrafico').attr('width',width);
					new Chart(ctx).Line(dataPr,optionsPr);
					
				} else {
					$('#divPrecoCusto').hide();
				}
			});
			
			$.getJSON('modules/stocks/detalheArtigo.php',{'exp':codart, 'mode':'vendasDiariasGraph'},function(dataVD){

				if(dataVD.datasets[0].data.length > 0){
					$('#divVendaUnidadesDiarias').show();
					
					$('#divVendaUnidadesDiarias').html('');
					var htmlToDiv = '<h2 style=\"text-align:center;\"> Venda de Unidades / Dia </h2><canvas id=\"VENDAS_UNIDADES_DIA_ArtigoGrafico\" height=\"350px\"></canvas>';
					$('#divVendaUnidadesDiarias').html(htmlToDiv);
					
					var max = (Math.max.apply(Math,dataVD.datasets[0].data));;
					var steps = 10;
					var stepSize = max / steps;
					
					var optionsVD = {
							graphTitle : \"Unidades Diárias Vendidas\",
							animation: true,
							scaleOverride: true,
							scaleStartValue: 0,
							scaleStepWidth: stepSize,
							scaleSteps: steps,
							scaleShowGridLines: true,
							scaleGridLineWidth: 1,
							scaleShowHorizontalLines: true,
							bezierCurve: true,
							bezierCurveTension: 0.4,
							pointDot: true,
							pointDotRadius: 4,
							pointDotStrokeWidth: 2,
							pointHitDetectionRadius: 30,
							datasetStroke: true,
							datasetStrokeWidth: 2,
							datasetFill: true,
							scaleStartValue: 0,
							showTooltips: true,
							annotateDisplay : true,
							inGraphDataShow : true,
							tooltipTemplate: \"<%if (label){%><%=label%>: <%}%><%= value %>\",
							scaleLabel : \"<%= Number(value).toFixed(2).replace('.', ',') + ' '%>\"
					};
					
					//NECESSARIO PARA O GRAFICO FICAR RESPONSIVO
					var ctx = $(\"#VENDAS_UNIDADES_DIA_ArtigoGrafico\").get(0).getContext(\"2d\");
					var width = $('#VENDAS_UNIDADES_DIA_ArtigoGrafico').parent().width();
					$('#VENDAS_UNIDADES_DIA_ArtigoGrafico').attr('width',width);
					new Chart(ctx).Line(dataVD,optionsVD);
					
				}
				else {
					$('#divVendaUnidadesDiarias').hide();
				}
			});
			
			
			$.getJSON('modules/stocks/detalheArtigo.php',{'exp':codart, 'mode':'PUMGraph'},function(dataPUM){

				if(dataPUM.datasets[0].data.length > 0){
					$('#divPUM').show();
					
					$('#divPUM').html('');
					var htmlToDiv = '<h2 style=\"text-align:center;\"> Preços Unitários Médios </h2><canvas id=\"PUM_ArtigoGrafico\" height=\"350px\"></canvas>';
					$('#divPUM').html(htmlToDiv);
					
					var options = {
							graphTitle : \"Preço Unitário Médio\",
							animation: true,
							legend : true,
							datasetFill : true,
							annotateDisplay : true,
							tooltipTemplate: \"<%if (label){%><%=label%>: <%}%><%= value %>\",
							scaleLabel : \"<%= Number(value).toFixed(2).replace('.', ',') + ' '%>\"
					};
					
					//NECESSARIO PARA O GRAFICO FICAR RESPONSIVO
					var ctx = $(\"#PUM_ArtigoGrafico\").get(0).getContext(\"2d\");
					var width = $('#PUM_ArtigoGrafico').parent().width();
					$('#PUM_ArtigoGrafico').attr('width',width);
					new Chart(ctx).Line(dataPUM, options);
				}
				else {
					$('#divPUM').hide();
				}
			});
		}
		
		//Recarrega os graficos para os ajustar as medidas do ecran
		window.onresize = function(event){
			setGraphics();
		};
					
		
		
		</script>";
	
	
	echo ($cabecalho.$content." ".$script);
	
}


?>


