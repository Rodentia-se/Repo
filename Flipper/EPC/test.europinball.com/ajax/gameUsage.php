<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $machineId = (isset($_REQUEST['machineId']) && preg_match('/^[0-9]+$/', $_REQUEST['machineId'])) ? $_REQUEST['machineId'] : null;
  $divisionId = (isset($_REQUEST['divisionId']) && preg_match('/^[0-9]+$/', $_REQUEST['divisionId'])) ? $_REQUEST['divisionId'] : null;
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($machineId) {
        $game = getMachineById($dbh, $machineId);
        if ($game) {
          if ($divisionId) {
            if ($game->setGameUsage($dbh, $divisionId)) {
              echo('{"success": true, "reason": "'.$game->shortName.' is now to be used for division ID '.$divisionId.'"}');
            } else {
              $errorMsg = 'Could not change the usage of '.$game->shortName.' to division ID '.$divisionId;
            }
          } else {
            $errorMsg = 'No or invalid game usage specified';
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
