<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');

  $machineId = (isset($_REQUEST['machineId']) && preg_match('/^[0-9]+$/', $_REQUEST['machineId'])) ? $_REQUEST['machineId'] : null;
  $ownerId = (isset($_REQUEST['ownerId']) && preg_match('/^[0-9]+$/', $_REQUEST['ownerId'])) ? $_REQUEST['ownerId'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($machineId) {
        $game = getMachineById($dbh, $machineId);
        if ($game) {
          if ($ownerId) {
            $owner = getOwnerById($dbh, $ownerId);
            if ($owner) {
              if ($game->setOwner($dbh, $owner)) {
                $game->owner_id = $owner->id;
                $game->owner = $owner->name;
                $game->ownerShortName = $owner->shortName;
                $result = (object) array(
                  'success' => true,
                  'reason' => $owner->name.' has been set as owner of '.$game->name,
                  'id' => $game->machine_id,
                  'owner' => $game->getAdminInfo($dbh, 'owner')
                );
                echo(json_encode($result));
              } else {
                $errorMsg = 'Could not set '.$owner->sname.' as owner of '.$game->name;
              }
            } else {
              $errorMsg = 'Invalid owner ID '.$ownerId.' specified';
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
