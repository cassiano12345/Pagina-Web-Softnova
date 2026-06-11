<?php
include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


$content = "";
$sql = "";
$tempName = "";
$pNomeloja = null;
$nomeloja = '';
$mercado= '';
$armazem= '';
$localizacao= '';
$gerente= '';

$pNomeloja = $_GET["nomeloja"];



$sql = "select lj.nome, lj.armazem, lj.localizacao, lj.gerente 
										from dash_lojas lj, dash_acessos ac
										where  ac.codemp = ".$_SESSION['codemp']." 
										and lj.codemp = ac.codemp 
										and lj.id = ac.idloja 
										and ac.idgrupo = ".$_SESSION['grupo']."";
$rs=$db->execute($sql);
if($rs){
	While($row = $rs->FetchRow()){
		
		if ($pNomeloja != null){
			if ($pNomeloja==$row[0]){
				//$mercado	 = $row[1]; 
				$armazem	 = ($row[1]);
				$localizacao = ($row[2]);
				$gerente	 = ($row[3]);
				$nomeloja	.= "<option selected='selected'  value='".$row[0]."'>".($row[0])."</option>";
			} else {
				$nomeloja.="<option value='".($row[0])."'>".($row[0])."</option>";
				$tempName="<option value='".($row[0])."'>".($row[0])."</option>";
			}
		} else {
				$nomeloja.="<option value='".($row[0])."'>".($row[0])."</option>";
		}
	}
}

$mercado2 = "";
$gerente2 = "";
$armazem2 = "";
$imagem = "";
$id = "";
$sqlA = " select nome 	  from stk_tabelas 	where tabela = 'AR' AND codigo='".$armazem."'";
$sqlM = " select nome 	  from stk_mercado 	where codigo = '".$mercado."'";
$sqlG = " select nomeutil from soft_util 	where id = '".$gerente."'";
$sqlI = " select imagem   from dash_lojas 	where nome = '".$pNomeloja."'";
$sqlId = "select id 	  from dash_lojas 	where nome like '".$pNomeloja."'";

$rs2=$db->execute($sqlA);
	if($rs2){
		While($row2 = $rs2->FetchRow()){
			$armazem2 = ($row2[0]);
		}
	}
	
$rs3=$db->execute($sqlM);
	if($rs3){
		While($row3 = $rs3->FetchRow()){
			$mercado2 = ($row3[0]);
		}
	}
$rs4=$db->execute($sqlG);
	if($rs4){
		While($row4 = $rs4->FetchRow()){
			$gerente2 = ($row4[0]);
		}
	}
	
$rs5=$db->execute($sqlI);
if($rs5){
	While($row5 = $rs5->FetchRow()){
			$imagem = ($row5[0]);
	}
}

$rs6=$db->execute($sqlId);
if($rs6){
	While($row6 = $rs6->FetchRow()){
			$id = ($row6[0]);
	}
}


if($imagem == "")
	$imagem = "/intranet/img/building.jpg";

	
$content .= '
	<ul class="breadcrumb">
		<li>
			<i class="icon-home"></i>
			<a href="index.php">Home</a> 
			<i class="icon-angle-right"></i>
		</li>
		<li><a href="#">Lojas / Filiais</a></li>
	</ul>	

	

	<div class="box ">
		<div class="box-header ">
			<h2><i class="halflings-icon shopping-cart"></i><span class="break"></span> Armazéns </h2>
		</div>
		
		<div class="box-content" >	
			<div>	
				
				<select placeholder="Escolha a Loja." onChange="onchangeEmpresa()" class="box span5" id="cb_loja" >
					<option value="" selected disabled>Escolha um Armazém</option>
					'.$nomeloja.'
				</select>
			</div>
			<div class="divDloja" id="dados">		
					<h1><small>'.$pNomeloja.'</small></h1>					
					<div class="divEloja" id="esquerda" style="display: none;"> 
						
						<!--<h2 style="color: #000;">Mercado</h2><h4  id="Mercado"><small>'.$mercado2.'</small></h4>-->
						<h2 style="color: #000;">Armazem</h2><h4 id="Armazem"><small>'.$armazem2.'</small></h4>
						<h2 style="color: #000;">Localização</h2><h4 id="localizacao"><small>'.$localizacao.'</small></h4>
						<h2 style="color: #000;">Gerente</h2><h4  id="gerente"><small>'.$gerente.'</small></h4>
					</div>
					<div class="divDirloja" id="direita"> 
						<img src="'.$imagem.'" style="width:65%;height:70%">
					</div>
			</div>   
			
			
		</div> 
	</div>';

