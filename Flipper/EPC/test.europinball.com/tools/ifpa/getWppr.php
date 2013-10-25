<?php
  require_once('../../functions/general.php');
  header('Content-Type: text/plain; charset=utf-8');
  
  $division = (isset($_REQUEST['division'])) ? $_REQUEST['division'] : 1;
  
  $prefix = ($division == 2) ? 'cl' : 'm';
  $players = getPlayers($dbh, ' where m.tournamentEdition_id = 1', 'order by ifnull('.$prefix.'.wpprPlace, '.$prefix.'.place) asc');
  
  echo "Tournament Name,Date,Finishing Position,Player,Country,IFPA ID\n";
  foreach ($players as $player) {
    $place = ($division == 2) ? $player->classicsWpprPlace : $player->wpprPlace;
    if ($place) {
      echo 'European Pinball Championships '.(($division == 2) ? 'Classics ' : '').'2013,2013-09-13,'.$place.','.$player->name.','.$player->country.','.$player->ifpa_id."\n";
    }
  }
 ?>