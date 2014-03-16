<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');

  config::$login->verified = TRUE; // No nonce
  config::$login->action('login');
  $volunteer = volunteer('login');
  if ($volunteer->scorekeeper) {
    echo('yes');
  } else {
    echo('statusCode=1'); // Login failed
  }
  debug($volunteer);
  
?>
