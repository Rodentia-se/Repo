<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  $where = ' where 1 ';
//  $where .= (((isset($_REQUEST['obj']) && $_REQUEST['obj'] == 'game') || !isset($_REQUEST['obj'])) && (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id']))) ? 'id = '.$_REQUEST['id'] : '1';
  
  $where .= (isset($_REQUEST['t'])) ? ' and tournamentEdition_id = '.$_REQUEST['t'] : '';
  $where .= (isset($_REQUEST['edition_id'])) ? ' and tournamentEdition_id = '.$_REQUEST['edition_id'] : '';
  $where .= (isset($_REQUEST['edition'])) ? ' and tournamentEdition_id = '.$_REQUEST['edition'] : '';
  $where .= (isset($_REQUEST['division_id'])) ? ' and tournamentDivision_id = '.$_REQUEST['division_id'] : '';
  $where .= (isset($_REQUEST['division'])) ? ' and tournamentDivision_id = '.$_REQUEST['division'] : '';
  $where .= (isset($_REQUEST['game_id'])) ? ' and game_id = '.$_REQUEST['game_id'] : '';
  $where .= (isset($_REQUEST['game'])) ? ' and game_id = '.$_REQUEST['game'] : '';
  $where .= (isset($_REQUEST['manufacturer_id'])) ? ' and id = '.$_REQUEST['manufacturer_id'] : '';
  $where .= (isset($_REQUEST['manufacturer'])) ? ' and id = '.$_REQUEST['manufacturer'] : '';
  $where .= (isset($_REQUEST['type'])) ? ' and type = '.$_REQUEST['type'] : '';
    
  $objs = (array) getManufacturers($dbh, $where);
    
  print json_encode(array_values($objs));
?>