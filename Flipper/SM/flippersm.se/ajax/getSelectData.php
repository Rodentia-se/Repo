<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'player' || $_REQUEST['type'] == 'game')) ? $_REQUEST['type'] : null;
  $entryId = (isset($_REQUEST['entryId']) && preg_match('/^[0-9]+$/', $_REQUEST['entryId'])) ? $_REQUEST['entryId'] : null;

  if ($entryId) {
    $qualEntry = getEntryById($dbh, $entryId);
    $type = ($qualEntry->tournamentDivision_id == 3) ? 'team' : 'player';
  }

  if ($type) {
    $objs = (array) getObjectList($dbh, $type, array('tournament' => '1'));

    $json = array('zero' => 'Välj...');
    foreach ($objs as $obj) {
      $json['_'.$obj->id] = $obj->name;
    }

    echo json_encode($json);
  }
?>