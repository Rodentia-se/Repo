<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $playerId = (isset($_REQUEST['playerId']) && preg_match('/^[0-9]+$/', $_REQUEST['playerId'])) ? $_REQUEST['playerId'] : false;
  $password = ($_REQUEST['pw']) ? $_REQUEST['pw'] : false;
  $nonce = ($_REQUEST['nonce']) ? $_REQUEST['nonce'] : false;
  $admin = (isset($_REQUEST['admin']) && preg_match('/^[01]$/', $_REQUEST['admin'])) ? $_REQUEST['admin'] : 0;
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  
  if (!$currentPlayer || $currentPlayer->adminLevel == 1) {
    if ($playerId) {
      $player = getPlayerById($dbh, $playerId);
      if ($player) {
        if ($password) {
          if (checkField($dbh, 'password', $password)) {
            if ($nonce) {
              if (ulNonce::Verify('resetNonce', $nonce)) {
                $uid = $ulogin->Uid($player->username);
                if ($uid) {
                  if ($ulogin->SetPassword($uid, $password)) {
                    echo('{"success": true, "reason": "Password was reset'.(($admin) ? ' for '.$player->username.'. You need to reload the page to reset more passwords.' : '. Redirecting...').'"}');
                  } else {
                    $errorMsg = 'Could not reset password';
                  }
                } else {
                  $errorMsg = 'Could not find '.$player->username.' in the database';
                }
              } else {
                $errorMsg = 'The nonce was invalid';
              }
            } else {
              $errorMsg = 'No nonce was specified';
            }
          } else {
            $errorMsg = 'The password was invalid'; 
          }
        } else {
          $errorMsg = 'No password was specified';
        }
      } else {
        $errorMsg = 'Could not find player ID '.$playerId.' in the database';
      }
    } else {
      $errorMsg = 'No player ID was specified';
    }
  } else {
    $errorMsg = 'You are already logged in? Please use the built-in change credentials feature in stead.';
  }
    
  if ($errorMsg) {
    echo(getError($errorMsg, false));    
  }
    
?>