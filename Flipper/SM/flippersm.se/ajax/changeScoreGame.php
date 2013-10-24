<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $gameId = (isset($_REQUEST['value']) && preg_match('/^_[0-9]+$/', $_REQUEST['value'])) ? preg_replace('/^_/', '', $_REQUEST['value']) : null;
  $scoreId = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($gameId == 0 || $gameId) {
        $game = getGameById($dbh, $gameId);
        if ($gameId == 0 || $game) {
          if ($scoreId) {
            $qualScore = getScoreById($dbh, $scoreId);
            if ($qualScore) {
              if ($qualScore->setGame($dbh, $game)) {
                if ($game) {
                  echo('{"success": true, "reason": "'.$game->shortName.' has been assigned to score ID '.$qualScore->id.'"}');
                } else {
                  echo('{"success": true, "reason": "Score ID '.$qualScore->id.' game assignment was cleared"}');
                }
              } else {
                $errorMsg = ($game) ? 'Could not change game to '.$game->shortName.' for score ID '.$qualScore->id : 'Could not remove game from score ID '.$qualScore->id;
              }
            } else {
              $errorMsg = 'Could not find the qualification score with ID '.$scoreId;
            }
          } else {
            $errorMsg = 'No or invalid qualification score ID specified';
          }
        } else {
          $errorMsg = 'Could not find any game with ID '.$gameId;
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

