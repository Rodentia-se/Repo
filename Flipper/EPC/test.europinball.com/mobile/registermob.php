<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');

  config::$login->verified = TRUE;
  config::$login->action('login');
  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    echo('yes');
  } else {
    echo('no');
  }
  debug($volunteer);
  
?>
