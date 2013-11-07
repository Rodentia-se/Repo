<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $teamId = (isset($_REQUEST['teamId']) && preg_match('/^[0-9]+$/', $_REQUEST['teamId'])) ? $_REQUEST['teamId'] : null;
  $here = (isset($_REQUEST['here']) && $_REQUEST['here'] == 1) ? true : false;
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'qual' || $_REQUEST['type'] == 'final')) ? $_REQUEST['type'] : 'qual';
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($teamId) {
        $team = getTeamById($dbh, $teamId);
        if ($team) {
          if ($team->setHere($dbh, $here, $type)) {
            echo('{"success": true, "reason": "'.$team->name.' is now set as being '.(($here) ? '' : 'NOT ').'present'.(($type == 'final') ? ' in the finals' : '').'"}');
          } else {
            $errorMsg = 'Could not change the status for '.$team->name.' as being '.(($here) ? '' : 'NOT ').'present'.(($type == 'final') ? ' in the finals' : '');
          }
        } else {
          $errorMsg = 'Could not find the team with ID '.$teamId;
        }
      } else {
        $errorMsg = 'No or invalid team ID specified';
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
