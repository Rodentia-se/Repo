<?php
  define('__ROOT__', dirname(dirname(dirname(__FILE__)))); 
  require_once(__ROOT__.'/functions/init.php');
  
  header('Content-Type: text/plain; charset=utf-8');
  
  $division_id = (isset($_REQUEST['division'])) ? $_REQUEST['division'] : 1;
  $division = division($division);
  
  $players = players($division);
  debug($players);
  $players->order('wpprPlace');
  
  echo "Tournament Name,Date,Finishing Position,Player,Country,IFPA ID\n";
  foreach ($players as $player) {
    echo 'European Pinball Championships '.(($division == 2) ? 'Classics ' : '').'2013,2013-09-13,'.$place.','.$player->name.','.$player->countryName.','.$player->ifpa_id."\n";
  }
 ?>