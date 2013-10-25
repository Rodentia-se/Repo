<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $playerId = (isset($_REQUEST['playerId']) && preg_match('/^[0-9]+$/', $_REQUEST['playerId'])) ? $_REQUEST['playerId'] : null;
  $adminLevel = (isset($_REQUEST['adminLevel']) && preg_match('/^[0-9]+$/', $_REQUEST['adminLevel'])) ? $_REQUEST['adminLevel'] : null;
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($adminLevel != null) {
        if ($playerId) {
          $player = getPlayerById($dbh, $playerId);
          if ($player) {
            if ($player->setAdmin($dbh, $adminLevel)) {
              echo('{"success": true, "reason": "'.$player->name.' is set to admin level '.$adminLevel.'"}');
            } else {
              $errorMsg = 'Could not change admin level for '.$player->name.' to '.$adminLevel;
            }
          } else {
            $errorMsg = 'Could not find the player with ID '.$playerId;
          }
        } else {
          $errorMsg = 'No or invalid player ID specified';
        }
      } else {
        $errorMsg = 'No admin level provided! How did you do that?';
      }
    } else {
      $errorMsg = 'Admin mode used, but you are not admin. Are you correctly logged in?';
    }
  } else {
    $errorMsg = 'Could not find you! Are you logged in?';
  }
    
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }
    
?>
