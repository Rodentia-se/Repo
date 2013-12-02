<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : null;
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'regSearch' || $_REQUEST['type'] == 'scores')) ? $_REQUEST['type'] : null;
   
  if ($search) {
    $persons = persons($search);
  }
  switch ($type) {
    case 'regSearch':
      if ($persons && count($persons) > 0) {
        $json = (object) array(
          'sEcho' => $_REQUEST['sEcho'],
          'iTotalRecords' => count($persons),
          'iTotalDisplayRecords' => count($persons)
        );
        foreach ($persons as $person) {
          $json->aaData[] = array(
            $person->name,
            $person->shortName,
            $person->cityName,
            $person->countryName,
            $person->getLink('ifpa'),
            $person->getLink('photo'),
            '<form id="'.$person->id.'_isMeForm" method="POST"><input type="hidden" name="register" value="yes"><input type="hidden" name="person_id" value="'.$person->id.'"><input type="button" id="'.$person->id.'_isMe" class="isMe" value="This is me!"></form>'
          );
        } 
      } else {
        $json = (object) array(
          'sEcho' => $_REQUEST['sEcho'],
          'iTotalRecords' => 0,
          'iTotalDisplayRecords' => 0,
          'aaData' => array()
        );
      }
      echo json_encode($json);
    break;
  }

?>