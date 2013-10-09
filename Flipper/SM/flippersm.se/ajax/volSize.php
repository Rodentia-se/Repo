<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');

  $sizeId = (isset($_REQUEST['size']) && preg_match('/^[0-9]+$/', $_REQUEST['size'])) ? $_REQUEST['size'] : null;
  $playerId = (isset($_REQUEST['playerId']) && preg_match('/^[0-9]+$/', $_REQUEST['playerId'])) ? $_REQUEST['playerId'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($playerId) {
      if ($currentPlayer->adminLevel == 1) {
        if ($sizeId) {
        $size = getSizeById($dbh, $sizeId);
          if ($size) {
            if ($playerId) {
              $player = getPlayerById($dbh, $playerId);
              if ($player) {
                if ($player->setShirtSize($dbh, $size->id)) {
                  echo('{"success": true, "reason": "'.$player->name.' now has T-shirt size '.$size->name.'"}');
                } else {
                  $errorMsg = 'Could not set '.$player->name.' T-shirt size to '.$size->name;
                }
              } else {
                $errorMsg = 'Could not find the player with ID '.$playerId;
              }
            } else {
              $errorMsg = 'No or invalid player ID specified';
            }
          } else {
            $errorMsg = 'Could not find the T-shirt size with ID '.$sizeId;
          }
        } else {
          $errorMsg = 'No T-shirt size specified';
        }
      } else {
        $errorMsg = 'Admin mode used, but you are not admin. Are you correctly logged in?';
      }
    } else {
      if ($sizeId) {
      $size = getSizeById($dbh, $sizeId);
        if ($size) {
          if ($currentPlayer->setShirtSize($dbh, $size->id)) {
            echo('{"success": true, "reason": "'.$currentPlayer->name.' now has T-shirt size '.$size->name.'"}');
          } else {
            $errorMsg = 'Could not set '.$currentPlayer->name.' T-shirt size to '.$size->name;
          }
        } else {
          $errorMsg = 'Could not find the T-shirt size with ID '.$sizeId;
        }
      } else {
        $errorMsg = 'No T-shirt size specified';
      }
    }
  } else {
    $errorMsg = 'Could not find you! Are you logged in?';
  }

  if ($errorMsg) {
    echo(getError($errorMsg, false));
  }

?>
