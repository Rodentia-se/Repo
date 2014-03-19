<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');
  noError(TRUE, TRUE, FALSE);

  $page = new page('Register');
  if (!$page->loggedin()) {
    config::$login->verified = TRUE; // No nonce
    config::$login->action('login');
  }

  $volunteer = volunteer('login');
  if ($volunteer->scorekeeper) {
    echo('statusCode=0');
  } else {
    echo('statusCode=1');
  }

?>
