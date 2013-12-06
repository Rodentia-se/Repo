<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');
  
  $value = (isset($_REQUEST['value'])) ? $_REQUEST['value'] : NULL;
  $id = (isId($value)) ? $value : NULL;
  $prop = (isset($_REQUEST['prop'])) ? $_REQUEST['prop'] : NULL;
  $person_id = (isId($_REQUEST['person_id'])) ? $_REQUEST['person_id'] : NULL;
  
  if ($id || $id == 0) {
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
            if ($id || $id == 0) {
              $class = substr($prop, 0, -3);
              if ($id > 0) {
                $obj = $class($id, NULL, 0);
                if ($obj) {
                  if ($person->setProp($prop, $id)) {
                    if (isGeo($obj)) {
                      $json['parents'] = $class::_getParents(FALSE);
                      $parent = $obj->getParent();
                      if ($parent) {
                        $json['parent_obj'] = get_class($parent);
                        $json['parent_id'] = $parent->id;
                      }
                      if ($json['parents']) {
                        foreach ($json['parents'] as $parent) {
                          if (!$stop) {
                            if($obj && $obj->{$parent.'_id'}) {
                              if ($obj->{$parent.'_id'} != $person->{$parent.'_id'}) {
                                $person->setProp($parent.'_id', $obj->{$parent.'_id'});
                              }
                              $stop = TRUE;
                            } else if ($person->{$parent.'_id'}) {
                              $person->setProp($parent.'_id', NULL);
                            }
                          }
                        }
                      }
                    }
                    $json = success((($id) ? 'Changed '.substr($prop, 0, -3).' to '.$obj->name : 'Removed '.substr($prop, 0, -3)).' for '.$person->name, $json);
                  } else {
                    $json = failure('Property assignment failed');
                  }
                } else {
                  $json = failure('Could not find '.$prop.' ID '.$id);
                }
              } else {
                if ($person->setProp($prop, NULL)) {
                  if ($json['parents']) {
                    foreach ($json['parents'] as $parent) {
                      $person->setProp($parent.'_id', NULL);
                    }
                  }
                  $json = success((($id) ? 'Changed '.$prop.' to '.$id : 'Removed '.$prop).' for '.$person->name, $json);
                } else {
                  $json = failure('Property assignment failed');
                }
              }
            } else {
              $json = failure('Malformed value detected'); 
            }
          } else {
            $validator = person::validate($prop, $value, TRUE);
            if ($validator->valid) {
              $change = $person->setProp($prop, $value);
              if ($change) {
                $json = success((($value) ? 'Changed '.$prop.' to '.$value : 'Removed '.$prop).' for '.$person->name);
              } else {
                $json = failure('Property assignment failed');
              }
            } else {
              $json = failure($validator->reason);
            }
          }
        } else {
          $json = failure('Authorization failed');
        }
      } else {
        $json = failure('Could not identify the target person');
      }
    } else {
      $json = failure('No property provided');
    }
  } else {
      $json = failure('No value provided');
  }
  
  header('Content-Type: application/json');
  echo($json);

?>