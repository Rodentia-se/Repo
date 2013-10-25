<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $where = ' where ';
  $where .= (((isset($_REQUEST['obj']) && $_REQUEST['obj'] == 'region') || !isset($_REQUEST['obj'])) && (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id']))) ? 'id = '.$_REQUEST['id'] : '1';
  if ($filter = getGeoFilterWheres('region')) {
    $where .= $filter;
  }
  
  $objs = (array) getRegions($dbh, $where);
  
  echo ($objs) ? json_encode(array_values($objs)) : '{"data": false}';
?>