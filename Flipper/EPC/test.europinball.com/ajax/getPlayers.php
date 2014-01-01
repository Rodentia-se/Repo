<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');

  $search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : null;
  $type = (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'regSearch' || $_REQUEST['type'] == 'registered')) ? $_REQUEST['type'] : null;
  $obj = (isset($_REQUEST['obj'])) ? $_REQUEST['obj'] : null;
  $id = (isId($_REQUEST['id'])) ? $_REQUEST['id'] : null;
        debug(count($players), 'players1');

  if ($obj) {
    if ($id) {
      $object = $obj($id);
      if ($object) {
        $players = players($object);
        debug(count($players), 'players2');
      }
    }
  } else if ($id) {
    $player = player($id);
  } else if ($search) {
    $players = players($search);
  }
        debug(count($players), 'players3');
  if ($player) {
    $players = players($player);
  }

        debug(count($players), 'players4');
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
              $person->getPhotoIcon(),
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
    case 'profileEdit':
      debug($players, 'players5');
      if (isPlayer($players[0])) {
        echo $players[0]->getEdit('profile');
      } else {
        echo 'Could not find player to edit';
      }
    break;
  }
        debug(count($players), 'players6');

?>