/***************************************** Criar Atalhos com base nas permissões atribuidas ao utilizador	*********************/
$sql = "select vendas, compras, stocks, ccorrentes, fornecedores from dash_grupos
				where codemp = ".$_SESSION['codemp']."
				and idgrupo = ".$_SESSION['grupo']." ";
$htmlDataMenu = '';
$rsX=$db->execute($sql);
	if($rsX)
	{
		While($rowX = $rsX->FetchRow())
		{
			if($rowX[0] == 'S')
			{
				$sqlVendasQuery = "Select submenu, ordem from dash_subacessos where codemp=".$_SESSION['codemp']." and menu = 'VENDAS' and idgrupo=".$_SESSION['grupo']." order by ordem asc";	
				$rsVendas=$db->execute($sqlVendasQuery);
				if($rsVendas)
					{
						$htmlDataMenu .= '	<a class="quick-button  span2" id="vendas" onclick="vendass()">
												<i class="icon-table"></i>
												<p >Vendas Diárias</p>
											</a>
											<a class="quick-button span2" id="vendas" onclick="vendass_m()">
												<i class="icon-calendar"></i>
												<p>Vendas Mensais</p>
											</a>	
										';		
					}

			} 
			else 
			{
				$htmlDataMenu .= '	<a class="quick-button  span2" id="vendas" onclick="semAcesso()">
										<i class="icon-table"></i>
										<p >Vendas Diárias</p>
									</a>
									<a class="quick-button  span2" id="vendas" onclick="semAcesso()">
										<i class="icon-calendar"></i>
										<p >Vendas Mensais</p>
									</a>
								';
			}
			if($rowX[1] == 'S')
			{
				$sqlComprasQuery = "Select submenu, ordem from dash_subacessos where codemp=".$_SESSION['codemp']." and menu = 'COMPRAS' and idgrupo=".($_SESSION['grupo'])." order by ordem asc";
				$rsCompras=$db->execute($sqlComprasQuery);
				if($rsCompras){
						$htmlDataMenu .= '	<a class="quick-button span2" id="compras" onclick="comprass()">
												<i class="icon-shopping-cart"></i>
												<p>Compras</p>
											</a>
										';		
					}
			} 
			else 
			{
				$htmlDataMenu .= '	<a class="quick-button span2" id="compras" onclick="semAcesso()">
										<i class="icon-shopping-cart"></i>
										<p>Compras</p>
									</a>
								';
			}
			if($rowX[2] == 'S')
			{
				$sqlArtigosQuery = "Select submenu, ordem from dash_subacessos where codemp=".$_SESSION['codemp']." and menu = 'ARTIGOS' and idgrupo=".$_SESSION['grupo']." order by ordem asc";
				$rsArtigos=$db->execute($sqlArtigosQuery);
				
				if($rsArtigos)
					{
						$htmlDataMenu .= '	<a class="quick-button span2" id="stocks" onclick="stockss()">
												<i class="icon-barcode"></i>
												<p>Stocks</p>
											</a>
											<a class="quick-button span2" id="stocks" onclick="stocks_r()">
												<i class="icon-reorder" ></i>
												<p>Rotura de Stocks</p>
											</a>
											<a class="quick-button span2" id="stocks" onclick="stocks_d()">
												<i class="icon-check"></i>
												<p>Stocks Disponível</p>
											</a>		
										';		
					}

			} 
			else 
			{
				$htmlDataMenu .= '	<a class="quick-button span2" id="stocks" onclick="semAcesso()">
										<i class="icon-barcode"></i>
										<p>Stocks</p>
									</a>
									<a class="quick-button span2" id="stocks" onclick="semAcesso()">
										<i class="icon-reorder" ></i>
										<p>Rotura de Stocks</p>
									</a>
									<a class="quick-button span2" id="stocks" onclick="semAcesso()">
										<i class="icon-check"></i>
										<p>Stocks Disponível</p>
									</a>	
								';
			}						
		}
	} 
	else 
	{
		echo 'Not Working!';
	}
	

	if($pNomeloja != null){
		$content .= '

			<div class="row-fluid">	
				<div class="box  span12">
					<div class="box-header">
						<h2><i class="halflings-icon hand-top"></i><span class="break"></span>Acessos Rápidos</h2>
					</div>
					<div class="box-content">					
						'.$htmlDataMenu.'
						<!-- ******	Substituido para funcionar com as permissões ******************************************
							<a class="quick-button  span2" id="vendas" onclick="vendass()">
								<i class="icon-table"></i>
								<p >Vendas Diárias</p>
								
							</a>
							
							<a class="quick-button span2" id="vendas" onclick="vendass_m()">
								<i class="icon-calendar"></i>
								<p>Vendas Mensais</p>
							</a>		
							
							<a class="quick-button span2" id="compras" onclick="comprass()">
								<i class="icon-shopping-cart"></i>
								<p>Compras</p>
							</a>
							
							<a class="quick-button span2" id="stocks" onclick="stockss()">
								<i class="icon-barcode"></i>
								<p>Stocks</p>
							</a>

							<a class="quick-button span2" id="stocks" onclick="stocks_r()">
								<i class="icon-reorder" ></i>
								<p>Rotura de Stocks</p>
							</a>

							<a class="quick-button span2" id="stocks" onclick="stocks_d()">
								<i class="icon-check"></i>
								<p>Stocks Disponível</p>
							</a>	
						-->
						<div class="clearfix"></div>
					</div>	
				</div><!--/span-->
				
			</div>  		';

	}
			
			
