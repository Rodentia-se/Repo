<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $tshirtId = (isset($_REQUEST['tshirtId']) && preg_match('/^[0-9]+$/', $_REQUEST['tshirtId'])) ? $_REQUEST['tshirtId'] : false;
  $action = (isset($_REQUEST['action']) && preg_match('/^-*[1]+$/', $_REQUEST['action'])) ? $_REQUEST['action'] : false;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($tshirtId) {
        $tshirt = getNoOfTshirts($dbh, $tshirtId);
        if ($tshirt && count($tshirt) == 1) {
          if ($action && ($action == 1 || $action == -1)) {
          if ($tshirt->setSold($dbh, $action)) {
            echo('{"success": true, "reason": "Number of sold T-shirt ID '.$tshirtId.' has now been '.(($action == 1) ? 'increased' : 'decreased').'"}');
          } else {
            $errorMsg = 'Could not change number of sold T-shirt  ID '.$tshirtId;
          }
          } else {
          $errorMsg = 'No or invalid sold number specified';
          }
        } else {
          $errorMsg = 'Could not find T-shirt ID '.$tshirtId;
        }
      } else {
        $errorMsg = 'No or invalid T-shirt ID specified';
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
