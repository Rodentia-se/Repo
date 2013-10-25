<?php
  require_once('../functions/general.php');
  header('Content-Type: text/html');
  
  $id = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : false;
  $obj = ($_REQUEST['obj']) ? $_REQUEST['obj'] : 'player';
  
  if ($obj) {
    if ($id) {
      $object = getObjectById($dbh, $obj, $id);
      if ($object) {
        echo $object->getInfo($dbh);
      } else {
        $errorMsg = 'Error: No '.$obj.' with ID '.$id.' found!';
      }
    } else {
      $errorMsg = 'Error: No valid '.$obj.' ID provided!';
    }
  } else {
    $errorMsg = 'Error: No valid object provided!';
  }
  
  if ($errorMsg) {
    echo(json_decode(getError($errorMsg, false))->reason);    
  }
    
?>