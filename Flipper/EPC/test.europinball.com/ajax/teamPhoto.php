<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $newPhoto = (isset($_REQUEST['newPhoto'])) ? $_REQUEST['newPhoto'] : null;
  
  $player = getCurrentPlayer($dbh, $ulogin);
  if ($player) {
    $team = $player->getTeam($dbh);
    if ($team) {
      if ($newPhoto) {
        $team->newPhoto = $newPhoto;
        if ($team->setPhoto()) {
          echo('{"success": true, "reason": "Team image updated"}');
        } else {
          $errorMsg = 'Could not add image for team '.$team->id;
        }
      } else {
        $errorMsg = 'No or invalid image specified';        
      }
    } else {
      $errorMsg = 'Could not find your team! Are you in a team?';
    }
  } else {
    $errorMsg = 'Could not find the player! Are you logged in?';
  }
    
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }
  
?>