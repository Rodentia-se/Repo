<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'player' || $_REQUEST['type'] == 'game')) ? $_REQUEST['type'] : null;
  
  if ($type) {
    $objs = (array) getObjectList($dbh, $type, array('tournament' => '1'));

    $json = array(0 => 'Välj...');
    foreach ($objs as $obj) {
      $json[$obj->id] = $obj->name;
    }

    echo json_encode($json);
  }
?>