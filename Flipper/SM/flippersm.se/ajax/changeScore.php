<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');

  $score = (isset($_REQUEST['value']) && preg_match('/^[0-9 \.,]+$/', $_REQUEST['value'])) ? preg_replace('/[^0-9]/', '', $_REQUEST['value']) : null;
  $scoreId = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : null;

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer) {
    if ($currentPlayer->adminLevel == 1) {
      if ($score == 0 || $score) {
        if ($scoreId) {
          $qualScore = getScoreById($dbh, $scoreId);
          if ($qualScore) {
            if ($qualScore->setScore($dbh, $score)) {
              echo('{"success": true, "reason": "Score set to '.$score.' for score ID '.$qualScore->id.'", "value": "'.$score.'"}');
            } else {
              $errorMsg = 'Could not set score to '.$score.' for score ID '.$qualScore->id;
            }
          } else {
            $errorMsg = 'Could not find the qualification score with ID '.$scoreId;
          }
        } else {
          $errorMsg = 'No or invalid qualification score ID specified';
        }
      } else {
        $errorMsg = 'No or invalid score specified';
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