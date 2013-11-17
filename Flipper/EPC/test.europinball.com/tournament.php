<?php

  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  $tournament = new tournament(1);
  
  echo '<pre>';
  var_dump($tournament);
  echo '</pre>';

?>