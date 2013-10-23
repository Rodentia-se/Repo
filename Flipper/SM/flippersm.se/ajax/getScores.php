<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'player' || $_REQUEST['type'] == 'game')) ? $_REQUEST['type'] : null;
  $id = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($type) {
        if ($id) {
          $obj = ($type == 'game') ? getGameById($dbh, $id) : getPlayerById($dbh, $id);
          if ($obj) {
            $scores = $obj->getScores($dbh, 1, false);
            if (is_array($scores) && count($scores > 1)) {
              foreach ($scores as $score) {
                $json[] = array(
                  $score->qualEntry_id,
                  $score->player,
                  $score->person_id,
                  $score->player_id,
                  $score->gameShortName,
                  $score->game_id,
                  $score->machine_id,
                  $score->score,
                  $score->points,
                  $score->place
                );
              }
              echo json_encode($json);
            } else {
              $errorMsg = 'Could not find any scores for '.$obj->name;
            }
          } else {
            $errorMsg = 'Could not find the '.$type.' with ID '.$id;
          }
        } else {
          $errorMsg = 'No or invalid '.$type.' ID specified';
        }
      } else {
        $errorMsg = 'No or invalid type specified';
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

