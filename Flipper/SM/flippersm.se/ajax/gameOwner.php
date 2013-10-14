<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');

  $machineId = (isset($_REQUEST['machineId']) && preg_match('/^[0-9]+$/', $_REQUEST['machineId'])) ? $_REQUEST['machineId'] : null;
  $owner_id = (isset($_REQUEST['owner_id']) && preg_match('/^[0-9]+$/', $_REQUEST['owner_id'])) ? $_REQUEST['owner_id'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($machineId) {
        $game = getMachineById($dbh, $machineId);
        if ($game) {
          if ($owner_id) {
            $owner = getOwnerById($dbh, $owner_id);
            if ($owner) {
              if ($game->setOwner($dbh, $owner)) {
                echo('{"success": true, "reason": "'.$owner->name.' has been set as owner of '.$game->name.'"}');
              } else {
                $errorMsg = 'Could not set '.$owner->sname.' as owner of '.$game->name;
              }
            } else {
              $errorMsg = 'Invalid owner ID '.$owner_id.' specified';
            }
          } else {
            if ($game->removeOwner($dbh)) {
              echo('{"success": true, "reason": "'.$game->name.' owner information has been cleared"}');
            } else {
              $errorMsg = 'Could not clear the owner information of '.$game->name;
            }
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
