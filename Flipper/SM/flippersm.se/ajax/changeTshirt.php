<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $tournament = (isset($_REQUEST['t']) && preg_match('/^[0-9]+$/', $_REQUEST['t'])) ? $_REQUEST['t'] : null;
  $id = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : false;
  $color = (isset($_REQUEST['color']) && preg_match('/^[0-9]+$/', $_REQUEST['color'])) ? $_REQUEST['color'] : 3;
  $size = (isset($_REQUEST['size']) && preg_match('/^[0-9]+$/', $_REQUEST['size'])) ? $_REQUEST['size'] : 3;
  $number = (isset($_REQUEST['number']) && preg_match('/^[0-9]+$/', $_REQUEST['number'])) ? $_REQUEST['number'] : 1;

  $player = getCurrentPlayer($dbh, $ulogin);
  if ($player) {
    If ($tournament && $id && $color && $size && $number) {
      $tShirt = getTshirtByParams($dbh, $color, $size, $tournament);
      $tShirt->number = $number;
      if ($tShirt) {
        if ($tShirt->inStock($dbh) >= $number) {
          $tShirt->playerTshirt_id = $id;
          if ($tShirt->updateOrder($dbh) > 0) {
            $response = (object) array('success' => true, 'reason' => $number.' of '.$tShirt->color.' T-shirts size '.$tShirt->size.' ordered!');
            echo(json_encode($response));
          } else {
            $errorMsg = 'Could not update the T-shirt';
          }
        } else {
          $errorMsg = 'There\'s not '.$number.' of '.$tShirt->color.' '.$tShirt->size.' in stock! Please'.(($number > 1) ? ' try less than '.$number.' or' : '').' choose another color/size.';
        }
      } else {
        $errorMsg = 'Could not find the T-shirt';
      }
    } else {
      $errorMsg = 'Could not get the T-shirt parameters';    
    }
  } else {
    $errorMsg = 'Could not find the player! Are you logged in?';
  }
    
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }
    
    
?>