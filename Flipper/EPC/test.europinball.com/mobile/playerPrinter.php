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
    $personId = (isset($_REQUEST['playerId'])) ? $_REQUEST['playerId'] : NULL;
    if (isId($personId)) {
      $person = person($personId);
      if (isPerson($person)) {
        echo $person->getQrLabel();
      }
    }
  }

?>
