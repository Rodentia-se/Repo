<?php
  require_once('../functions/general.php');
  header('Content-Type: application/json');
  
  /*
  $join = (isset($_REQUEST['t'])) ? ' left join player l on l.person_id = p.id' : '';
  $join .= ' left join gender g on p.gender_id = g.id';
  */
  
  $where = ' where ';
  $where .= (((isset($_REQUEST['obj']) && $_REQUEST['obj'] == 'player') || !isset($_REQUEST['obj'])) && (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id']))) ? 'id = '.$_REQUEST['id'] : '1';
  if ($filter = getGeoFilterWheres()) {
    $where .= $filter;
  }
  $where .= (isset($_REQUEST['t'])) ? ' and tournamentEdition_id = '.$_REQUEST['t'] : '';
  $where .= ((isset($_REQUEST['obj']) && ($_REQUEST['obj'] == 'qualgroup' || $_REQUEST['obj'] == 'qualGroup')) && (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id']))) ? ' and (m.qualGroup_id = '.$_REQUEST['id'].' or cl.qualGroup_id = '.$_REQUEST['id'].') ' : '';
/*  
  $where .= (isset($_REQUEST['t'])) ? ' and tournamentEdition_id = '.$_REQUEST['t'] : ' and 1';
  $where .= (isset($_REQUEST['city_id']) && preg_match('/^[0-9]+$/', $_REQUEST['city_id'])) ? ' and city_id = '.$_REQUEST['city_id'] : ' and 1';
  $where .= (isset($_REQUEST['region_id']) && preg_match('/^[0-9]+$/', $_REQUEST['region_id'])) ? ' and region_id = '.$_REQUEST['region_id'].' or parentRegion_id = '.$_REQUEST['region_id'] : ' and 1';
  $where .= (isset($_REQUEST['country_id']) && preg_match('/^[0-9]+$/', $_REQUEST['country_id'])) ? ' and country_id = '.$_REQUEST['country_id'].' or parentCountry_id = '.$_REQUEST['country_id'] : ' and 1';
  $where .= (isset($_REQUEST['continent_id']) && preg_match('/^[0-9]+$/', $_REQUEST['continent_id'])) ? ' and continent_id = '.$_REQUEST['continent_id'] : ' and 1';
    
  $query = 'select concat(p.firstName," ",p.lastName) as name, p.*, g.name as gender from person p'.$join.$where.' order by p.firstName, p.lastName';
  
  $objs = (array) getObjects($dbh, 'player', $query);
  */
  
  $objs = (array) getPlayers($dbh, $where);

  echo ($objs) ? json_encode(array_values($objs)) : '{"data": false}';
?>