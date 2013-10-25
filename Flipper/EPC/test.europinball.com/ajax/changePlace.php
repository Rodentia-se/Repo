<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $playerId = (isset($_REQUEST['playerId']) && preg_match('/^[0-9]+$/', $_REQUEST['playerId'])) ? $_REQUEST['playerId'] : null;
  $division = (isset($_REQUEST['division']) && preg_match('/^[0-9]+$/', $_REQUEST['division'])) ? $_REQUEST['division'] : 1;
  $place = (isset($_REQUEST['place']) && preg_match('/^[0-9]+$/', $_REQUEST['place'])) ? $_REQUEST['place'] : 0;
  $wppr = (isset($_REQUEST['wppr']) && $_REQUEST['wppr'] == 1) ? true : false;
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($playerId) {
        $player = getPlayerById($dbh, $playerId);
        if ($player) {
          if ($player->setPlace($dbh, $place, $division, $wppr)) {
            echo('{"success": true, "reason": "'.$player->name.' is now set as '.(($wppr) ? 'WPPR ' : '').'placed '.$place.' in division ID '.$division.'"}');
          } else {
            $errorMsg = 'Could not change to '.(($wppr) ? 'WPPR ' : '').'place '.$place.' in division ID '.$division.' for '.$player->name;
          }
        } else {
          $errorMsg = 'Could not find the player with ID '.$playerId;
        }
      } else {
        $errorMsg = 'No or invalid player ID specified';
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
