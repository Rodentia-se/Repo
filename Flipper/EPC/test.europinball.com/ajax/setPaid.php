<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $playerId = (isset($_REQUEST['playerId']) && preg_match('/^[0-9]+$/', $_REQUEST['playerId'])) ? $_REQUEST['playerId'] : null;
  $paid = (isset($_REQUEST['paid']) && preg_match('/^[0-9]+$/', $_REQUEST['paid'])) ? $_REQUEST['paid'] : null;
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($paid != null) {
        if ($playerId) {
          $player = getPlayerById($dbh, $playerId);
          if ($player) {
            if ($player->setPaid($dbh, $paid)) {
              echo('{"success": true, "reason": "'.$player->name.' has paid a total of '.$paid.'"}');
            } else {
              $errorMsg = 'Could not change the paid amount for '.$player->name.' to '.$paid;
            }
          } else {
            $errorMsg = 'Could not find the player with ID '.$playerId;
          }
        } else {
          $errorMsg = 'No or invalid player ID specified';
        }
      } else {
        $errorMsg = 'No paid sum provided! How did you do that?';
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
