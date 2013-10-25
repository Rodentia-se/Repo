<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $playerTshirtId = (isset($_REQUEST['playerTshirtId']) && preg_match('/^[0-9]+$/', $_REQUEST['playerTshirtId'])) ? $_REQUEST['playerTshirtId'] : false;
  $dlvr = (isset($_REQUEST['dlvr'])) ? (($_REQUEST['dlvr'] == 'true') ? true : false) : false;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($playerTshirtId) {
        $tshirt = getTshirtOrderById($dbh, $playerTshirtId);
        if ($tshirt) {
          if ($tshirt->setDelivered($dbh, $dlvr)) {
            echo('{"success": true, "reason": "T-shirt order ID '.$playerTshirtId.' is now set as'.(($dlvr) ? '' : ' NOT').' delivered"}');
          } else {
            $errorMsg = 'Could not set T-shirt order ID '.$playerTshirtId.' as delivered';
          }
        } else {
          $errorMsg = 'Could not find T-shirt order ID '.$playerTshirtId;
        }
      } else {
        $errorMsg = 'No or invalid T-shirt order ID specified';
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
