<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $playerId = (isset($_REQUEST['playerId']) && preg_match('/^[0-9]+$/', $_REQUEST['playerId'])) ? $_REQUEST['playerId'] : null;
  $admin = (isset($_REQUEST['admin']) && preg_match('/^[01]$/', $_REQUEST['admin'])) ? $_REQUEST['admin'] : false;
  $tournament = (isset($_REQUEST['t']) && preg_match('/^[0-9]+$/', $_REQUEST['t'])) ? $_REQUEST['t'] : 1;
  $cell = (isset($_REQUEST['cell'])) ? $_REQUEST['cell'] : null;

  if ($cell) {
    if ($admin) {
      $currentPlayer = getCurrentPlayer($dbh, $ulogin);
      if ($currentPlayer) {
        if ($currentPlayer->adminLevel == 1) {
          if ($playerId) {
            $player = getPlayerById($dbh, $playerId);
            if (!$player) {
              $errorMsg = 'Could not find the player ID '.$playerId.'! Does it exist?';          
            }
          } else {
            $errorMsg = 'Admin mode and no player ID specified! How did you do that?';
          }
        } else {
          $errorMsg = 'Admin mode used, but you are not admin. Are you currectly logged in?';
        }
      } else {
        $errorMsg = 'Could not find you! Are you logged in?';
      }
    } else {
      $player = getCurrentPlayer($dbh, $ulogin);;
    }
    
    if ($player) {
      $player->mobileNumber = $cell;
      if ($player->setPhone($dbh, $cell, true)) {
        echo '{"success": true, "reason": "Phone number '.$cell.' added to '.$player->name.'"}';
      } else {
        $errorMsg = 'Could not add the cell phone number into the database';        
      }
    } else {
      $errorMsg = 'Could not find the player! Does it exist?';
    }
  } else {
    $errorMsg = 'Required parameter (cell phone number) missing';
  }
  
  if ($errorMsg) {
    echo(getError($errorMsg, false));
  }

?>
