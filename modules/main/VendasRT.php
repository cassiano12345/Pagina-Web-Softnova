<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

           
 
if(isset($_SESSION['user'])){

	$tag = $_GET['tag'];
	
	
	switch ($tag) {
	
		case 'dataHoras':
				/*
					Retorna dois tipos de datasets. ambos da pagina inicial. 
					Retorna os valores acumulados ao longo do dia ou valores vendidos por hora dependendo da flag "acc".
				*/
			//para verificar se é acumulado ou não
			$acc = $_GET['acc'];
			$today = date("Y/m/d");

            
			$sql_hora = " select didatadocX, dlnomeX, dlcorX, sum(h0), sum(h1), sum(h2), sum(h3), sum(h4), sum(h5), sum(h6), 
                                    sum(h7), sum(h8), sum(h9), sum(h10), sum(h11), sum(h12), sum(h13),
                                    sum(h14),sum(h15), sum(h16), sum(h17),sum(h18), sum(h19), sum(h20),
                                    sum(h21), sum(h22), sum(h23)
            from (select di.datadoc didatadocX, dl.nome dlnomeX, dl.cor dlcorX ,
	            /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'00',
                /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h0,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'01',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h1,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'02',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h2,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'03',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h3,  			          
                /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'04',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h4, 
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'05',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h5,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'06',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h6,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'07',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h7,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'08',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h8,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'09',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h9,                                                         
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'10',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h10,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'11',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h11,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'12',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h12,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'13',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h13,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'14',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h14,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'15',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h15,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'16',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h16,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'17',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h17,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'18',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h18,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'19',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h19,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'20',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h20,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'21',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h21,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'22',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h22,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'23',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h23
                  from Vendas_VDCab DI, Vendas_VDItem it, dash_lojas dl 
							    where DI.CodEmp = ".$_SESSION['codemp']."
							      and DI.TipoDoc not in ('DAF','FAF')
							      and DI.AnoDoc = ".$_SESSION['ano']."
                    and to_char(di.datareg, 'yyyymmddHH24MISS') between 
                        to_char(to_date('".$today." 00:00:00','yyyy/mm/dd HH24:MI:SS'), 'yyyymmddHH24MISS') 
                        and
                        to_char(to_date('".$today." 23:59:59','yyyy/mm/dd HH24:MI:SS'), 'yyyymmddHH24MISS') 
							      and nvl(DI.Situacao,'*') <> 'A' 
                    and it.codemp     = di.codemp
                    and it.tipodoc    = di.tipodoc
                    and it.serie      = di.serie
                    and it.nroficial  = di.nroficial
                    and it.anodoc     = di.anodoc
                    and dl.codemp     = it.codemp
                    and dl.id in (select dl.id 
                                    from dash_lojas dl, dash_acessos ac
                                    where dl.codemp   = ".$_SESSION['codemp']."
                                    and ac.codemp     = dl.codemp
                                    and ac.idgrupo    = ".$_SESSION['grupo']."
                                    and dl.id         = ac.idloja)
                    and it.codarm = dl.armazem
                   group by  di.datadoc, dl.nome, dl.cor
                   
                   
                   
                   UNION
                   
                   
                   
                   select di.datadoc didatadocX, dl.nome dlnomeX, dl.cor dlcorX ,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'00',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h0,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'01',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h1,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'02',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h2,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'03',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h3,  			          
                /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'04',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h4, 
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'05',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h5,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'06',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h6,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'07',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h7,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'08',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h8,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'09',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h9,                                                         
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'10',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h10,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'11',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h11,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'12',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h12,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'13',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h13,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'14',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h14,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'15',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h15,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'16',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h16,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'17',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h17,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'18',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h18,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'19',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h19,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'20',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h20,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'21',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h21,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'22',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h22,
			         /* DECODE A HORA */         nvl(sum(decode(to_char(di.datareg,'HH24'),'23',
               /* DECODE AO IVA INCLUIDO*/                  decode(di.isentoiva,'I',
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                  'FSC',-((it.VALORLI ) * ((it.taxaiva/100)+1)),
                                                                                         ((it.VALORLI ) * ((it.taxaiva/100)+1)) )
                                                                                          /  DI.Cambio),
                                                                      (decode(replace(DI.TipoDoc,'NCC','DCL'),
                                                                                  'DCL',-((it.VALORLI )),
                                                                                  'FSC',-((it.VALORLI )),
                                                                                         ((it.VALORLI )) )
                                                                                          /  DI.Cambio) )
                                                        )),0) h23
                  from Vendas_GRCab DI, Vendas_GRItem it, dash_lojas dl 
							    where DI.CodEmp = ".$_SESSION['codemp']."
							      and DI.TipoDoc = 'GRM'
							      and DI.AnoDoc = ".$_SESSION['ano']."
                    and to_char(di.datareg, 'yyyymmddHH24MISS') between 
                        to_char(to_date('".$today." 00:00:00','yyyy/mm/dd HH24:MI:SS'), 'yyyymmddHH24MISS') 
                        and
                        to_char(to_date('".$today." 23:59:59','yyyy/mm/dd HH24:MI:SS'), 'yyyymmddHH24MISS') 
                    and nvl(DI.Situacao,'*') <> 'A'       
					and di.DocFact_N is null 
                    and it.codemp     = di.codemp
                    and it.tipodoc    = di.tipodoc
                    and it.serie      = di.serie
                    and it.nroficial  = di.nroficial
                    and it.anodoc     = di.anodoc
                    and dl.codemp     = it.codemp
                    and dl.id in (select dl.id 
                                    from dash_lojas dl, dash_acessos ac
                                    where dl.codemp   = ".$_SESSION['codemp']."
                                    and ac.codemp     = dl.codemp
                                    and ac.idgrupo    = ".$_SESSION['grupo']."
                                    and dl.id         = ac.idloja)
                    and it.codarm = dl.armazem
                   group by  di.datadoc, dl.nome, dl.cor
                   
                   
                   )  group by  didatadocX, dlnomeX, dlcorX";


			$rs=$db->execute($sql_hora);
			if($rs){
			
				$ds = array();
			
				$counter 		= 0;
				$labels 		= array();
				$datasetData	= array();
				$cores			= array();
				$hasdata 		= false;
				$series			= array();
				
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$hasdata = false;
					else{
						$hasdata = true;
						$temp = array();
						$accum = 0;
						
						for($i=0; $i<24; $i++){ 
							if($acc == 'true'){
								$datasetData[$i] = $row[$i+3] + $accum;
								$accum = $datasetData[$i];
							} else {
								$datasetData[$i] = floatval($row[$i+3]);
							}
							$labels[$i] = $i.'h';
						}
						$ds[$counter] 		=  $datasetData;
						$series[$counter]	=  ($row[1]);
						$cores[$counter]	=  ($row[2]);
						$counter 			+= 1;
					}
				}

				if($hasdata){
					for($i=0; $i<sizeof($ds); $i++){
						$fillColorR             = getColorR();
                        $fillColorG             = getColorG();
                        $fillColorB             = getColorB();
                        $strokeColorR           = $fillColorR + 10; 
                        $strokeColorG           = $fillColorG + 10; 
                        $strokeColorB           = $fillColorB + 10; 
                        $pointColorR            = $fillColorR - 10; 
                        $pointColorG            = $fillColorG - 10; 
                        $pointColorB            = $fillColorB - 10; 
                        $pointHighlightFillR    = $fillColorR - 20; 
                        $pointHighlightFillG    = $fillColorG - 20; 
                        $pointHighlightFillB    = $fillColorB - 20; 
                         
                        $fillColor          = "rgba(".$fillColorR.",".$fillColorG.",".$fillColorB.",0.4)";
                        $strokeColor        = "rgba(".$strokeColorR.",".$strokeColorG.",".$strokeColorB.",0.7)";
                        $pointColor         = "rgba(".$pointColorR.",".$pointColorG.",".$pointColorB.",0.9)";
                        $pointHighlightFill = "rgba(".$pointHighlightFillR.",".$pointHighlightFillG.",".$pointHighlightFillB.",0.9)";

					
						$dataset[$i] = array("label" => $series[$i], "fillColor" => $fillColor, "strokeColor" => $strokeColor, "pointColor" => $pointColor, "pointHighlightFill" => $pointHighlightFill, "data" => ($ds[$i]));
					}
					$finalDS = array("labels" => $labels, "datasets" => $dataset);
					echo json_encode($finalDS);
				}
				else
					echo 0;
			} else 
				echo 0;
				
		break;
		/*
		*
		*	ESTA QUERY DEVOLVE UMA LINHA POR CADA LOJA QUE O UTILIZADOR TEM ACESSO
		*	CADA LINHA DESSAS TEM O TOTAL QUE CADA LOJA JA VENDEU NO PRESENTE DIA ATE AO MOMENTO
		*	ESTA INFORMACAO VAI "CAMUFLADA" COMO SENDO UM DATASET COMPLETO
		*	NO ENTANTO DO LADO DO CLIENTE SERA NECESSARIO O NOME DA LOJA E O VALOR AMEALHADO ATE ENTAO
		*	
		*/
        
		case 'dataToChart':
			$vendasRT = 0;

			$rs=$db->execute("select nomeloja, sum(ValorLI) from (
								-- Vendas
								    select sum(decode(dl.armazem, it.codarm,decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
								                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
								                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
								                                                      /  DI.Cambio)) ValorLI, 
					          dl.nome nomeloja                                                
								    from Vendas_VDCab DI, Vendas_VDItem it, dash_lojas dl
								    where DI.CodEmp     = ".$_SESSION['CodEmp']."
							        and DI.TipoDoc not in ('DAF','FAF')
							        and DI.AnoDoc     = ".$_SESSION['ano']."
							        and nvl(DI.Situacao,'*') <> 'A' 
						            and it.codemp     = di.codemp
						            and it.tipodoc    = di.tipodoc
						            and it.serie      = di.serie
						            and it.nroficial  = di.nroficial
						            and it.anodoc     = di.anodoc
						            and dl.codemp     = it.codemp
						            and dl.ARMAZEM    = it.CODARM
					          group by dl.nome
								  UNION    
								  
								  -- Guias de remessa ainda não faturadas
								    select sum(decode(dl.armazem, it.codarm,decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
								                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
								                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
								                                                          /  DI.Cambio)) ValorLI,
					            dl.nome  nomeloja                                                    
								    from Vendas_GrCab DI , VENDAS_GRITEM it, dash_lojas dl
								    where di.CodEmp        = ".$_SESSION['codemp']."
								    and DI.TipoDoc    = 'GRM'            
								    and DI.AnoDoc     = ".$_SESSION['ano']."
								    and nvl(DI.Situacao,'*') <> 'A'       
								    and DocFact_N is null
						            and it.codemp     = di.codemp
						            and it.tipodoc    = di.tipodoc
						            and it.serie      = di.serie
						            and it.nroficial  = di.nroficial
						            and it.anodoc     = di.anodoc
						            and dl.codemp     = it.codemp
						            and dl.ARMAZEM    = it.CODARM
									group by dl.nome
					        )
					      group by nomeloja");




			if($rs){
				$counter 		= 0;
				$labels 		= array();
				$datasetData	= array();
				
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$datasets = "null";
					else{
						$labels[$counter] = $row[0];
						
						$datasetData[$counter]	= $row[1];
						$counter = $counter +1;
					}
				}
				
				$finalDataSets = array();
				$finalDataSets[0] = getSomeColors($datasetData, $labels); 
				
				$datasets = array("labels" => ($labels), "datasets" => ($finalDataSets));
				echo json_encode($datasets);
			}
		break;
		
		case 'initiateDatasets':
			
			$rs=$db->execute("select max(rownum) from ( 
									select dl.nome, 
									  sum(decode(dl.armazem, it.codarm, nvl(it.valorli,0),0)) 
									from dash_lojas dl , vendas_vditem it, vendas_vdcab ca 
									where ca.codemp    = ".$_SESSION['codemp']."
									  and ca.tipodoc  <> 'DCL' 
									  and ca.tipodoc  <> 'FSC' 
									  and ca.tipodoc  <> 'NCC' 
									  and it.codemp    = ca.codemp
									  and it.nroficial = ca.nroficial
									  and it.serie     = ca.serie
									  and it.datadoc   = ca.datadoc
									  and dl.codemp    = it.codemp
									  and to_char(it.datadoc,'YYYY/MM/DD') = to_char(sysdate,'YYYY/MM/DD') 
									  and dl.id in (select dl.id 
													  from dash_lojas dl, dash_acessos ac
													  where dl.codemp = ".$_SESSION['codemp']." 
														and ac.codemp = dl.codemp
														and ac.idgrupo = ".$_SESSION['grupo']." 
														and dl.id = ac.idloja)
								   group by dl.nome    
								)");
			if($rs){
				$nrOfDatasets 		= 0;
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$nrOfDatasets = "null";
						
					else{
						$nrOfDatasets = $row[0];
					}
				}
				$labels = array();
				
				$finalDataSets = array();
				for($i = 0; $i< $nrOfDatasets; $i++){
					$finalDataSets[$i] = getSomeColors([0,0,0,0,0,0,0,0], ['']); 
				}
				
				for($j=0;$j<7;$j++){
					$labels[$j] = '';
				}
				
			
				$datasets = array("labels" => $labels, "datasets" => ($finalDataSets));
				
				echo json_encode($datasets);
				
			} else {
				echo 'Error';
			}
		break;
		
		
		case 'dataToPieChart': //RETORNA UM ELEMENTO JSON COM TODOS OS ELEMENTOS EM CADA UM DELES É UM DATASET PARA UM GRAFICO
			
			$vendasRT = 0;
		
			$queryex = "select nomeloja, sum(ValorLI) from (
								-- Vendas
								    select nvl(sum(decode(dl.armazem, it.codarm,decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
								                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
								                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
								                                                      /  DI.Cambio)),0) ValorLI, 
					            		dl.nome nomeloja                                                
								    from Vendas_VDCab DI, Vendas_VDItem it, dash_lojas dl
								    where DI.CodEmp       = ".$_SESSION['codemp']." 
								      	and DI.TipoDoc not in ('DAF','FAF')
								      	and DI.AnoDoc     = ".$_SESSION['ano']."
                      					and to_char(di.datadoc,'yyyymmdd') = to_char(sysdate,'yyyymmdd')
								      	and nvl(DI.Situacao,'*') <> 'A' 
					            		and it.codemp     = di.codemp
							            and it.tipodoc    = di.tipodoc
							            and it.serie      = di.serie
							            and it.nroficial  = di.nroficial
							            and it.anodoc     = di.anodoc
							            and dl.codemp     = it.codemp
		                      			and dl.id in (select dl.id 
													  from dash_lojas dl, dash_acessos ac
													  where dl.codemp  = ".$_SESSION['codemp']." 
														and ac.codemp  = dl.codemp
														and ac.idgrupo = ".$_SESSION['grupo']."
														and dl.id = ac.idloja)
					          		group by dl.nome
								  UNION    
								  
								  -- Guias de remessa ainda não faturadas
								    select nvl(sum(nvl(decode(dl.armazem, it.codarm,decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
								                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
								                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
								                                                      /  DI.Cambio),0)),0) ValorLI, 
			            				dl.nome  nomeloja                                                    
								    from Vendas_GrCab DI , VENDAS_GRITEM it, dash_lojas dl
								    where di.CodEmp       = ".$_SESSION['codemp']." 
								    	and DI.TipoDoc    = 'GRM'            
								      	and DI.AnoDoc     = ".$_SESSION['ano']."
                     				 	and to_char(di.datadoc,'yyyymmdd') = to_char(sysdate,'yyyymmdd')
								      	and nvl(DI.Situacao,'*') <> 'A'       
								      	and DocFact_N is null
							            and it.codemp     = di.codemp
							            and it.tipodoc    = di.tipodoc
							            and it.serie      = di.serie
							            and it.nroficial  = di.nroficial
							            and it.anodoc     = di.anodoc
					                    and dl.codemp     = it.codemp
					                    and dl.id in (select dl.id 
													  from dash_lojas dl, dash_acessos ac
													  where dl.codemp  = ".$_SESSION['codemp']." 
														and ac.codemp  = dl.codemp
														and ac.idgrupo = ".$_SESSION['grupo']." 
														and dl.id = ac.idloja)
									group by dl.nome
					        )
					      group by nomeloja ";

			$rs=$db->execute($queryex);
					
			$counter 		= 0;
						
			if($rs){
				$counter 		= 0;
				$datasetData	= array();
				$total			= 0;
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$datasetData = null;				
					else{
						$labels[$counter] = ($row[0]);
						$total += $row[1];
						
					
						$finalTemp =  array();
						$finalTemp['value'] 	= str_replace(',','.',$row[1]);
						$finalTemp['color'] 	= 'rgba(50, 145, 212, 0.9)';
						$finalTemp['highlight'] = 'rgba(120, 199, 255, 0.9)';
						$finalTemp['label']		= ($row[0]);
						$finalDataSet[0]		= $finalTemp;
						
						$inverse =  array();
						$inverse['value'] 		= 0;
						$inverse['color'] 		= 'rgba(206, 204, 207, 0.9)';
						$inverse['highlight'] 	= 'rgba(16, 46, 57, 0.9)';
						$inverse['label']		= '';
						$finalDataSet[1] = $inverse;
					
					
						$datasetData[$counter]  = $finalDataSet;
						$counter 				= $counter +1;
					}
				}
				
				for($i =0; $i < $counter; $i++){
					$datasetvalue 				 = floatval($datasetData[$i][0]['value']);
					$percentagem  				 = number_format(floatval(($datasetvalue*100)/$total), 2, '.',',');
					$datasetData[$i][0]['value'] = $percentagem;
					$datasetData[$i][1]['value'] = (100-$percentagem);
				}
			
				echo json_encode($datasetData); 
			}
		break;
		
		
		case 'comparativoAnoMes':
			
			$anoini = $_GET['anoini'];
			$anofim = $_GET['anofim'];
			
			$sqlMonthYear = "select anoX, sum(h1), sum(h2), sum(h3), sum(h4), sum(h5), sum(h6), sum(h7), sum(h8), sum(h9), sum(h10), sum(h11), sum(h12)
											from (
											-- Vendas
								    select di.anodoc anox,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'01',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h1  ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'02',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h2    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'03',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h3    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'04',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h4    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'05',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h5    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'06',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h6    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'07',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h7    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'08',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h8    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'09',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h9    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'10',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h10    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'11',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h11    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'12',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h12                                          
				                  from Vendas_VDCab DI  
								    where DI.CodEmp = ".$_SESSION['codemp']."
								      	and DI.TipoDoc not in ('DAF','FAF')
								      	and (DI.AnoDoc = ".$anoini." or di.anodoc = ".$anofim.")
				                   		and (to_char(di.datadoc,'yyyy') = ".$anoini." or to_char(di.datadoc,'yyyy') = ".$anofim.") 
										and nvl(DI.Situacao,'*') <> 'A' 
				                  group by di.anodoc
							  UNION    
							  -- Guias de remessa ainda não faturadas
			    				select di.anodoc anox,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'01',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h1,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'02',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h2    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'03',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h3    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'04',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h4    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'05',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h5    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'06',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h6    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'07',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h7    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'08',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h8    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'09',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h9    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'10',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h10    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'11',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h11    ,
			                        nvl(sum(decode(to_char(di.datadoc,'MM'),'12',
			                            decode(replace(DI.TipoDoc,'NCC','DCL'),'DCL',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                      'FSC',-( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ),
										                                                             ( nvl(DI.Total,0) - nvl(DI.Iva01Val,0) - nvl(DI.Iva02Val,0) - nvl(DI.Iva03Val,0) - nvl(DI.Iva04Val,0) ))
										                                                      /  DI.Cambio)),0) h12    
							    from Vendas_GrCab DI 
							    where di.CodEmp = ".$_SESSION['codemp']." 
						    		and DI.TipoDoc = 'GRM'            
							      	and (DI.AnoDoc = ".$anoini." or di.anodoc = ".$anofim.")
                    				and (to_char(di.datadoc,'yyyy') = ".$anoini." or to_char(di.datadoc,'yyyy') = ".$anofim.") 
							      	and nvl(DI.Situacao,'*') <> 'A'       
							      	and DocFact_N is null
                  				group by di.anodoc 
                ) group by anoX ";


			$rs=$db->execute($sqlMonthYear);
						
			if($rs){
				$counter 		= 0;
				$series 		= array();
				$datasetData	= array();
				
				While($row = $rs->FetchRow()){
					if (is_null($row[0]))
						$datasets = "null";
					else{
						$series[$counter] = $row[0];
						
						$tempDataSet 	 = array();
						$tempDataSet[0]  = $row[1];
						$tempDataSet[1]  = str_replace(',','.',$row[2]);
						$tempDataSet[2]  = str_replace(',','.',$row[3]);
						$tempDataSet[3]  = str_replace(',','.',$row[4]);
						$tempDataSet[4]  = str_replace(',','.',$row[5]);
						$tempDataSet[5]  = str_replace(',','.',$row[6]);
						$tempDataSet[6]  = str_replace(',','.',$row[7]);
						$tempDataSet[7]  = str_replace(',','.',$row[8]);
						$tempDataSet[8]  = str_replace(',','.',$row[9]);
						$tempDataSet[9]  = str_replace(',','.',$row[10]);
						$tempDataSet[10] = str_replace(',','.',$row[11]);
						$tempDataSet[11] = str_replace(',','.',$row[12]);
						
						
						$datasetData[$counter]	= $tempDataSet;
						$counter = $counter +1;
						
					}
				}
				
				$meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
				
				$finalDataSets = array();

				for($i = 0; $i< sizeof($datasetData); $i++){
					$finalDataSets[$i] = getSomeColors($datasetData[$i], $series[$i]); 
				}
				
				$datasets = array("labels" => ($meses), "datasets" => ($finalDataSets));
				 
				echo json_encode($datasets);	
			} else {
				echo 'Error: '.$db->ErrorMsg();
			}
			
		break;
	}
}
else{
	header("Location: ../../index.php");
}


