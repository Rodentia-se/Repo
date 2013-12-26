<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $division_id = (isset($_REQUEST['division_id'])) ? $_REQUEST['division_id'] : null;
  $division = ($division_id) ? division($division_id) : division('main');
  
  $person = person('login');
  if ($person) {
    if ($person->receptionist) {
      if (isDivision($division)) {
        $calc = $person->db->seqWaiting();
        if (is($calc)) {
          $json = success('Waiting list recalculated for '.$calc.' players');
        } else {
          $json = failure('Waiting list recalculation failed');
        }
      } else {
        $json = failure('Could not identify the division');
      }
    } else {
      $json = failure('Authorization failed');
    }
  } else {
    $json = failure('Could not identify the target person');
  }
  
  jsonEcho($json);
  
?>