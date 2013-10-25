<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $id = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : null;
  
  $player = getCurrentPlayer($dbh, $ulogin);
  if ($player) {
    if ($id) {
      $tShirt = new tshirt(array('playerTshirt_id' => $id));
      if ($tShirt->deleteOrder($dbh)) {
        echo('{"success": true, "reason": "T-shirt removed"}');
      } else {
        $errorMsg = 'Could not delete the T-shirt';
      }
    } else {
      $errorMsg = 'Could not find the T-shirt';
    }
  } else {
    $errorMsg = 'Could not find the player! Are you logged in?';
  }
  
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }
    
    
?>