function getSomeColors($dataset, $serie){
	$fillColorR 			= getColorR();//rand(101,244);
	$fillColorG 			= getColorG();//rand(101,244);
	$fillColorB 			= getColorB();//rand(101,244);
	$strokeColorR 			= $fillColorR + 10; 
	$strokeColorG 			= $fillColorG + 10; 
	$strokeColorB 			= $fillColorB + 10; 
	$pointColorR 			= $fillColorR - 10; 
	$pointColorG 			= $fillColorG - 10; 
	$pointColorB 			= $fillColorB - 10; 
	$pointHighlightFillR 	= $fillColorR - 20; 
	$pointHighlightFillG 	= $fillColorG - 20; 
	$pointHighlightFillB 	= $fillColorB - 20; 
	 
	$fillColor 			= "rgba(".$fillColorR.",".$fillColorG.",".$fillColorB.",0.4)";
	$strokeColor 		= "rgba(".$strokeColorR.",".$strokeColorG.",".$strokeColorB.",0.7)";
	$pointColor 		= "rgba(".$pointColorR.",".$pointColorG.",".$pointColorB.",0.9)";
	$pointHighlightFill = "rgba(".$pointHighlightFillR.",".$pointHighlightFillG.",".$pointHighlightFillB.",0.9)";

	//echo $dataset;	
	return array("label" => $serie, "fillColor" => $fillColor, "strokeColor" => $strokeColor, "pointColor" => $pointColor, "pointHighlightFill" => $pointHighlightFill, "data" => ($dataset));
}


function getColorR(){
	$colorR = rand(101,244);
	return $colorR;
}

function getColorG(){
	$colorG = rand(101,244);
	return $colorG;
}

function getColorB(){
	$colorB = rand(101,244);
	return $colorB;
}




/* Convert hexdec color string to rgb(a) string */
function hex2rgba($color, $opacity = false) {
 
	$default = 'rgb(0,0,0)';
 
	//Return default if no color provided
	if(empty($color))
          return $default; 
 
	//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }
 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }
 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
 
        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }
 
        //Return rgb(a) color string
        return $output;
}

?>