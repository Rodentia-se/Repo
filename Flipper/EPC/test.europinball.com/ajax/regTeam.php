<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');

  $teamId = (isset($_REQUEST['teamId']) && preg_match('/^[0-9]+$/', $_REQUEST['teamId'])) ? $_REQUEST['teamId'] : false;
  $admin = (isset($_REQUEST['admin']) && preg_match('/^[01]$/', $_REQUEST['admin'])) ? $_REQUEST['admin'] : false;
  $name = ($_REQUEST['name']) ? $_REQUEST['name'] : false;
  $national = (isset($_REQUEST['national']) && preg_match('/^[01]$/', $_REQUEST['national'])) ? $_REQUEST['national'] : 0;
  $country_id = (isset($_REQUEST['country_id']) && preg_match('/^[0-9]+$/', $_REQUEST['country_id'])) ? $_REQUEST['country_id'] : null;
  $contactPlayer_id = (isset($_REQUEST['contactPlayer_id']) && preg_match('/^[0-9]+$/', $_REQUEST['contactPlayer_id'])) ? $_REQUEST['contactPlayer_id'] : null;
  $registerPerson_id = (isset($_REQUEST['registerPerson_id']) && preg_match('/^[0-9]+$/', $_REQUEST['registerPerson_id'])) ? $_REQUEST['registerPerson_id'] : null;

  $player = getCurrentPlayer($dbh, $ulogin);

  if ($teamId && $teamId > 0) {
    $team = getTeamById($dbh, $teamId);
    $team->name = ($name) ? $name : $team->name;      
    $team->national = ($national) ? $national : $team->national;
    $team->country_id = ($country_id) ? $country_id : $team->country_id;
    $team->contactPlayer_id = ($contactPlayer_id) ? $contactPlayer_id : $team->contactPlayer_id;
    $team->registerPerson_id = ($registerPerson_id) ? $registerPerson_id : $team->registerPerson_id;
  }
  
  if (!$team) {
    $team = new team($_REQUEST);
    $team->id = ($team->id) ? $team->id : 0;
  }

  if ($player) {
    if ($team) {
      if ($team->id == 0) {
        if ($admin) {
          if ($player->adminLevel == 1) {
            echo '{"success": true, "reason": "'.addTeam($dbh, $team).'"}';
          } else {
            $errorMsg = 'Admin mode used, but you are not admin. Are you logged in?';
          }
        } else {
          echo '{"success": true, "reason": "'.addTeam($dbh, $team, $player).'"}';
        }
      } else {
        if ($team->id > 0) {
          if ($admin) {
            if ($player->adminLevel == 1) {
              echo '{"success": true, "reason": "'.editTeam($dbh, $team).'"}';
            } else {
              $errorMsg = 'Admin mode used, but you are not admin. Are you correctly logged in?';
            }
          } else {
            $playerTeam = $player->getTeam($dbh);
            if ($playerTeam) {
              if ($playerTeam->id == $team->id) {
                echo '{"success": true, "reason": "'.editTeam($dbh, $team).'"}';
              } else {
                $errorMsg = 'You are not a member of that team! Are you correctly logged in?';
              }
            } else {
              $errorMsg = 'You are not a member of a team! Are you correctly logged in?';
            }
          }
        } else {
          $errorMsg = 'Invalid team ID! Did you provide the correct parameters?';
        }
      }
    } else {
      $errorMsg = 'Could not create the team. Did you provide the correct parameters?';
    }
  } else {
    $errorMsg = 'Could not find you! Are you logged in?';
  }

  if ($errorMsg) {
    echo getError($errorMsg, false);
  }

?>
