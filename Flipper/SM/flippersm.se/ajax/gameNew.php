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
              'game' => $game->getLink().'&nbsp'.$game->getQR(),
              'id' => $game->machine_id,
              'acro' => $game->shortName,
              'manufacturer' => $game->manufacturer,
              'ipdb' => $game->getIpdbLink(),
              'rules' => $game->getRulesLink()
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
        gameSel.parentNode.innerHTML = data.link;
        gameSel.id = data.id + '_game';
        typeSel.id = data.id + '_type';
        usageSel.id = data.id + '_usage';
        document.getElementById(icon.id.replace('gameAdd', 'acroTd')).parentNode.innerHTML = data.acro;
        document.getElementById(icon.id.replace('gameAdd', 'manufacturerTd')).parentNode.innerHTML = data.manufacturer;
        document.getElementById(icon.id.replace('gameAdd', 'ipdbTd')).parentNode.innerHTML = data.ipdb;
        document.getElementById(icon.id.replace('gameAdd', 'rulesTd')).parentNode.innerHTML = data.rules;
