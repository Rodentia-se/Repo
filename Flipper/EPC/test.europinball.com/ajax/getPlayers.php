<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  $ajax = TRUE;
  require_once(__ROOT__.'/functions/init.php');

  $search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : null;
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'regSearch' || $_REQUEST['type'] == 'registered')) ? $_REQUEST['type'] : null;
  $obj = (isset($_REQUEST['obj'])) ? $_REQUEST['obj'] : null;
  $id = (isId($_REQUEST['id'])) ? $_REQUEST['id'] : null;
  
  if ($obj) {
    if ($id) {
      $object = $obj($id);
      if ($object) {
        $players = players($object);
      }
    }
  } else if ($id) {
    $player = player($id);
  } else if ($search) {
    $players = players($search);
  }
  if ($player) {
    $players = players($player);
  }
  
  switch ($type) {
    case 'registered':
      if ($players && count($players) > 0) {
        $json = (object) array(
          'sEcho' => $_REQUEST['sEcho'],
          'iTotalRecords' => count($players),
          'iTotalDisplayRecords' => count($players)
        );
        foreach ($players as $player) {
          $json->aaData[] = $player->getRegRow(TRUE);
        }
      }
      jsonEcho($json);
    break;
    case 'regSearch':
      if ($search) {
        $persons = persons($search);
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
              '<form id="'.$person->id.'_isMeForm" method="POST"><input type="hidden" name="register" value="isMe"><input type="hidden" name="person_id" value="'.$person->id.'"><input type="button" id="'.$person->id.'_isMe" class="isMe" value="This is me!"></form>'
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
      } else {
        $json = (object) array(
          'sEcho' => $_REQUEST['sEcho'],
          'iTotalRecords' => 0,
          'iTotalDisplayRecords' => 0,
          'aaData' => array()
        );
      }
      jsonEcho($json);
    break;
  }

?>