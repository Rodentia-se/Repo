<?php
  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  printHeader('EPC 2013', __baseHref__);

  if (checkLogin($dbh, $ulogin, true, 'Please use your login details to access the page.')) {
    printTopper("getObjects('geo');");
    $content = showEditPlayer($dbh, $ulogin);
    echo($content);
  }

  printFooter($dbh, $ulogin);
?>
