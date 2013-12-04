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
                $geos = array('city', 'region', 'country', 'continent');
                foreach ($geos as $geo) {
                  if ($geo == $class) {
                    foreach($geos as $subGeo) {
                      if ($start && $obj->{$subGeo.'_id'}) {
                        $json['new_id'] = $obj->{$subGeo.'_id'};
                        $json['new_obj'] = $subGeo;
                      }
                      if ($subGeo == $geo) {
                        $start = true;
                      }
                    }
                  }
                }
                $change = $person->setProp($prop, $id);
                if ($change) {
                  switch ($class) {
                    case 'city':
                      $json['new_id'] = $obj->region_id;
                      $json['new_obj'] = 'region';
                    break;
                    case 'region':
                      $json['new_id'] = $obj->country_id;
                      $json['new_obj'] = 'country';
                    break;
                    case 'country':
                      $json['new_id'] = $obj->continent_id;
                      $json['new_obj'] = 'continent';
                    break;
                  }
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
  
  header('Content-Type: application/json');
  echo(json_encode($json));

?>