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
		case 'resetpassword':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/passwordReset.php');
	  break;
		case 'admin-tools':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/test.php');
	  break;
	}
}

?>
 