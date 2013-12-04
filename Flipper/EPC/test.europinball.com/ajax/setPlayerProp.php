<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');
  
  $value = (isset($_REQUEST['value'])) ? $_REQUEST['value'] : NULL;
  $id = (isId($value)) ? $value : NULL;
  $prop = (isset($_REQUEST['prop'])) ? $_REQUEST['prop'] : NULL;
  $person_id = (isId($_REQUEST['person_id'])) ? $_REQUEST['person_id'] : NULL;
  
  if ($id) {
    if ($prop) {
      $loginPerson = person('login');
      if ($person_id) {
        $person = person($person_id);
      } else {
        $person = $loginPerson;
      }
      if ($person) {
        if ($loginPerson->id == $person->id || $loginPerson->adminLevel > 1) {
          if (substr($prop, -3) == '_id') {
            if ($id) {
              $class = substr($prop, 0, -3);
              $obj = $class($id, NULL, 0);
              if ($obj) {
                $change = $person->setProp($prop, $id);
                if ($change) {
                  $json['region_id'] = ($class != 'region') ? $obj->region_id : NULL;
                  $json['country_id'] = ($class != 'country') ? $obj->country_id : NULL;
                  $json['continent_id'] = $obj->continent_id;
                  $json = success($prop.' changed to '.$id.' for '.$person->name, $json);
                } else {
                  $json = error('Property assignment failed', FALSE, TRUE);
                }
              } else {
                $json = error('Could not find '.$prop.' ID '.$id, FALSE, TRUE);
              }
            } else {
              $json = error('Malformed value detected', FALSE, TRUE);
            }
          } else {
            $change = $person->setProp($prop, $value);
            if ($change) {
              $json = success($prop.' changed to '.$id.' for '.$person->name);
            } else {
              $json = error('Property assignment failed', FALSE, TRUE);
            }
          }
        } else {
          $json = error('Äuthorization failed', FALSE, TRUE);
        }
      } else {
        $json = error('Could not identify the target person', FALSE, TRUE);
      }
    } else {
      $json = error('No property provided', FALSE, TRUE);
    }
  } else {
      $json = error('No value provided', FALSE, TRUE);
  }
  
  echo(json_encode($json));
  
  debug($_REQUEST);

?>