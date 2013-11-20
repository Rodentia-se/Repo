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

$player = new person(array('ifpa_id' => '27', 'mailAddress' => 'the@pal.pp.se'), true);
echo 'hej';
preDump($player);

?>