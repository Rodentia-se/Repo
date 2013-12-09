<?php

  require_once('classes.php');
  require_once('general.php');
  
  config::$login = new auth();
  
  if (isId($_REQUEST['tournament']) || isId($_REQUEST['t'])) {
    config::$currentTournament =(isId($_REQUEST['tournament'])) ? $_REQUEST['tournament'] : $_REQUEST['t'];
  }
  
  config::$login->huff = 'huff';
  
  if (isset($_REQUEST['nonce']) && ulNonce::Verify('login', $_REQUEST['nonce'])) {
    config::$login->verified = TRUE;
  }
  
  if ($_REQUEST['action']) {
    config::$login->action($_REQUEST['action']);
  }
  config::$login->huff = 'hepp';
  
  if (config::$login->loggedin() && !auth::$person) {
    if (isset($_SESSION['username']) && $_SESSION['username']) {
      auth::$person = person(array('username' => $_SESSION['username']), TRUE);
    } else if ($this->Username($_SESSION['uid'])) {
      $_SESSION['username'] = $this->Username($_SESSION['uid']);
      if (isset($_SESSION['username']) && $_SESSION['username']) {
        auth::$person = person(array('username' => $_SESSION['username']), TRUE);
      }
    }
  }

?>