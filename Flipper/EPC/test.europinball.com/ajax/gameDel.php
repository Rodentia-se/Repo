<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $machineId = (isset($_REQUEST['machineId']) && preg_match('/^[0-9]+$/', $_REQUEST['machineId'])) ? $_REQUEST['machineId'] : null;
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($machineId) {
        $game = getMachineById($dbh, $machineId);
        if ($game) {
          if ($game->remove($dbh)) {
            echo('{"success": true, "reason": "'.$game->shortName.' is now removed from the tournament"}');
          } else {
            $errorMsg = 'Could not remove '.$game->shortName.' from the tournament';
          }
        } else {
          $errorMsg = 'Could not find the machine with ID '.$machineId;
        }
      } else {
        $errorMsg = 'No or invalid machine ID specified';
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
