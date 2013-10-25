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
            $qualScores = $obj->getScores($dbh, 1, false);
            if (is_array($qualScores) && count($qualScores > 1)) {
              foreach ($qualScores as $qualScore) {
                $qualEntry = $qualScore->getEntry($dbh);
                $json[] = array(
                  $qualScore->id,
                  $qualEntry->id,
                  round($qualEntry->points),
                  $qualEntry->place,
                  (($qualScore->tournamentDivision_id == 3) ? 'Team' : (($qualScore->tournamentDivision_id == 2) ? 'Classics' : 'Main')),
                  (($qualScore->tournamentDivision_id == 3) ? $qualScore->team : $qualScore->player),
                  $qualScore->game,
                  $qualScore->score,
                  round($qualScore->points),
                  $qualScore->place
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