<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'player' || $_REQUEST['type'] == 'game')) ? $_REQUEST['type'] : null;
  $entryId = (isset($_REQUEST['entryId']) && preg_match('/^[0-9]+$/', $_REQUEST['entryId'])) ? $_REQUEST['entryId'] : null;
  $scoreId = (isset($_REQUEST['scoreId']) && preg_match('/^[0-9]+$/', $_REQUEST['scoreId'])) ? $_REQUEST['scoreId'] : null;

  if ($entryId) {
    $qualEntry = getEntryById($dbh, $entryId);
    $division = $qualEntry->tournamentDivision_id;
    $type = ($division == 3) ? 'team' : 'player';
  }

  if ($scoreId) {
    $qualScore = getScoreById($dbh, $scoreId);
    $division = $qualScore->tournamentDivision_id;
    $type = ($division == 3) ? 'team' : 'player';
  }

  if ($type) {
    $objs = (array) getObjectList($dbh, $type, array('tournament' => '1', (($division) ? 'division' => $division : null)));

    $json = array('zero' => 'Välj...');
    foreach ($objs as $obj) {
      $json['_'.$obj->id] = $obj->name;
    }

    echo json_encode($json);
  }
?>