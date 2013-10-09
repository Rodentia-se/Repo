<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $playerId = (isset($_REQUEST['playerId']) && preg_match('/^[0-9]+$/', $_REQUEST['playerId'])) ? $_REQUEST['playerId'] : null;
  $here = (isset($_REQUEST['here']) && $_REQUEST['here'] == 1) ? true : false;
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'qual' || $_REQUEST['type'] == 'final' || $_REQUEST['type'] == 'vol') ? $_REQUEST['type'] : 'qual';
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($playerId) {
        $player = getPlayerById($dbh, $playerId);
        if ($player) {
          if ($player->setHere($dbh, $here, $type)) {
            echo('{"success": true, "reason": "'.$player->name.' is now set as being '.(($here) ? '' : 'NOT ').'present'.(($type == 'final') ? ' in the finals' : (($type == 'vol') ? ' for voluntary work': '')).'"}');
          } else {
            $errorMsg = 'Could not change the status for '.$player->name.' as being '.(($here) ? '' : 'NOT ').'present'.(($type == 'final') ? ' in the finals' : (($type == 'vol') ? ' for voluntary work': ''));
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
