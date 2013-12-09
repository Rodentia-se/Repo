<?php

  require_once('classes.php');
  require_once('general.php');
  config::$login = new auth();
   
  if (isId($_REQUEST['tournament']) || isId($_REQUEST['t'])) {
    config::$currentTournament =(isId($_REQUEST['tournament'])) ? $_REQUEST['tournament'] : $_REQUEST['t'];
  }

?>