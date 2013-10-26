<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');

  $place = (isset($_REQUEST['value']) && preg_match('/^[0-9 \.,]+$/', $_REQUEST['value'])) ? preg_replace('/[^0-9]/', '', $_REQUEST['value']) : null;
  $entryId = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : null;
  $entryId = (isset($_REQUEST['entryId']) && preg_match('/^[0-9]+$/', $_REQUEST['entryId'])) ? $_REQUEST['entryId'] : $entryId;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($entryId) {
        $qualEntry = getEntryById($dbh, $entryId);
        if ($qualEntry) {
          if ($qualEntry->setPlace($dbh, $place)) {
            if ($place) {
              echo('{"success": true, "reason": "Place set to '.$place.' for entry ID '.$qualEntry->id.'", "value": "'.$place.'"}');
            } else {
              echo('{"success": true, "reason": "Place removed for entry ID '.$qualEntry->id.'", "value": null}');
            }
          } else {
            $errorMsg = 'Could not set place to '.$place.' for entry ID '.$qualEntry->id;
          }
        } else {
          $errorMsg = 'Could not find the qualification entry with ID '.$entryId;
        }
      } else {
        $errorMsg = 'No or invalid qualification entry ID specified';
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