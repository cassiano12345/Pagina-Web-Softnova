<?php
/*$p_code="admin";

$l_code="";
$l_n = strlen($p_code)-1;
($l_i-1) = 0;

while(($l_i-1) <= $l_n){
	($l_i-1) += 1;
	$temp_n=$l_n+1;
	$char=substr($p_code,($l_i-1)-1,1);
	$l_code = ord($char)+substr($l_code,1,15);
	$l_code = intval(ord($char)*$temp_n/(($l_i-1)/(1+(($l_i-1)/2))));
	$l_code = sqrt($l_code);
	$l_code = substr($l_code,1,15);
}

$l_code=str_replace(".","",$l_code);

//echo "<script>alert('pass:'+".$l_code."+'  XX  final:  33150392256662 ')<script>";
//echo "<br>final:  33150392256662 ";

echo "pass: ".$l_code;
echo "<br>final:  33150392256662 ";

//echo 1;
*/
$p_code = "admin";

$l_n = strlen($p_code);
($l_i-1) = 0;
$l_code = "";

while (($l_i-1) < $l_n){ 
	  
	$l_i = $l_i + 1;  
	
	echo "<br>".substr($p_code,($l_i-1),1)." vs ".ord(substr($p_code,($l_i-1),1))."<br>";
	
	if (!is_null(ord(substr($p_code,($l_i-1),1)))){
		$l_code = ord(substr($p_code,($l_i-1),1)) + substr($l_code,1,15) . round(ord(substr($p_code,($l_i-1),1))*$l_n/(($l_i-1)/(1+(($l_i-1)/2))), 0, PHP_ROUND_HALF_DOWN);; 
	} else {
		$l_code = 0 + substr($l_code,1,15) . round(ord(substr($p_code,($l_i-1),1))*$l_n/(($l_i-1)/(1+(($l_i-1)/2))), 0, PHP_ROUND_HALF_DOWN); 
	}

	$l_code = sqrt($l_code);
    $l_code = substr($l_code,1,15);
	
echo "<br>".$l_code;
}


echo "<br><br>".str_replace(',','',str_replace('.','',$l_code));

?>