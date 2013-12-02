<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : null;
   
  echo "hej".search;
   
  if ($search) {
    $persons = persons($search);
    if ($persons) {
      $json = (object) array(
        'sEcho' => $_REQUEST['sEcho'],
        'iTotalRecords' => count($persons),
        'iTotalDisplayRecords' => count($persons),
      );
    }
    foreach ($persons as $person) {
      $json->aaData[] = array(
        $person->name,
        $person->shortName,
        $person->cityName,
        $person->regionName,
        $person->countryName,
        $person->getLink('ifpa'),
        $person->getLink('photo'),
        '<input type="button" id="'.$person->id.'_isMe">'
      );
    }
    debug($json);
  } else {
   echo '{"aaData": []}';
  }

?>