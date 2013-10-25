<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $teamId = (isset($_REQUEST['teamId']) && preg_match('/^[0-9]+$/', $_REQUEST['teamId'])) ? $_REQUEST['teamId'] : false;
  $admin = (isset($_REQUEST['admin']) && preg_match('/^[01]$/', $_REQUEST['admin'])) ? $_REQUEST['admin'] : false;
  
  $player = getCurrentPlayer($dbh, $ulogin);
  if ($player) {
    if ($admin) {
      if ($player->adminLevel == 1) {
        if ($teamId && $teamId > 0) {
          $team = getTeamById($dbh, $teamId);
          if ($team) {
            if ($team->delete($dbh)) {
              $response = (object) array('success' => true, 'reason' => 'Team deleted!');
              echo(json_encode($response));
            } else {
              $errorMsg = 'Could not delete '.$team->name;
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
      $team = $player->getTeam($dbh);
      if ($team) {
        if ($team->delete($dbh)) {
          $response = (object) array('success' => true, 'reason' => 'Team deleted!');
          echo(json_encode($response));
        } else {
          $errorMsg = 'Could not delete '.$team->name;
        }
      } else {
        $errorMsg = 'Could not find the team! Are you in a team?';
      }
    }
  } else {
    $errorMsg = 'Could not find you! Are you logged in?';
  }
  
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }
?>