<?php
//  https://www.flippersm.se/mobile/registermob.php?playerId=55&gameId=20&score=200000&user=bitwalk&password=abc123

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');
  noError();

  config::$login->verified = TRUE; // No nonce
  config::$login->action('login');
  $volunteer = volunteer('login');
  if ($volunteer->scorekeeper) {
    echo('hej');
    $tournament = getTournament('active');
    $division = division($tournament, 'recreational');
    debug($division, "DIV");
    $division = division($tournament, 'main');
    debug($division, "DIV");

    $personId = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
    $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;
    $prop = (isset($_REQUEST['prop'])) ? $_REQUEST['prop'] : NULL;
    $value = (isset($_REQUEST['value'])) ? $_REQUEST['value'] : NULL;
  } else {
    echo('statusCode=1'); // Login failed
  }
  debug($volunteer);
  
?>


