<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');

  $machineId = (isset($_REQUEST['machineId']) && preg_match('/^[0-9]+$/', $_REQUEST['machineId'])) ? $_REQUEST['machineId'] : null;
  $onePlayerAllowed = (isset($_REQUEST['onePlayerAllowed']) && preg_match('/^[01]$/', $_REQUEST['onePlayerAllowed'])) ? $_REQUEST['onePlayerAllowed'] : false;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($machineId) {
        $game = getMachineById($dbh, $machineId);
        if ($game) {
          if ($game->setOnePlayerAllowed($dbh, $onePlayerAllowed)) {
            echo('{"success": true, "reason": "'.$game->shortName.' has been set to '.(($onePlayerAllowed) ? 'allow' : 'NOT allow').' one player play"}');
          } else {
            $errorMsg = 'Could not set '.$game->shortName.' to '.(($onePlayerAllowed) ? 'allow' : 'NOT allow').' one player play';
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
