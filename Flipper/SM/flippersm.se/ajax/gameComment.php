<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');

  $machineId = (isset($_REQUEST['$machineId']) && preg_match('/^[0-9]+$/', $_REQUEST['$machineId'])) ? $_REQUEST['$machineId'] : null;
  $comment = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($machineId) {
        $game = getMachines($dbh, ' where id = '.$machineId)[0];
        if ($game) {
          if ($game->setComment($dbh, $comment)) {
            echo('{"success": true, "reason": "'.$game->shortName.' comment updated"}');
          } else {
            $errorMsg = 'Could not update the '.$game->shortName.' comment';
          }
        } else {
          $errorMsg = 'Could not find the game with ID '.$gameId;
        }
      } else {
        $errorMsg = 'No or invalid game ID specified';
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