$script="<script>

	$('".$pNomeloja."').attr('selected',true);
	var nomeLoja = '".$pNomeloja."';
	if( nomeLoja != '')
		document.getElementById('esquerda').style.display = 'block';

	function onchangeEmpresa(){
		document.getElementById('esquerda').style.display = 'block';
		var Nomeloja=$('#cb_loja option:selected').text();
		$.get('modules/loja/index.php', {nomeloja:Nomeloja}, function(data){
			$('#content').html(data);
		}); 		
	}

	var id_loja = '".$id."';
	var op=1;
	
	function vendass(){
		if(id_loja != ''){
			$.get('modules/vendas/index.php',{'id_loja':id_loja, 'op':op}, function(data){
				$('#content').html(data);
			});
		}
	}
	
	function comprass(){
		if(id_loja != ''){
			$.get('modules/compras/index.php',{'id_loja':id_loja, 'op':op}, function(data){
				$('#content').html(data);
			});
		}
	}

	
		
	function stockss(){		
		if(id_loja != ''){
			$.get('modules/stocks/index.php',{'id_loja':id_loja}, function(data){
				$('#content').html(data);
			});
		}
	}

			
		
	function stocks_r(){		
		if(id_loja != ''){
			
			$.get('modules/stocks/index.php',{'id_loja':id_loja, 'op':op}, function(data){
				$('#content').html(data);
			});
		}
	}
		
	function stocks_d(){	
		var op_d=2;
		if(id_loja != ''){
			$.get('modules/stocks/index.php',{'id_loja':id_loja, 'op_d':op_d}, function(data){
				$('#content').html(data);
			});
		}
	}
	
		
	function vendass_m(){
		if(id_loja != ''){
			op=2;
			$.get('modules/vendas/index.php',{'id_loja':id_loja, 'op':op}, function(data){
				$('#content').html(data);
			});
		}
	}
	
	</script>";
	
	
echo ($content.$script);
	


?>
