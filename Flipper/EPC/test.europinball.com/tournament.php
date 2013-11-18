<?php

  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  $city = new city(1930);
  $locations = $city->getLocations();
  
  pre_dump(location::$instances);

?>