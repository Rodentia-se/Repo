<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'player' || $_REQUEST['type'] == 'game')) ? $_REQUEST['type'] : null;
  $entryId = (isset($_REQUEST['entryId']) && preg_match('/^[0-9]+$/', $_REQUEST['entryId'])) ? $_REQUEST['entryId'] : null;
  $scoreId = (isset($_REQUEST['scoreId']) && preg_match('/^[0-9]+$/', $_REQUEST['scoreId'])) ? $_REQUEST['scoreId'] : null;

  if ($entryId) {
    $qualEntry = getEntryById($dbh, $entryId);
    $division = $qualEntry->tournamentDivision_id;
    $playerType = ($division == 3) ? 'team' : 'player';
  }

  if ($scoreId) {
    $qualScore = getScoreById($dbh, $scoreId);
    $division = $qualScore->tournamentDivision_id;
    $playerType = ($division == 3) ? 'team' : 'player';
  }

  $type = ($type != 'game' && $playerType) ? $playerType : $type;

  if ($type) {
    $options['tournament'] = 1;
    if ($division) {
      $options['division'] = $division;
    }
    $objs = (array) getObjectList($dbh, $type, $options);

    $json = array('zero' => 'Välj...');
    foreach ($objs as $obj) {
      $json['_'.$obj->id] = $obj->name;
    }

    echo json_encode($json);
  }
?>