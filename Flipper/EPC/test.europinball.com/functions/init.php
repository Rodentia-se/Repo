<?php

  require_once('classes.php');
  require_once('general.php');
  
  config::$login = new auth();

  if (isset($_REQUEST['debug'])) {
    config::$debug = ($_REQUEST['debug']);
    config::$showWarnings = ($_REQUEST['debug']);
    config::$showErrors = ($_REQUEST['debug']);
  }
  
  if (isset($_REQUEST['nonce']) && (!$ajax || $noLogin)) {
    if (ulNonce::Verify('login', $_REQUEST['nonce'])) {
      config::$login->verified = TRUE;
    } else {
      error('Invalid nonce');
    }
    debug('nonce checked');
  }

  if (isId($_REQUEST['tournament']) || isId($_REQUEST['t'])) {
    config::$currentTournament =(isId($_REQUEST['tournament'])) ? $_REQUEST['tournament'] : $_REQUEST['t'];
  }
  
  
  if ($_REQUEST['action']) {
    config::$login->action($_REQUEST['action']);
  }

  if (!config::$login->nonce) {
    $nonce = ulNonce::Create('login');
    config::$login->nonce = $nonce;
  }

  if (config::$login->loggedin() && !config::$login->person) {
    if (isset($_SESSION['username']) && $_SESSION['username']) {
      config::$login->person = person(array('username' => $_SESSION['username']), TRUE);
    } else if (config::$login->Username($_SESSION['uid'])) {
      $_SESSION['username'] = config::$login->Username($_SESSION['uid']);
      if (isset($_SESSION['username']) && $_SESSION['username']) {
        config::$login->person = person(array('username' => $_SESSION['username']), TRUE);
      }
    }
  }

?>