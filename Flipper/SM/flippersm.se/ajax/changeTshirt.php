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
        $tShirt->playerTshirt_id = $id;
        $available = $tShirt->inStock($dbh);
        if ($available == 0) {
          $errorMsg = 'There\'s no '.$tShirt->color.' '.$tShirt->size.' in stock! Please choose another color/size.';
        } else {
          if ($available < $tShirt->number) {
            $tShirt->number = $available;
            $reason = 'There was only '.$tShirt->number.' of '.$tShirt->color.' '.$tShirt->size.' in stock! ';
            if ($tShirt->number == $tShirt->getNumber($dbh)) {
              $errorMsg = 'There\'s only '.$tShirt->getNumber($dbh).' '.$tShirt->color.' '.$tShirt->size.' in stock!';
            }
          }
          if (!$errorMsg) {
            if ($tShirt->updateOrder($dbh) > 0) {
              $response = (object) array('success' => true, 'reason' => $reason.$tShirt->number.' of '.$tShirt->color.' '.$tShirt->size.' ordered!', 'number' => $tShirt->number);
              echo(json_encode($response));
            } else {
              $errorMsg = 'Could not update the T-shirt';
            }
          }
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