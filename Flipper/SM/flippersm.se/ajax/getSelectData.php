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
    $selected = ($type == 'game') ? $qualEntry->game_id : (($division == 3) ? $qualEntry->team_id : $qualEntry->person_id);
  }

  if ($scoreId) {
    $qualScore = getScoreById($dbh, $scoreId);
    $division = $qualScore->tournamentDivision_id;
    $playerType = ($division == 3) ? 'team' : 'player';
    $selected = ($type == 'game') ? $qualScore->game_id : (($division == 3) ? $qualScore->team_id : $qualScore->person_id);
  }

  $type = ($type != 'game' && $playerType) ? $playerType : $type;

  if ($type) {
    $options['tournament'] = 1;
    if ($division) {
      $options['division'] = $division;
    }
    $objs = (array) getObjectList($dbh, $type, $options);

    $json = array('_0' => 'Välj...');
    foreach ($objs as $obj) {
      $json['_'.$obj->id] = $obj->name;
    }

    $json['selected'] = ($selected) ? '_'.$selected : '_0';

    echo json_encode($json);
  }
?>