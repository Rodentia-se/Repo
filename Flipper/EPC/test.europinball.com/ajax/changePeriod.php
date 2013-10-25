<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $tournament = (isset($_REQUEST['t']) && preg_match('/^[0-9]+$/', $_REQUEST['t'])) ? $_REQUEST['t'] : 1;
  $periodId = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : null;
  $change = (isset($_REQUEST['c']) && preg_match('/^[01]$/', $_REQUEST['c'])) ? $_REQUEST['c'] : null;

  if ($periodId) {
    if ($change == 0 || $change == 1) {
      $player = getCurrentPlayer($dbh, $ulogin);
      if ($player) {
        $period = getPeriodById($dbh, $periodId);
        if ($period) {
          if ($change == 0) {
            if ($player->volunteer) {
              if ($period->removePlayer($dbh, $player, $tournament)) {
                echo '{"success": true, "reason": "Period removed"}';
              } else {
                $errorMsg = 'Could not remove the period from the player';                          
              }
            } else {
              $errorMsg = 'Could not find the volunteer in the database'; 
            }
          } else if ($change == 1) {
            if (!$player->volunteer) {
              $player->addVolunteer($dbh, $tournament);
            }
            $volunteer = $player->getVolunteer($dbh);
            if ($volunteer) {
              if ($period->addPlayer($dbh, $volunteer, $tournament)) {
                echo '{"success": true, "reason": "Period added"}';
              } else {
                $errorMsg = 'Could not add the period to the player';                          
              }
            } else {
              $errorMsg = 'Could not find or add the volunteer in the database';            
            }
          }
        } else {
          $errorMsg = 'Could not find the period in the database';        
        }
      } else {
        $errorMsg = 'Could not find the player! Are you logged in?';
      }
    } else {
      $errorMsg = 'Required parameter (change) missing';    
    }
  } else {
    $errorMsg = 'Required parameter (periodId) missing';    
  }
  
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }
?>
