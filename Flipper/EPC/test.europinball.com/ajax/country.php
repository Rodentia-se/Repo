<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  
  $where = ' where ';
  $where .= (((isset($_REQUEST['obj']) && $_REQUEST['obj'] == 'country') || !isset($_REQUEST['obj'])) && (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id']))) ? 'id = '.$_REQUEST['id'] : '1';
  if ($filter = getGeoFilterWheres('country')) {
    $where .= $filter;
  }
        
  $objs = (array) getCountries($dbh, $where);
    
  echo ($objs) ? json_encode(array_values($objs)) : '{"data": false}';
?>