<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $tournament = (isset($_REQUEST['t']) && preg_match('/^[0-9]+$/', $_REQUEST['t'])) ? $_REQUEST['t'] : 1;
  $qualGroupId = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : null;
  $change = (isset($_REQUEST['c']) && preg_match('/^[01]$/', $_REQUEST['c'])) ? $_REQUEST['c'] : null;
  $prefered = (isset($_REQUEST['pr']) && preg_match('/^[01]$/', $_REQUEST['pr'])) ? $_REQUEST['pr'] : null;
  $prefered = ($prefered == 1) ? true : false;
  $qualMsg =  ($prefered) ? 'prefered flag' : 'qualification group';

  if ($qualGroupId) {
    if ($change == 0 || $change == 1) {
      $player = getCurrentPlayer($dbh, $ulogin);
      if ($player) {
        $qualGroup = getQualGroupById($dbh, $qualGroupId);
        if ($qualGroup) {
          if ($change == 0) {
            if ($qualGroup->removePlayer($dbh, $player, $prefered)) {
              $player->setQualChangeReq($dbh, $qualGroup->tournamenDivision_id);
              echo '{"success": true, "reason": "'.ucfirst($qualMsg).' removed"}';
            } else {
              $errorMsg = 'Could not remove the '.$qualMsg.' from '.$player->name;                          
            }
          } else if ($change == 1) {
            if ($qualGroup->addPlayer($dbh, $player, $prefered)) {
              $player->setQualChangeReq($dbh, $qualGroup->tournamenDivision_id);
              echo '{"success": true, "reason": "'.ucfirst($qualMsg).' added"}';
            } else {
              $errorMsg = 'Could not add the '.$qualMsg.' to the player';                          
            }
          } else {
            $errorMsg = 'Invalid change parameter specified';        
          }
        } else {
          $errorMsg = 'Could not find the '.$qualMsg.' in the database';        
        }
      } else {
        $errorMsg = 'Could not find the player! Are you logged in?';
      }
    } else {
      $errorMsg = 'Required parameter (change) missing';    
    }
  } else {
    $errorMsg = 'Required parameter (qualGroupId) missing';    
  }
  
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }
?>
