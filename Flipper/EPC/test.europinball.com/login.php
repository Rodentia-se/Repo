<?php
  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  printHeader('EPC 2013', __baseHref__);
  printTopper();

  echo('<h3 class="entry-title">Login</h3>');

  if (!checkLogin($dbh, $ulogin)) {
    echo('<script type="text/javascript">document.getElementById(\'usernameLogin\').focus();</script>');
  }
  
  printFooter($dbh, $ulogin);
?>
