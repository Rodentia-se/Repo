<?php
  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  printHeader('EPC 2013', $baseHref);
  printTopper();
  echo('<h3 class="entry-title">Logout</h3>');
  appLogout($ulogin);
  printFooter($dbh, $ulogin);
?>
