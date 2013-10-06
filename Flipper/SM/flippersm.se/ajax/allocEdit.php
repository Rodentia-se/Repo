<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $taskId = (isset($_REQUEST['taskId']) && preg_match('/^[0-9]+$/', $_REQUEST['taskId'])) ? $_REQUEST['taskId'] : null;
  $periodId = (isset($_REQUEST['periodId']) && preg_match('/^[0-9]+$/', $_REQUEST['periodId'])) ? $_REQUEST['periodId'] : null;
  $playerId = (isset($_REQUEST['playerId']) && preg_match('/^[0-9]+$/', $_REQUEST['playerId'])) ? $_REQUEST['playerId'] : null;
  $otherPlayerId = (isset($_REQUEST['otherPlayerId']) && preg_match('/^[0-9]+$/', $_REQUEST['otherPlayerId'])) ? $_REQUEST['otherPlayerId'] : null;
  $change = (isset($_REQUEST['c']) && preg_match('/^[01]$/', $_REQUEST['c'])) ? $_REQUEST['c'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($change === null) {
      if ($currentPlayer->adminLevel == 1) {
        if ($taskId) {
          $task = getTaskById($dbh, $taskId);
          if ($task) {
            if ($periodId) {
              $period = getPeriodById($dbh, $periodId);
              if ($period) {
                if ($playerId == 0 || $playerId) {
                  if ($playerId > 0) {
                    $player = getPlayerById($dbh, $playerId);
                    if ($player) {
                      if ($task->assign($dbh, $player, $period)) {
                        $reason = $player->name.' has been assigned';
                      } else {
                        $errorMsg = 'Could not assign '.$player->name.' as '.$task->name.' at '.$period->name;
                      }
                    }
                  }
                  if ($otherPlayerId == 0 || $otherPlayerId) {
                    if ($otherPlayerId > 0) {
                      $otherPlayer = getPlayerById($dbh, $otherPlayerId);
                      if ($otherPlayer) {
                        if ($task->assign($dbh, $otherPlayer, $period, false)) {
                          $reason = (($reason) ? $reason.' and ' : '').$otherPlayer->name.' has been UNassigned';
                        } else {
                          $errorMsg = 'Could not UNassign '.$otherPlayer->name.' as '.$task->name.' at '.$period->name;
                        }
                      }
                    }
                  } else {
                    $errorMsg = 'No or invalid previous player ID specified';
                  }
                  echo('{"success": true, "reason": "'.$reason.' as '.$task->name.' at '.$period->name.'"}');
                } else {
                  $errorMsg = 'No or invalid player ID specified';
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
      if ($taskId) {
        $task = getTaskById($dbh, $taskId);
        if ($task) {
          if ($periodId) {
            $period = getPeriodById($dbh, $periodId);
            if ($period) {
              if ($currentPlayer->mainPlayerId) {
                if ($change == 1) {
                  if ($task->assign($dbh, $currentPlayer, $period)) {
                    echo('{"success": true, "reason": "'.$currentPlayer->name.' has been assigned as '.$task->name.' at '.$period->name.'"}');
                  } else {
                    $errorMsg = 'Could not assign '.$currentPlayer->name.' as '.$task->name.' at '.$period->name;
                  }
                } else if ($change == 0) {
                  if ($task->assign($dbh, $currentPlayer, $period, false)) {
                    echo('{"success": true, "reason": "'.$currentPlayer->name.' has been UNassigned as '.$task->name.' at '.$period->name.'"}');
                  } else {
                    $errorMsg = 'Could not UNassign '.$currentPlayer->name.' as '.$task->name.' at '.$period->name;
                  }
                } else {
                  $errorMsg = 'Invalid change parameter specified';
                }
              } else {
                $errorMsg = 'Could not find you! Are you registered for the tournament?';
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
    }
  } else {
    $errorMsg = 'Could not find you! Are you logged in?';
  }
    
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }

?>
