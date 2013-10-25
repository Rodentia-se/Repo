<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');

  $playerId = (isset($_REQUEST['value']) && preg_match('/^_[0-9]+$/', $_REQUEST['value'])) ? preg_replace('/^_/', '', $_REQUEST['value']) : null;
  $scoreId = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($scoreId) {
        $qualScore = getScoreById($dbh, $scoreId);
        if ($qualScore) {
          if ($playerId == 0 || $playerId) {
            $player = ($qualScore->tournamentDivision_id == 3) ? getTeamById($dbh, $playerId) : getPlayerById($dbh, $playerId);
            if ($playerId == 0 || $player) {
              if ($qualScore->setPlayer($dbh, $player)) {
                if ($player) {
                  echo('{"success": true, "reason": "'.$player->name.' has been assigned to score ID '.$qualScore->id.'", "value": "'.$player->name.'"}');
                } else {
                  echo('{"success": true, "reason": "Score ID '.$qualScore->id.' '.(($qualScore->tournamentDivision_id == 3) ? 'team' : 'player').' assignment was cleared"}');
                }
              } else {
                $errorMsg = ($player) ? 'Could not change '.(($qualScore->tournamentDivision_id == 3) ? 'team' : 'player').' to '.$player->name.' for score ID '.$qualScore->id : 'Could not remove '.(($qualScore->tournamentDivision_id == 3) ? 'team' : 'player').' from score ID '.$qualScore->id;
              }
            } else {
              $errorMsg = 'Could not find any '.(($qualScore->tournamentDivision_id == 3) ? 'team' : 'player').' with ID '.$playerId;
            }
          } else {
            $errorMsg = 'No or invalid '.(($qualScore->tournamentDivision_id == 3) ? 'team' : 'player').' ID specified';
          }
        } else {
          $errorMsg = 'Could not find the qualification score with ID '.$scoreId;
        }
      } else {
        $errorMsg = 'No or invalid qualification score ID specified';
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

