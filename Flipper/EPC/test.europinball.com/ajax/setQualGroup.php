<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $playerId = (isset($_REQUEST['playerId']) && preg_match('/^[0-9]+$/', $_REQUEST['playerId'])) ? $_REQUEST['playerId'] : null;
  $qualGroupId = (isset($_REQUEST['qualGroup']) && preg_match('/^[0-9]+$/', $_REQUEST['qualGroup'])) ? $_REQUEST['qualGroup'] : null;
  $divisionId = (isset($_REQUEST['divisionId']) && preg_match('/^[0-9]+$/', $_REQUEST['divisionId'])) ? $_REQUEST['divisionId'] : null;
  
  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($playerId) {
        $player = getPlayerById($dbh, $playerId);
        if ($player) {
          if ($qualGroupId) {
            $qualGroup = getQualGroupById($dbh, $qualGroupId);
            if ($qualGroup) {
              if ($player->assignQualGroup($dbh, $qualGroup)) {
                echo('{"success": true, "reason": "'.$player->name.' is now assigned qualification group '.$qualGroup->shortName.'"}');
              } else {
                $errorMsg = 'Could not change qualification group for '.$player->name.' to '.$qualGroup->shortName;
              }
            } else {
              $errorMsg = 'Could not find the qualification group with ID '.$qualGroupId;
            }
          } else {
            $errorMsg = 'No or invalid qualification group ID specified';
          }
        } else {
          $errorMsg = 'Could not find the player with ID '.$playerId;
        }
      } else {
        $errorMsg = 'No or invalid player ID specified';
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
