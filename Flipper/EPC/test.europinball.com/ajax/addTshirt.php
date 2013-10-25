<?php
  require_once('../functions/general.php');
  header('Content-Type: text/html');
  
  $tournament = (isset($_REQUEST['t']) && preg_match('/^[0-9]+$/', $_REQUEST['t'])) ? $_REQUEST['t'] : 1;
  
  $player = getCurrentPlayer($dbh, $ulogin);
  if ($player) {
    $newTshirt = $player->addTshirt($dbh);
    if ($newTshirt && count($newTshirt > 0)) {
      echo(json_encode(getTshirtRow($dbh, $tournament, $newTshirt, null, true, true)));
    } else {
      $errorMsg = 'Could not add the T-shirt in the database';
    }
  } else {
    $errorMsg = 'Could not find the player! Are you logged in?';
  }
  
  if ($errorMsg) {
    echo(json_decode(getError($errorMsg))->reason);
  }
?>
