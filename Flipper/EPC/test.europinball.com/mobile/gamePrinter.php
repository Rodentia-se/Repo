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
    $machineId = (isset($_REQUEST['gameId'])) ? $_REQUEST['gameId'] : NULL;
    if (isId($machineId)) {
      $machine = machine($machineId);
      if (isMachine($machine)) {
        echo $machine->getQrLabel();
      }
    }
  }

?>
