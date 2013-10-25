<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');

  $playerId = (isset($_REQUEST['value']) && preg_match('/^_[0-9]+$/', $_REQUEST['value'])) ? preg_replace('/^_/', '', $_REQUEST['value']) : null;
  $entryId = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($playerId == 0 || $playerId) {
        $player = getPlayerById($dbh, $playerId);
        if ($playerId == 0 || $player) {
          if ($entryId) {
            $qualEntry = getEntryById($dbh, $entryId);
            if ($qualEntry) {
              if ($qualEntry->setPlayer($dbh, $player)) {
                if ($player) {
                  echo('{"success": true, "reason": "'.$player->name.' has been assigned to entry ID '.$qualEntry->id.'", "value": "'.$player->name.'"}');
                } else {
                  echo('{"success": true, "reason": "Score ID '.$qualEntry->id.' player assignment was cleared", "value": null}');
                }
              } else {
                $errorMsg = ($player) ? 'Could not change player to '.$player->name.' for entry ID '.$qualEntry->id : 'Could not remove player from entry ID '.$qualScore->id;
              }
            } else {
              $errorMsg = 'Could not find the qualification entry with ID '.$entryId;
            }
          } else {
            $errorMsg = 'No or invalid qualification entry ID specified';
          }
        } else {
          $errorMsg = 'Could not find any player with ID '.$playerId;
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

