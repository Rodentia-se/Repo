<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $tournament = (isset($_REQUEST['t']) && preg_match('/^[0-9]+$/', $_REQUEST['t'])) ? $_REQUEST['t'] : 1;
  $taskId = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : null;
  $change = (isset($_REQUEST['c']) && preg_match('/^[01]$/', $_REQUEST['c'])) ? $_REQUEST['c'] : null;

  if ($taskId) {
    if ($change == 0 || $change == 1) {
      $player = getCurrentPlayer($dbh, $ulogin);
      if ($player) {
        $task = getTaskById($dbh, $taskId);
        if ($task) {
          if ($change == 0) {
            if ($player->volunteer) {
              if ($task->removePlayer($dbh, $player, $tournament)) {
                echo '{"success": true, "reason": "Task removed"}';
              } else {
                $errorMsg = 'Could not remove the task from the player';                          
              }
            } else {
              $errorMsg = 'Could not find the volunteer in the database. Are you registered as a volunteer?'; 
            }
          } else if ($change == 1) {
            if (!$player->volunteer) {
              $player->addVolunteer($dbh, $tournament);
            }
            $volunteer = $player->getVolunteer($dbh);
            if ($volunteer) {
              if ($task->addPlayer($dbh, $volunteer, $tournament)) {
                echo '{"success": true, "reason": "Task added"}';
              } else {
                $errorMsg = 'Could not add the task to the player';                          
              }
            } else {
              $errorMsg = 'Could not find the volunteer in the database. Are you registered as a volunteer?'; 
            }
          } else {
            $errorMsg = 'The change parameter is invalid!';
          }
        } else {
          $errorMsg = 'Could not find the task in the database';
        }
      } else {
        $errorMsg = 'Could not find the player! Are you logged in?';
      }
    } else {
      $errorMsg = 'Required parameter (change) missing';    
    }
  } else {
    $errorMsg = 'Required parameter (taskId) missing';    
  }
  
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }
    
?>
