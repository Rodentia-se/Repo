<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');
  
  $value = (isset($_REQUEST['value'])) ? $_REQUEST['value'] : NULL;
  $id = (isId($value)) ? $value : NULL;
  $prop = (isset($_REQUEST['prop'])) ? $_REQUEST['prop'] : NULL;
  $person_id = (isId($_REQUEST['person_id'])) ? $_REQUEST['person_id'] : NULL;
  $region_id = (isId($_REQUEST['region_id'])) ? $_REQUEST['region_id'] : NULL;
  $country_id = (isId($_REQUEST['country_id'])) ? $_REQUEST['country_id'] : NULL;
  $continent_id = (isId($_REQUEST['continent_id'])) ? $_REQUEST['continent_id'] : NULL;
  
  if ($value || $value == 0) {
    if ($prop) {
      $loginPerson = person('login');
      if ($person_id) {
        $person = person($person_id);
      } else {
        $person = $loginPerson;
      }
      if ($person) {
        if ($loginPerson->id == $person->id || $loginPerson->receptionist) {
          if (substr($prop, -3) == '_id') {
            if ($id || $id == 0) {
              $class = substr($prop, 0, -3);
              if ($id > 0) {
                $obj = $class($id, config::NOSEARCH, 0);
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
                  if (isGeo($class)) {
                    $json['parents'] = $class::_getParents(FALSE);
                    if ($json['parents']) {
                      foreach ($json['parents'] as $parent) {
                        $person->setProp($parent.'_id', NULL);
                      }
                    }
                  }
                  $json = success((($id) ? 'Changed '.substr($prop, 0, -3).' to '.$obj->name : 'Removed '.substr($prop, 0, -3)).' for '.$person->name, $json);
                } else {
                  $json = failure('Property assignment failed');
                }
              }
            } else {
              $json = failure('Malformed value detected'); 
            }
          } else {
            if (in_array($prop, config::$addables)) {
              if ($value) {
                $obj = $prop(array(
                  'name' => $value,
                  'region_id' => (($region_id && isId($region_id)) ? $region_id : NULL),
                  'country_id' => (($country_id && isId($country_id)) ? $country_id : NULL),
                  'continent_id' => (($continent_id && isId($continent_id)) ? $continent_id : NULL)
                ));
                $id = $obj->save();
                if (isId($id)) {
                  $obj = $prop($id);
                  if ($obj) {
                    $change = $person->setProp($prop.'_id', $id);
                    if ($change) {
                      $json = success('Added '.$value.' as '.$prop.' for '.$person->name);
                    } else {
                      $json = failure('Property assignment failed');
                    }
                  } else {
                    $json = failure(ucfirst($prop).' creation failed');
                  }
                } else {
                  $json = failure(ucfirst($prop).' creation failed');
                }
              } else {
                $json = failure(ucfirst($prop).' creation failed');
              }
            } else if (in_array($prop, config::$divisions)) {
              $tournament = tournament(config::$activeTournament);
              if ($tournament) {
                $division = division($tournament, $prop);
                if ($division) {
                  if ($value == 1) {
                    $change = $person->addPlayer($division);
                    if ($change) {
                      $player = player($person, $division);
                      if ($player) {
                        $json = success('Added '.$person->name.' to the '.$division->divisionName);
                      } else {
                        $json = failure('Could not add '.$person->name.' to the '.$division->divisionName);
                      }
                    } else {
                      $json = failure('Could not add '.$person->name.' to the '.$division->divisionName);
                    }
                  } else if ($value == 0) {
                    $player = player($person, $division);
                    if ($player) {
                      $change = $player->delete();
                      if ($change) {
                        $player = player($person, $division);
                        if (!$player) {
                          $json = success('Removed '.$person->name.' from the '.$division->divisionName);
                        } else {
                          $json = failure('Could not remove '.$person->name.' from the '.$division->divisionName);
                        }
                      } else {
                        $json = failure('Could not remove '.$person->name.' from the '.$division->divisionName);
                      }
                    } else {
                      $json = success($person->name.' is not registered for the '.$division->divisionName);
                    }
                  } else {
                    $json = failure('Invalid request');
                  }
                } else {
                  $json = failure('Could not identify the division');
                }
              } else {
                $json = failure('Could not identify the tournament');
              }
            } else {
              $validator = person::validate($prop, $value, TRUE);
              if ($prop == 'password' && $loginPerson->id != $person->id && !$loginPerson->receptionist) {
                $json = failure('You need to be a receptionist or administrator to be able to change passwords');
              } else {
                if ($prop == 'adminLevel' && $loginPerson->receptionist < $value) {
                  $json = failure('You can not grant priviliges to higher levels than your own.');
                } else {
                  if ($validator->valid || ($prop == 'password' && $loginPerson->admin)) {
                    $change = $person->setProp($prop, $value);
                    if ($change) {
                      $json = success((($value) ? 'Changed '.$prop.(($prop != 'password') ? ' to '.$value : '') : 'Removed '.$prop).' for '.$person->name, $loginPerson);
                    } else {
                      $json = failure('Property assignment failed');
                    }
                  } else {
                    $json = failure($validator->reason);
                  }
                }
              }
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
  
  jsonEcho($json);
  
?>