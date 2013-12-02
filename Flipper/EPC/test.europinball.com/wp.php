<?php

function customTemplate($page) {
	switch($page) {
		case 'registration':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/register.php');
	  break;
		case 'login':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/login.php');
	  break;
		case 'admin-tools':
			include($_SERVER['DOCUMENT_ROOT'].'/pages/test.php');
	  break;
	}
}

?>
