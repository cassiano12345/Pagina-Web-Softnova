<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");
$content = "";
$contentOptions ='';

//session_start();

if (!isset($_SESSION['user'])){
	echo "";
	header("Location: ../../index.php");
}
else{
		
		$tipo 			= $_GET['tipo'];
		//$dataini 		= $_GET['dataini'];
		$dataini 		= date("d/m/Y");
		$datafim 		= date("d/m/Y");
		//$datafim 		= $_GET['datafim'];
		$tipo_qry 		= $_GET['tipo_qry'];
		//$procurar 		= $_GET['procurar'];

		
		switch($tipo){
			case 1:
				if ($tipo_qry==1){
					$sql_documentos =  "select vendedor, tipodoc, serie, nroficial, datadoc, total*decode(tipodoc,'DCL',-1,1), codmoeda codmoeda, nomecliente, anodoc, situacao from (
									      	(select vd.nome vendedor , ca.tipodoc tipodoc, ca.serie serie, ca.nroficial nroficial, 
									              to_char(ca.datadoc,'dd/mm/yyyy') datadoc, 
									              (nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0)) total, 
									              ca.codmoeda codmoeda, ca.nome nomecliente, ca.anodoc anodoc, nvl(ca.situacao,'*') situacao
									            from vendas_vdcab ca, ctb_vendedor vd	
									            where ca.codemp  					 = ".$_SESSION['codemp']."
									              /*and ca.tipodoc 					<> 'DCL' 
									              and ca.tipodoc 					<> 'FSC' 
									              and ca.tipodoc 					<> 'NCC'*/ 		
									              and vd.codemp 					 = ca.codemp
									              and vd.nrvd 						 = ca.vendedor 
									              and to_char(ca.datadoc,'yyyymmdd') = to_char(to_date('".date('d/m/Y')."','dd/mm/yyyy'), 'yyyymmdd'))
									              
									              
									      UNION 
									      
									     	(select vd.nome vendedor , ca.tipodoc tipodoc, ca.serie serie, ca.nroficial nroficial, 
									              to_char(ca.datadoc,'dd/mm/yyyy') datadoc, 
									              ((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))*decode(ca.tipodoc,'DCL',-1,1)) total, 
									              ca.codmoeda codmoeda, ca.nome nomecliente, ca.anodoc anodoc, nvl(ca.situacao,'*') situacao
									            from vendas_GRcab ca, ctb_vendedor vd	
									            where ca.codemp  					 = ".$_SESSION['codemp']." 
									              and ca.tipodoc 					 = 'GRM'
									              and ca.DocFact_N is null
									              and vd.codemp 					 = ca.codemp
									              and vd.nrvd 						 = ca.vendedor 
									              and to_char(ca.datadoc,'yyyymmdd') = to_char(to_date('".date('d/m/Y')."','dd/mm/yyyy'), 'yyyymmdd')
									             )
									          ) ";
	        						
					$rs = $db->execute($sql_documentos);
					if($rs){
						$response = array();
						$counter = 0;
						While($row = $rs->FetchRow()){
							$temp = array();
							$temp['vendedor'] 	 = ($row[0]);
							$temp['tipodoc'] 	 = $row[1];
							$temp['serie'] 		 = $row[2];
							$temp['nroficial'] 	 = $row[3];
							$temp['datadoc'] 	 = $row[4];
							$temp['total'] 		 = str_replace(',','.',$row[5]);
							$temp['codmoeda'] 	 = $row[6];
							$temp['nomecliente'] = ($row[7]);
							$temp['anodoc'] 	 = $row[8];
							$temp['situacao']	 = $row[9];
							$response[$counter] = $temp;
							$counter	 		+=1;
						}
					}
					else
					{
						echo $sql_documentos;
					}
				}

				else if ($tipo_qry==2){
											
					$sql_documentos =  "select vendedor, tipodoc, serie, nroficial, datadoc, total*decode(tipodoc,'DCL',-1,1), codmoeda codmoeda, nomecliente, anodoc, situacao from (
									      	(select vd.nome vendedor , ca.tipodoc tipodoc, ca.serie serie, ca.nroficial nroficial, 
									              to_char(ca.datadoc,'dd/mm/yyyy') datadoc, 
									              (nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0)) total, 
									              ca.codmoeda codmoeda, ca.nome nomecliente, ca.anodoc anodoc, nvl(ca.situacao,'*') situacao
									            from vendas_vdcab ca, ctb_vendedor vd	
									            where ca.codemp  					 = ".$_SESSION['codemp']."
									              /*and ca.tipodoc 					<> 'DCL' 
									              and ca.tipodoc 					<> 'FSC' 
									              and ca.tipodoc 					<> 'NCC'*/ 		
									              and TO_CHAR(ca.datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
																					and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
												  and vd.codemp 					 = ca.codemp
									              and vd.nrvd 						 = ca.vendedor 
									              and to_char(ca.datadoc,'yyyymmdd') = to_char(to_date('".date('d/m/Y')."','dd/mm/yyyy'), 'yyyymmdd'))
									              
									              
									      UNION 
									      
									     	(select vd.nome vendedor , ca.tipodoc tipodoc, ca.serie serie, ca.nroficial nroficial, 
									              to_char(ca.datadoc,'dd/mm/yyyy') datadoc, 
									              ((nvl(ca.total,0)- nvl(ca.iva01val,0) - nvl(ca.iva02val,0) - nvl(ca.iva03val,0) - nvl(ca.iva04val,0))*decode(ca.tipodoc,'DCL',-1,1)) total, 
									              ca.codmoeda codmoeda, ca.nome nomecliente, ca.anodoc anodoc, nvl(ca.situacao,'*') situacao
									            from vendas_GRcab ca, ctb_vendedor vd	
									            where ca.codemp  					 = ".$_SESSION['codemp']." 
									              and ca.tipodoc 					 = 'GRM'
									              and TO_CHAR(ca.datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
																					and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
												  and ca.DocFact_N is null
									              and vd.codemp 					 = ca.codemp
									              and vd.nrvd 						 = ca.vendedor 
									              and to_char(ca.datadoc,'yyyymmdd') = to_char(to_date('".date('d/m/Y')."','dd/mm/yyyy'), 'yyyymmdd')
									             )
									          ) ";




					$rs = $db->execute($sql_documentos);
					if($rs){
						$response = array();
						$counter = 0;
						While($row = $rs->FetchRow()){
							$temp = array();
							$temp['vendedor'] 	 = ($row[0]);
							$temp['tipodoc'] 	 = $row[1];
							$temp['serie'] 		 = $row[2];
							$temp['nroficial'] 	 = $row[3];
							$temp['datadoc'] 	 = $row[4];
							$temp['total'] 		 = str_replace(',','.',$row[5]);
							$temp['codmoeda'] 	 = $row[6];
							$temp['nomecliente'] = ($row[7]);
							$temp['anodoc'] 	 	= $row[8];
							$temp['situacao'] 	 	= $row[9];
							$response[$counter] = $temp;
							$counter	 		+=1;
						}
					}
				}
			
			break;
			
			case 2:
				if ($tipo_qry==1){
					//todos
						$sql_documentos =  "select tipodoc||'/'||serie||'/'||nroficial, datadoc, nome, ((nvl(total,0)- nvl(iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0))*decode(tipodoc,'DFR',-1,1)), anodoc, situacao
												from compras_cpcab
												where codemp = ".$_SESSION['codemp']." 
												  and TO_CHAR(datadoc,'yyyymmdd') = TO_CHAR(to_date('".date('d/m/Y')."', 'dd/mm/yyyy'),'yyyymmdd')
												order by datadoc desc, tipodoc||'/'||serie||'/'||nroficial desc ";			
						// echo $sql_documentos;	
						$rs = $db->execute($sql_documentos);
						if($rs){
							$response = array();
							$counter = 0;
							While($row = $rs->FetchRow()){
								$temp = array();
								$temp['tipodoc'] 	 = $row[0];
								$temp['datadoc'] 	 = $row[1];
								$temp['nome'] 		 = ($row[2]);
								$temp['totalmercli'] = str_replace(',','.',$row[3]);
								$temp['anodoc'] 	 = $row[4];
								$temp['situacao']	 = $row[5];
								$response[$counter] = $temp;
								$counter	 		+=1;
							}
						}
				}
				else if($tipo_qry==2){
					$sql_documentos =  "select tipodoc||'/'||serie||'/'||nroficial, to_char(datadoc,'dd/mm/yyyy'), nome, ((nvl(total,0)- nvl(iva01val,0) - nvl(iva02val,0) - nvl(iva03val,0) - nvl(iva04val,0))*decode(tipodoc,'DFR',-1,1)), anodoc
													from compras_cpcab
													where codemp = ".$_SESSION['codemp']."
													and TO_CHAR(datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
													and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
													order by datadoc desc, tipodoc||'/'||serie||'/'||nroficial desc";			
						// echo $sql_documentos;
					$rs = $db->execute($sql_documentos);
					if($rs){
						$response = array();
						$counter = 0;
						While($row = $rs->FetchRow()){
							$temp = array();
							$temp['tipodoc'] 	 = $row[0];
							$temp['datadoc'] 	 = $row[1];
							$temp['nome'] 		 = ($row[2]);
							$temp['totalmercli'] = str_replace(',','.',$row[3]);
							$temp['anodoc'] 	 = $row[4];
							$response[$counter] = $temp;
							$counter	 		+=1;
						}
					}
				}
			break;
	
			case 3:
				if ($tipo_qry==1){
					$sql_documentos =  " select ec.tipodoc||'/'||ec.serie||'/'||ec.nroficial, ec.datadoc, ec.nome, ec.total, ec.codmoeda, ec.anodoc
										from vendas_eccab ec
										where ec.codemp   = ".$_SESSION['codemp']."
											and ec.tipodoc  = 'ECL'
											and (situacao = 'E' or situacao = 'P' or situacao = 'C' or situacao = 'D')
										order by ec.datadoc desc, ec.tipodoc||'/'||ec.serie||'/'||ec.nroficial desc ";
						
						// echo $sql_documentos;
					$rs = $db->execute($sql_documentos);
					if($rs){
						$response = array();
						$counter = 0;
						While($row = $rs->FetchRow()){
							$temp = array();
							$temp['doc'] 	 		= $row[0];
							$temp['datadoc'] 	 	= $row[1];
							$temp['cliente'] 		= ($row[2]);
							$temp['total'] 	 		= str_replace(',','.',$row[3]);
							$temp['codmoeda'] 		= $row[4];
							$temp['anodoc'] 	 	= $row[5];
							$response[$counter] 	= $temp;
							$counter	 			+= 1;
						}
					}
				}
				else if ($tipo_qry==2){
					$sql_documentos =  " select ec.tipodoc||'/'||ec.serie||'/'||ec.nroficial, ec.datadoc, ec.nome, ec.totalmercli, ec.codmoeda, ec.anodoc
											from vendas_eccab ec
											where ec.codemp   = ".$_SESSION['codemp']."
											  and ec.tipodoc  = 'ECL'
											  and TO_CHAR(ec.datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
											  and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
											  and (situacao = 'E' or situacao = 'P' or situacao = 'C' or situacao = 'D')
											order by ec.datadoc desc, ec.tipodoc||'/'||ec.serie||'/'||ec.nroficial desc ";
											
					// echo $sql_documentos;
						
					$rs = $db->execute($sql_documentos);
					if($rs){
						$response = array();
						$counter = 0;
						While($row = $rs->FetchRow()){
							$temp = array();
							$temp['doc'] 	 		= $row[0];
							$temp['datadoc'] 	 	= $row[1];
							$temp['cliente'] 		= ($row[2]);
							$temp['total'] 	 		= str_replace(',','.',$row[3]);
							$temp['codmoeda'] 		= $row[4];
							$temp['anodoc'] 	 	= $row[5];
							$response[$counter] 	= $temp;
							$counter	 			+= 1;
						}
					}
				}
			
				if ($tipo_qry==3){
					$sql_documentos =  "select ec.datadoc, ec.tipodoc||'/'||ec.serie||'/'||ec.nroficial, 
											ec.nome, ec.total, ec.codmoeda,it.desart, ec.anodoc, it.qtd, it.qtde 
											from vendas_eccab ec, vendas_ecitem it
										where 	ec.codemp   	= ".$_SESSION['codemp']."
											and it.codemp		= ec.codemp
											and ec.tipodoc  	= 'ECL'
											and it.tipodoc 		= ec.tipodoc
											and (upper(desart) like upper('%".$procurar."%'))
											and ec.nroficial	= it.nroficial	
											and TO_CHAR(ec.datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
											and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
											and (ec.situacao 	= 'E' or ec.situacao = 'P' or ec.situacao = 'C' or ec.situacao = 'D')
										order by ec.datadoc desc,  ec.tipodoc||'/'||ec.serie||'/'||ec.nroficial desc";
					// echo $sql_documentos;
					
					$rs = $db->execute($sql_documentos);
					if($rs){
						$response = array();
						$counter = 0;
						While($row = $rs->FetchRow()){
							$temp = array();
							$temp['datadoc'] 	 	= $row[0];
							$temp['doc'] 	 		= $row[1];
							$temp['cliente'] 		= ($row[2]);
							$temp['total'] 	 		= str_replace(',','.',$row[3]);
							$temp['codmoeda'] 		= $row[4];
							$temp['desart'] 		= ($row[5]);
							$temp['anodoc'] 	 	= $row[6];
							$temp['qtd'] 	 	= $row[7];
							$temp['qtde'] 	 	= $row[8];
							$temp['qtdf'] 	 	= str_replace(',','.',(floatval($row[7]) - floatval($row[8]))); 
							
							$response[$counter] 	= $temp;
							$counter	 			+= 1;
						}
					}
				
				}
			break;
			
			case 4:

				if ($tipo_qry==1){
					$sql_documentos =  "select tipodoc||'/'||serie||'/'||nroficial, to_char(datadoc,'dd/mm/yyyy'), 
					nrfornecedor, nome, totalmercli, codmoeda, anodoc, iva01val, iva02val, iva03val, iva04val
										from compras_efcab
										where codemp  = ".$_SESSION['codemp']."
										and tipodoc = 'REF'
										and (situacao = 'E' or situacao = 'P' or situacao = 'C' or situacao = 'D')
										order by to_char(datadoc,'dd/mm/yyyy') desc, tipodoc||'/'||serie||'/'||nroficial desc ";
					//echo $sql_documentos;

					$rs = $db->execute($sql_documentos);
					if($rs){
						$response = array();
						$counter = 0;
						While($row = $rs->FetchRow()){ 
							$temp = array();
							$temp['doc'] 	 		= $row[0];
							$temp['datadoc'] 	 	= $row[1];
							$temp['nrfornecedor'] 	= $row[2];
							$temp['nome'] 	 		= ($row[3]);
							$temp['total'] 			= str_replace(',','.',(floatval($row[4]) + floatval($row[7]) + floatval($row[8]) + floatval($row[9]) + floatval($row[10])));
							$temp['codmoeda'] 		= $row[5];
							$temp['anodoc'] 	 	= $row[6];
							$response[$counter] 	= $temp;
							$counter	 			+= 1;
						}
					}
				break;
				
				}
				else if($tipo_qry==2){
					$sql_documentos =  "select tipodoc||'/'||serie||'/'||nroficial, to_char(datadoc,'dd/mm/yyyy'), nrfornecedor, nome, totalmercli, codmoeda, anodoc, iva01val, iva02val, iva03val, iva04val
										from compras_efcab
										where codemp  = ".$_SESSION['codemp']."
										and (situacao = 'E' or situacao = 'P' or situacao = 'C' or situacao = 'D')
										and tipodoc = 'REF'
										and TO_CHAR(datadoc,'yyyymmdd') between TO_CHAR(to_date('".$dataini."', 'dd/mm/yyyy'),'yyyymmdd')
										and TO_CHAR(to_date('".$datafim."','dd/mm/yyyy'),'yyyymmdd') 
										order by datadoc desc, tipodoc||'/'||serie||'/'||nroficial desc ";
										
					//echo $sql_documentos;
					$rs = $db->execute($sql_documentos);
					if($rs){
						$response = array();
						$counter = 0;
						While($row = $rs->FetchRow()){ 
							$temp = array();
							$temp['doc'] 	 		= $row[0];
							$temp['datadoc'] 	 	= $row[1];
							$temp['nrfornecedor'] 	= $row[2];
							$temp['nome'] 	 		= ($row[3]);
							$temp['total'] 			= str_replace(',','.',(floatval($row[4]) + floatval($row[7]) + floatval($row[8]) + floatval($row[9]) + floatval($row[10])));
							$temp['codmoeda'] 		= $row[5];
							$temp['anodoc'] 	 	= $row[6];

							$response[$counter] 	= $temp;
							$counter	 			+= 1;
						}
					}
				}
			break;
		}
			
	echo json_encode($response);
	
}


?>