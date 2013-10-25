<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'player' || $_REQUEST['type'] == 'game' || $_REQUEST['type'] == 'team')) ? $_REQUEST['type'] : null;
  $id = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($type) {
        if ($id) {
          $obj = ($type == 'game') ? getGameById($dbh, $id) : (($type == 'team') ? getTeamById($dbh, $id) : getPlayerById($dbh, $id));
          if ($obj) {
            $scores = $obj->getScores($dbh, 1, false);
            if (is_array($scores) && count($scores > 1)) {
              foreach ($scores as $score) {
                $json[] = array(
                  $score->id,
                  $score->qualEntry_id,
                  (($score->tournamentDivision_id == 3) ? 'Team' : (($score->tournamentDivision_id == 2) ? 'Classics' : 'Main')),
                  (($score->tournamentDivision_id == 3) ? $score->team : $score->player),
                  $score->game,
                  $score->score,
                  round($score->points),
                  $score->place
                );
              }
              echo '{"aaData": '.json_encode($json).'}';
            } else {
              $json[] = array('No data', null, null, null, null, null, null, null);
              echo '{"aaData": '.json_encode($json).'}';
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

