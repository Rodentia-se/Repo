<?php

function customTemplate($page) {
	switch($page) {
		case 'registration':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/register.php');
	  break;
		case 'players':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/registered.php');
	  break;
		case 'edit':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/edit.php');
	  break;
		case 'password-reset':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/forgotPassword.php');
	  break;
		case 'admin-tools':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/test.php');
	  break;
	}
}

?>
 