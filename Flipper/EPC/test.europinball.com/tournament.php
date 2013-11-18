<?php

  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');

/*  
  $country = new country(188);
  $locations = $country->getLocations();
  
  pre_dump($locations);
*/


  $tournament = new tournament(1);
  $divisions = $tournament->getDivisions();
  
  pre_dump($divisions);

?>