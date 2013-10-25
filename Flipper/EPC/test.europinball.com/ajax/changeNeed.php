<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $taskId = (isset($_REQUEST['taskId']) && preg_match('/^[0-9]+$/', $_REQUEST['taskId'])) ? $_REQUEST['taskId'] : null;
  $periodId = (isset($_REQUEST['periodId']) && preg_match('/^[0-9]+$/', $_REQUEST['periodId'])) ? $_REQUEST['periodId'] : null;
  $need = (isset($_REQUEST['need']) && preg_match('/^[0-9]+$/', $_REQUEST['need'])) ? $_REQUEST['need'] : null;
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($taskId) {
        $task = getTaskById($dbh, $taskId);
        if ($task) {
          if ($periodId) {
            $period = getPeriodById($dbh, $periodId);
            if ($period) {
              if (isset($need)) {
                if ($task->setNeed($dbh, $period, $need)) {
                  echo('{"success": true, "reason": "'.$task->name.' needs for '.$period->name.' is now set to '.$need.'"}');
                } else {
                  $errorMsg = 'Could not change the need for '.$task->name.' at '.$period->name.' to '.$need;
                }
              } else {
                $errorMsg = 'No or invalid need specified';
              }
            } else {
              $errorMsg = 'Could not find the period with ID '.$periodId;
            }
          } else {
            $errorMsg = 'No or invalid period ID specified';
          }
        } else {
          $errorMsg = 'Could not find the task with ID '.$taskId;
        }
      } else {
        $errorMsg = 'No or invalid task ID specified';
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
