<?php

  require_once('classes.php');
  config::$login = new auth();
  require_once('general.php');
   
  if (isId($_REQUEST['tournament']) || isId($_REQUEST['t'])) {
    config::$currentTournament =(isId($_REQUEST['tournament'])) ? $_REQUEST['tournament'] : $_REQUEST['t'];
  }

?>