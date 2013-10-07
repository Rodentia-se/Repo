<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');

  $gameId = (isset($_REQUEST['gameId']) && preg_match('/^[0-9]+$/', $_REQUEST['gameId'])) ? $_REQUEST['gameId'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($gameId) {
        $game = getGameById($dbh, $gameId);
        if ($game) {
          $game->machine_id = $game->add($dbh);
          if ($game->machine_id) {
            $result = (object) array(
              'success' => true,
              'reason' => $game->shortName.' was added to the tournament',
              'link' => $game->getLink().'&nbsp;<a href="'.__baseHref__.'/mobile/gamePrinter.php?gameId='.$game->machine_id.'&autoPrint=true" target="_blank"><img src="'.__baseHref__.'/images/qr.png" alt="QR" title="Click for QR code"></a>'
            );
            echo(json_encode($result));
          } else {
            $errorMsg = 'Could not add '.$game->shortName.' to the tournament';
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
