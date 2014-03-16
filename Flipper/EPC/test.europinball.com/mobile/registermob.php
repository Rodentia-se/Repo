<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');
  noError();

  config::$login->verified = TRUE; // No nonce
  config::$login->action('login');
  $volunteer = volunteer('login');
  if ($volunteer->scorekeeper) {
    
  } else {
    echo('statusCode=1'); // Login failed
  }
  debug($volunteer);
  
?>

// https://www.flippersm.se/mobile/registermob.php?playerId=55&gameId=20&score=200000&user=bitwalk&password=abc123

