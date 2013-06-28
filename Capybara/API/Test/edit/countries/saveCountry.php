<?php
	
	include "../_define.php";
	
	if(!$sess->checkUrl(false,$_SESSION['basedir'].'/countries.php',false)) {
		$obj->status='Error';
		$obj->statusMsg=$lang->get('You do not have sufficient privileges to write to database').".";
		die(json_encode($obj));
	}

	$dw=new data_dataWriter($db);
	
	$countryid=$dw->writeGeneric($_POST,'country');
	
	print $dr->getCountryById($countryid)->getJSON();
	
?>