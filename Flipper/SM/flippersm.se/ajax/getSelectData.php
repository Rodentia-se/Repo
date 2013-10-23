<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'player' || $_REQUEST['type'] == 'game')) ? $_REQUEST['type'] : null;
  
  if ($type) {
    $objs = (array) getObjectList($dbh, $type, array ('tournament' => '1');

    $json[0] = 'Välj...';
    foreach ($players as $player) {
      $json[$player->id] = $player->name;
    }

    echo json_encode($json);
  }
?>