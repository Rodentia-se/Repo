<?php

  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');

/*  
  $country = new country(188);
  $locations = $country->getLocations();
  
  preDump($locations);
*/

/*
  $tournament = new tournament(1);
//  $divisions = $tournament->getDivisions();
  
  preDump($tournament->location->city->region->continent);
*/

$a = array('hej', 'hepp', 'huff');
preDump(!isAssoc($a));
$a = array(1 => 'hej', 3 => 'hepp', 2 => 'huff');
preDump(!isAssoc($a));
$a = array('hej' => 'hej', 'hepp' => 'hepp', 'huff' => 'huff');
preDump(!isAssoc($a));

?>