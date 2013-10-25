<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $field = ($_REQUEST['f']) ? $_REQUEST['f'] : false;
  $value = ($_REQUEST['v']) ? $_REQUEST['v'] : false;
  
  if ($field) {
    if ($field == 'username') {
      if ($value) {
        $id = getIdFromUser($dbh, $value);
        if ($id) {
          echo('{"success": true, "reason": "Username found - please click submit"}');
        } else {
          echo('{"success": false, "reason": "No such username found"}');
        }
      } else {
        $errorMsg = 'No value specified';
      }
    } else if ($field == 'email') {
      if ($value) {
        $player = getPlayerByEmail($dbh, $value);
        if ($player) {
          echo('{"success": true, "reason": "Email address found"}');
        } else {
          echo('{"success": false, "reason": "No such email address found"}');
        }
      } else {
        $errorMsg = 'No value specified';
      }
    } else {
      $errorMsg = 'Invalid field specified';
    }
  } else {
    $errorMsg = 'No field specified';
  }
    
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }
    
?>