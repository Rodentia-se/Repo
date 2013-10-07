<?php
  require_once('../functions/general.php');
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
            $result = (object) array(
              'success' => true,
              'reason' => $game->shortName.' was added to the tournament',
              'game' => $game->getLink().'&nbsp;'.$game->getQR(),
              'id' => $game->machine_id,
              'acro' => $game->shortName,
              'manufacturer' => $game->manufacturer,
              'ipdb' => $game->getIpdbLink(),
              'rules' => $game->getRulesLink(),
              'type' => '
                <select id="'.$game->id.'_type" onchange="adminGameType(this);" previous="'.$game->gameType.'">
                  <option value="0">Type...</option>
                  <option value="modern"'.(($game->gameType == 'modern') ? ' selected' : '').'>Modern</option>
                  <option value="classics"'.(($game->gameType == 'classics') ? ' selected' : '').'>Classics</option>
                </select>
                <span class="error errorSpan toolTip" id="'.$game->id.'_typeSpan"></span>
              ',
              'usage' => '
                <select id="'.$game->machine_id.'_usage" onchange="adminGameUsage(this);" previous="'.$game->tournamentDivision_id.'">
                  <option value="0">Usage...</option>
                  <option value="1"'.(($game->tournamentDivision_id == 1) ? ' selected' : '').'>Main</option>
                  <option value="2"'.(($game->tournamentDivision_id == 2) ? ' selected' : '').'>Classics</option>
                  <option value="3"'.(($game->tournamentDivision_id == 3) ? ' selected' : '').'>Team</option>
                  <option value="13"'.(($game->tournamentDivision_id == 13) ? ' selected' : '').'>Side</option>
                  <option value="14"'.(($game->tournamentDivision_id == 14) ? ' selected' : '').'>Recreational</option>
                </select>
                <span class="error errorSpan toolTip" id="'.$game->machine_id.'_usageSpan"></span>
                <img src="'.__baseHref__.'/images/cancel.png" class="icon" onclick="adminGameDel(this);" alt="Click to remove the game from the tournament" title="Click to remove the game from the tournament" id="'.$game->machine_id.'_delete">
                <span class="error errorSpan toolTip" id="'.$game->machine_id.'_deleteSpan"></span>
              '
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