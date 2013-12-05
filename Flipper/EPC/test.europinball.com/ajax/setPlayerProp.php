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
              $json['parents'] = (is_subclass_of($class, 'geography')) ? $class::_getParents(FALSE) : FALSE;
              if ($id > 0) {
                $obj = $class($id, NULL, 0);
                if ($obj) {
                  $parent = $obj->getParent();
                  if ($parent) {
                    $json['parent_obj'] = get_class($parent);
                    $json['parent_id'] = $parent->id;
                  }
                  if ($person->setProp($prop, $id)) {
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
                    $json = success((($id) ? 'Changed '.$prop.' to '.$id : 'Removed '.$prop).' for '.$person->name, $json);
                  } else {
                    $json = error('Property assignment failed', FALSE, TRUE);
                  }
                } else {
                  $json = error('Could not find '.$prop.' ID '.$id, FALSE, TRUE);
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
                  $json = error('Property assignment failed', FALSE, TRUE);
                }
              }
            } else {
              $json = error('Malformed value detected', FALSE, TRUE);
            }
          } else {
            $validator = person::validate($prop, $value, TRUE);
            debug($validator);
            if ($validator->valid) {
              $change = $person->setProp($prop, $value);
              if ($change) {
                $json = success((($value) ? 'Changed '.$prop.' to '.$value : 'Removed '.$prop).' for '.$person->name);
              } else {
                $json = error('Property assignment failed', FALSE, TRUE);
              }
            } else {
              $json = error($validator->reason, FALSE, TRUE);
            }
          }
        } else {
          $json = error('Authorization failed', FALSE, TRUE);
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