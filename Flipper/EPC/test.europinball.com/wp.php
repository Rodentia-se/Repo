<?php

function customTemplate($page) {
	switch($page) {
		case 'registration':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/register.php');
	  break;
		case 'players':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/registered.php');
	  break;
		case 'editplayer':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/edit.php');
	  break;
		case 'resetpassword':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/forgotPassword.php');
	  break;
		case 'object':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/object.php');
	  break;
		case 'admin-tools':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/adminTools.php');
	  break;
		case 'test':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/test.php');
	  break;
	}
}

?>
 