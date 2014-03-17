<?php
  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  if (!$page->loggedin()) {
    config::$login->verified = TRUE; // No nonce
    config::$login->action('login');
  }

  $volunteer = volunteer('login');
  if ($volunteer->scorekeeper) {
    $teamId = (isset($_REQUEST['teamId'])) ? $_REQUEST['teamId'] : NULL;
    if (isId($teamId)) {
      $team = team($teamId);
      if (isTeam($team)) {
        echo($team->getQrLabel());
      } else {
        echo('Can not find team ID '.$teamId);
      }
    } else {
      echo('Invalid team ID '.$teamId);
    }
  } else {
    echo('Login or authorization failed');
  }

?>
