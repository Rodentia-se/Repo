<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $gameId = (isset($_REQUEST['gameId']) && preg_match('/^[0-9]+$/', $_REQUEST['gameId'])) ? $_REQUEST['gameId'] : null;
  $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : false;
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($gameId) {
        $game = getGameById($dbh, $gameId);
        if ($game) {
          if ($type) {
            if ($game->setGameType($dbh, strtolower($type))) {
              echo('{"success": true, "reason": "'.$game->shortName.' is now set as type '.$type.'"}');
            } else {
              $errorMsg = 'Could not change the type of '.$game->shortName.' to '.$type;
            }
          } else {
            $errorMsg = 'No or invalid game type specified';
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
