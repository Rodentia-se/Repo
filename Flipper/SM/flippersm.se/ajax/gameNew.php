<?php
  require_once('../functions/general.php');
  require_once('../functions/admin.php');
  header('Content-Type: application/json');

  $gameId = (isset($_REQUEST['gameId']) && preg_match('/^[0-9]+$/', $_REQUEST['gameId'])) ? $_REQUEST['gameId'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($gameId) {
        $game = getGameById($dbh, $gameId, false);
        if ($game) {
          $game->machine_id = $game->add($dbh);
          if ($game->machine_id) {
            $game = getMachineById($dbh, $game->machine_id);
            $game->id = $game->game_id;
            $result = (object) array(
              'success' => true,
              'reason' => $game->shortName.' was added to the tournament',
              'id' => $game->machine_id,
              'game' => $game->getAdminInfo($dbh, 'game'),
              'acro' => $game->getAdminInfo($dbh, 'shortName'),
              'manufacturer' => $game->getAdminInfo($dbh, 'manufacturer'),
              'owner' => $game->getAdminInfo($dbh, 'owner'),
              'ipdb' => $game->getAdminInfo($dbh, 'ipdb'),
              'rules' => $game->getAdminInfo($dbh, 'rules'),
              'type' => $game->getAdminInfo($dbh, 'type'),
              'usage' => $game->getAdminInfo($dbh, 'usage'),
              'tdIds' => array(
                $game->machine_id.'_gameTd',
                $game->machine_id.'_acroTd',
                $game->machine_id.'_manufacturerTd',
                $game->machine_id.'_ownerTd',
                $game->machine_id.'_ipdbTd',
                $game->machine_id.'_rulesTd',
                $game->machine_id.'_typeTd',
                $game->machine_id.'_usageTd'
              )
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