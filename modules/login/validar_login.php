<?php

include_once($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");


if(!isset($_SESSION['user'])){
	$empresa = $_GET["empresa"];
	$pass    = $_GET["password"];
	$user 	 = $_GET["user"];
	$grupo	 = null;


	//session_start();

	$codemp=-1;
	$moeda='';

	$rs=$db->execute("Select CodEmp,Moeda From soft_empresa where Nome = '".$empresa."'");
	if($rs){
		While($row = $rs->FetchRow()){
			$codemp=$row[0];
			$moeda=$row[1];
		}
	}


	$masterpiece = sha1($user.$pass);

	$queryLog = "Select ut.codutil, ut.grupo, ut.nomeutil  
								From soft_util ut, soft_acessos ac 
								where ac.codemp = '".$codemp."' 
								and ut.codutil = '".$user."'
								and ut.id = ac.codutil 
								and ut.passworddb='".$masterpiece."'
							UNION
								Select ut.codutil, ut.grupo, ut.nomeutil  
										From soft_util ut, soft_acessos ac 
										where ac.codemp = '".$codemp."' 
										and ut.codutil = '".$user."'
										and ut.id = ac.codutil 
										and ut.passworddb='".$masterpiece."'
								";
	$nomeutil = '';
	$rsX=$db->execute($queryLog);
	if($rsX){
		While($rowX = $rsX->FetchRow()){
			$grupo = $rowX[1];
			$nomeutil = $rowX[2];
		}
	} else {
		echo 'Not Working!';
	}

	if($grupo!=null){
		$_SESSION['state'] 	 = "active";
		$_SESSION['user'] 	 = $nomeutil;
		$_SESSION['codutil'] = $user;
		$_SESSION['nomeEmp'] = $empresa;
		$_SESSION['codemp']  = $codemp;
		$_SESSION['grupo'] 	 = $grupo;
		$_SESSION['moeda']   = $moeda;
		$_SESSION['ano']	 = date("Y");
		
		$sqlquery = "Select decimaispv from soft_configura where codemp = ".$codemp." and codutil = ".$user;
		$rs=$db->execute($sqlquery);
		if($rs){
			$counter = 0;
			While($row = $rs->FetchRow()){
				if (is_null($row[0]))
					$_SESSION['decimaispv'] = 2;
				else{
					$_SESSION['decimaispv'] = 3;
				}
			}
		} else {
			$_SESSION['decimaispv'] = 2;
		}
		

		echo 1;
	}else 
		echo 0;
} 
		

?>