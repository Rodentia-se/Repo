<?php
  define('__ROOT__', dirname(dirname(dirname(__FILE__)))); 
  require_once(__ROOT__.'/functions/init.php');
  
  header('Content-Type: text/plain; charset=utf-8');
  
  $division_id = (isset($_REQUEST['division'])) ? $_REQUEST['division'] : 1;
  $division = division($division_id);
  
  $players = players($division);
  $players->order('wpprPlace', 'numeric');
  $players->filter('wpprPlace', TRUE);
    
  echo "Tournament Name,Date,Finishing Position,Player,Country,IFPA ID\n";
  foreach ($players as $player) {
    echo 'European Pinball Championships '.$division->divisionNam).' '.substr($divisions->tournamentEdition->startDate, 0, 4).','.$divisions->tournamentEdition->startDate.','.$player->wpprPlace.','.$player->name.','.$player->countryName.','.$player->ifpa_id."\n";
  }
 ?> 