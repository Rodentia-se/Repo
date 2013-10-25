<?php
  require_once('../functions/general.php');
  header('Content-Type: text/plain');

  if (checkLogin($dbh, $ulogin, true, 'Please use your login details to access the page.')) {
    deNorm($dbh);
  }
  
  echo('Denormalization done');
?>
  