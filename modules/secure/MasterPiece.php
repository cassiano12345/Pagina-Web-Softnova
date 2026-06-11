<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/intranet/bin/inc.php");

$util = $_SESSION['codutil'];
	
//if (!isset($_SESSION['user']) || $_SESSION['user'] != 'SoftNova'){
if (!isset($_SESSION['user'])){
	echo "";
	header("Location: ../../index.php");
}
else{
	
	$mode	= $_GET['mode'];

	if($mode == 'INSERT'){
		
		$id 	= $_GET['identifier'];
		$master = $_GET['user'];
		$name 	= $_GET['name'];
		$piece 	= $_GET['pass'];
		$group	= $_GET['group'];
		
	
		if(strlen($master)>0 &&  strlen($master)<20){

			$masterpiece = sha1($master.$piece);
			
			$sql_query = 'SELECT CASE WHEN MAX(codutil) IS NULL THEN \'NO\' ELSE \'YES\' END User_exists
							FROM soft_util
							WHERE 	id  = \''.$id.'\' or codutil = \''.$master.'\'';
			
			
			$rs = $db->Execute($sql_query);
			if($rs){
				while($row = $rs->FetchRow()){
				
					if($row[0] == 'NO'){
						//user not exists -> insert
						$sql_insert = 'Insert into soft_util (codutil, passworddb, id, nomeutil, grupo, acesso) values (\''.$master.'\',\''.$masterpiece.'\','.$id.',\''.$name.'\','.$group.',\'N\')';
						//echo $sql_insert;
						$rs = $db->Execute($sql_insert);
						if($rs){
							
							
							$sql_insertX = 'Insert into soft_acessos (tipo, codutil, codemp) values (\'E\','.$id.','.$_SESSION['codemp'].')';
							//echo $sql_insert;
							$rsX = $db->Execute($sql_insertX);
							
							if($rsX)
								echo 1;
							else
								echo 0;
						}
						else{
							echo 'Error. Try again later.';
						}
						
					} else {
						echo '0';
					}
				}
			}
			else{
				echo 'erro!';
			}
		}else{
			echo 'Erro!';
		}
	} else if($mode == 'GETUSERS'){
		$users = array();
		$sql_query = 'Select su.id, su.codutil, su.nomeutil, sg.nome from soft_util su, dash_grupos sg where ((su.id >=1 and su.id < 990) or su.id = 999) and sg.idgrupo = su.grupo order by su.id asc';
		$rs = $db->Execute($sql_query);
			if($rs){
				$count = 0;
				While($row = $rs->FetchRow()){
					$tempUser = array();
					$tempUser['id'] 	  	= $row[0];
					$tempUser['codutil']  	= ($row[1]);
					$tempUser['nomeutil'] 	= ($row[2]);
					$tempUser['grupo'] 		= $row[3];
					$users[$count] 			= $tempUser;
					$count ++;
				}
			}
		echo json_encode($users);

	} else if($mode == 'ALTUSER'){

		$users = array();
		$sql_query = "Select su.id, su.codutil, su.nomeutil, sg.nome 
										from soft_util su, dash_grupos sg 
										where ((su.id >=1 and su.id < 990) or su.id = 999) 
										and sg.idgrupo = su.grupo 
										and su.codutil='".$util."'
										order by su.id asc ";
		$rs = $db->Execute($sql_query);
			if($rs){
				$count = 0;
				While($row = $rs->FetchRow()){
					$tempUser = array();
					$tempUser['id'] 	  	= $row[0];
					$tempUser['codutil']  	= ($row[1]);
					$tempUser['nomeutil'] 	= ($row[2]);
					$tempUser['grupo'] 		= $row[3];
					$users[$count] 			= $tempUser;
					$count ++;
				}
			}
		echo json_encode($users);
				
	} else if($mode == 'UPDATE'){
		
		$id 	 = $_GET['identifier'];
		$master  = $_GET['user'];
		$name 	 = $_GET['name'];
		//$piece 	 = $_GET['pass'];
		$group	 = $_GET['group'];
		//$passOld = $_GET['passAntiga'];
		
	
		if(strlen($master)>0 &&  strlen($master)<20){

			$masterpiece = sha1($master.$passOld);
			
			$sql_query = 'SELECT CASE WHEN MAX(codutil) IS NULL THEN \'NO\' ELSE \'YES\' END User_exists
							FROM soft_util
							WHERE  id =  \''.$id.'\'';
			//echo $sql_query;
			$rs = $db->Execute($sql_query);
			if($rs){
				while($row = $rs->FetchRow()){
					if($row[0] == 'NO'){
						//user not exists -> ERRO UPDATE
						echo json_encode('Erro! Dados de utilizador inválidos. Confirme o utilizador e a password.');
					} else {
						
						$sql_update = 'Update soft_util set codutil=\''.$master.'\', nomeutil=\''.$name.'\', grupo='.$group.'
										where id = '.$id;
						//echo $sql_update;	
						$rsX = $db->Execute($sql_update);
						if($rsX){
							echo json_encode(1);
							
						}else{
							echo json_encode($sql_update);
						}
						
					}
				}
			} else {
				echo json_encode("Erro. De momento não é possível adicionar utilizadores.");
			}
		
		}

	} else if($mode == 'UPDATEUTIL'){
		
		$id 	 = $_GET['identifier'];
		$master  = $_GET['user'];
		$name 	 = $_GET['name'];			
	
		if(strlen($master)>0 &&  strlen($master)<20){			
			
			$sql_query = 'SELECT CASE WHEN MAX(codutil) IS NULL THEN \'NO\' ELSE \'YES\' END User_exists
							FROM soft_util
							WHERE  id =  \''.$id.'\'';

			$rs = $db->Execute($sql_query);
			if($rs){
				while($row = $rs->FetchRow()){
					if($row[0] == 'NO'){
						//user not exists -> ERRO UPDATE
						echo json_encode('Erro! Dados de utilizador inválidos. Confirme o utilizador e a password.');
					} else {
						
						$sql_update = 'Update soft_util set codutil=\''.$master.'\', nomeutil=\''.$name.'\'
										where id = '.$id;						
						$rsX = $db->Execute($sql_update);
						if($rsX){
							echo json_encode(1);
							
						}else{
							echo json_encode($sql_update);
						}
						
					}
				}
			} else {
				echo json_encode("Erro. De momento não é possível adicionar utilizadores.");
			}
		
		}	
	}else if($mode == 'CONFIRMAPWD'){
	
		//COMENTADO POR CAUSA DA ENCRIPTAÇÃO SER DIF DA ENCRIPT USADA NO ORACLE
		
		/*$id 	= $_GET['identifier'];
		$master = $_GET['user'];
		$piece 	= $_GET['pass'];
	
		$masterpiece = sha1($master.$piece);
		
		$sql = "select 1 from soft_util where id = ".$id." and codutil='".$master."' and passworddb='".$masterpiece."'";
		//echo($sql);
		
		$rs = $db->Execute($sql);
		if($rs){
			while($row = $rs->FetchRow()){
				if($row[0] == 1)
					echo 1;
			}
		}*/
		echo 1;
	}
	else if($mode == 'UPDATEALL'){
		
		$id 	 = $_GET['identifier'];
		$master  = $_GET['user'];
		$name 	 = $_GET['name'];
		$piece 	 = $_GET['pass'];
		$group	 = $_GET['group'];
	
		if(strlen($master)>0 &&  strlen($master)<20){

			
			$sql_query = 'SELECT CASE WHEN MAX(codutil) IS NULL THEN \'NO\' ELSE \'YES\' END User_exists
							FROM soft_util
							WHERE  id =  \''.$id.'\'';
			//echo $sql_query;
			$rs = $db->Execute($sql_query);
			if($rs){
				while($row = $rs->FetchRow()){
					if($row[0] == 'NO'){
						//user not exists -> ERRO UPDATE
						echo json_encode('Erro! Dados de utilizador inválidos. Confirme o utilizador e a password.');
					} else {
						
						$newMasterPiece = sha1($master.$piece);
						
						$sql_update = 'Update soft_util set codutil=\''.$master.'\', nomeutil=\''.$name.'\', grupo='.$group.', passworddb = \''.$newMasterPiece.'\'
										where id = '.$id;
						//echo $sql_update;	
						$rsX = $db->Execute($sql_update);
						if($rsX){
							echo json_encode(1);
							
						}else{
							echo json_encode(0);
						}
						
					}
				}
			} else {
				echo json_encode("Erro. De momento não é possível adicionar utilizadores.");
			}
		
		}
	}else if($mode == 'UPDATEALLUSER'){
		
		$id 	 = $_GET['identifier'];
		$master  = $_GET['user'];
		$name 	 = $_GET['name'];
		$piece 	 = $_GET['pass'];		
	
		if(strlen($master)>0 &&  strlen($master)<20){

			
			$sql_query = 'SELECT CASE WHEN MAX(codutil) IS NULL THEN \'NO\' ELSE \'YES\' END User_exists
							FROM soft_util
							WHERE  id =  \''.$id.'\'';
			//echo $sql_query;
			$rs = $db->Execute($sql_query);
			if($rs){
				while($row = $rs->FetchRow()){
					if($row[0] == 'NO'){
						//user not exists -> ERRO UPDATE
						echo json_encode('Erro! Dados de utilizador inválidos. Confirme o utilizador e a password.');
					} else {
						
						$newMasterPiece = sha1($master.$piece);
						
						$sql_update = 'Update soft_util set codutil=\''.$master.'\', nomeutil=\''.$name.'\', passworddb = \''.$newMasterPiece.'\'
										where id = '.$id;
						//echo $sql_update;	
						$rsX = $db->Execute($sql_update);
						if($rsX){
							echo json_encode(1);
							
						}else{
							echo json_encode(0);
						}
						
					}
				}
			} else {
				echo json_encode("Erro. De momento não é possível adicionar utilizadores.");
			}
		
		}
			
	}else if($mode == 'GETGROUPID'){
	
		$nome = $_GET['nomegrupo'];
			
		$sql = "select idgrupo from DASH_GRUPOS where codemp = ".$_SESSION['codemp']." and nome='".$nome."'";
		
		$rs = $db->Execute($sql);
		if($rs){
			while($row = $rs->FetchRow()){
					echo $row[0];
			}
		}
	}
}

?>