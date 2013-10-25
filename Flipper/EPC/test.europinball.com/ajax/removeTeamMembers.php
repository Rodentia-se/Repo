<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $playerId = (isset($_REQUEST['playerId']) && preg_match('/^[0-9]+$/', $_REQUEST['playerId'])) ? $_REQUEST['playerId'] : null;
  $teamId = (isset($_REQUEST['teamId']) && preg_match('/^[0-9]+$/', $_REQUEST['teamId'])) ? $_REQUEST['teamId'] : false;
  $admin = (isset($_REQUEST['admin']) && preg_match('/^[01]$/', $_REQUEST['admin'])) ? $_REQUEST['admin'] : false;
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($admin) {
      if ($currentPlayer->adminLevel == 1) {
        if ($teamId && $teamId > 0) {
          $team = getTeamById($dbh, $teamId);
          if ($team) {
            if ($playerId) {
              $player = getPlayerById($dbh, $playerId);
              if ($player) {
                if ($team->removePlayer($dbh, $player) > 0) {
                  echo('{"success": true, "reason": "'.$player->name.' removed from '.$team->name.'"}');
                } else {
                  $errorMsg = 'Could not remove player ID '.$player->id.' from '.$team->name;
                }
              } else {
                $errorMsg = 'Could not find the player with ID '.$playerId;
              }
            } else {
              if ($team->removePlayers($dbh)) {
                echo('{"success": true, "reason": "All members removed from '.$team->name.'"}');
              } else {
                $errorMsg = 'Could not remove all players from '.$team->name;
              }
            }
          } else {
            $errorMsg = 'Could not find the team ID '.$teamId.'! Does it exist?';          
          }
        } else {
          $errorMsg = 'Admin mode and no team ID specified! How did you do that?';
        }
      } else {
        $errorMsg = 'Admin mode used, but you are not admin. Are you currectly logged in?';
      }
    } else {
      $team = $currentPlayer->getTeam($dbh);
      if ($team) {
        if ($playerId) {
          $player = getPlayerById($dbh, $playerId);
          if ($player) {
            if ($team->removePlayer($dbh, $player) > 0) {
              echo('{"success": true, "reason": "'.$player->name.' removed from '.$team->name.'"}');
            } else {
              $errorMsg = 'Could not remove player ID '.$player->id.' from '.$team->name;
            }
          } else {
            $errorMsg = 'Could not find the player with ID '.$playerId;
          }
        } else {
          if ($team->removePlayers($dbh)) {
            echo('{"success": true, "reason": "All members removed from '.$team->name.'"}');
          } else {
            $errorMsg = 'Could not remove all players from '.$team->name;
          }
        }
      } else {
        $errorMsg = 'Could not find your team! Are you in a team?';
      }
    }
  } else {
    $errorMsg = 'Could not find you! Are you logged in?';
  }
    
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }
    
    
?>
