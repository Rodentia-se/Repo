<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');
  
  $class = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;
  $prop = (isset($_REQUEST['prop'])) ? $_REQUEST['prop'] : NULL;
  $value = (isset($_REQUEST['value'])) ? $_REQUEST['value'] : NULL;

  $region_id = (isId($_REQUEST['region_id'])) ? $_REQUEST['region_id'] : NULL;
  $country_id = (isId($_REQUEST['country_id'])) ? $_REQUEST['country_id'] : NULL;
  $continent_id = (isId($_REQUEST['continent_id'])) ? $_REQUEST['continent_id'] : NULL;
  
  if (is($value)) {
    if ($prop) {
      $person = person('login');
      if ($person) {
        if ($class) {
          if (isObj($class, TRUE)) {
            if (isId($id) && $id != 0) {
              $obj = $class($id);
              if (isObj($obj)) {
                if ($obj->authorize($person, $prop, $value)) {
                  if (substr($prop, -3) == '_id') {
                    $propClass = $class::$parents[substr($prop, 0, -3)];
                    if (isObj($propClass, TRUE)) {
                      if ($value > 0) {
                        $propObj = $propClass($value, config::NOSEARCH, 0);
                        if (isObj($propObj)) {
                          if ($obj->setProp($prop, $propObj)) {
                            if (isGeo($propObj)) {
                              $json['parents'] = $propClass::_getParents(FALSE);
                              $parent = $propObj->getParent();
                              if ($parent) {
                                $json['parent_obj'] = get_class($parent);
                                $json['parent_id'] = $parent->id;
                              }
                              if ($json['parents']) {
                                foreach ($json['parents'] as $parent) {
                                  if (!$stop) {
                                    if($propObj && $propObj->{$parent.'_id'}) {
                                      if ($propObj->{$parent.'_id'} != $obj->{$parent.'_id'}) {
                                        $obj->setProp($parent.'_id', $propObj->{$parent.'_id'});
                                      }
                                      $stop = TRUE;
                                    } else if ($obj->{$parent.'_id'}) {
                                      $obj->setProp($parent.'_id', NULL);
                                    }
                                  }
                                }
                              }
                            }
                            $json = success((($value) ? 'Changed '.substr($prop, 0, -3).' to '.$propObj->name : 'Removed '.substr($prop, 0, -3)).' for '.$obj->name, $json);
                          } else {
                            $json = failure('Property assignment failed');
                          }
                        } else {
                          $json = failure('Could not find '.$prop.' ID '.$value);
                        }
                      } else {
                        if ($obj->setProp($prop, NULL)) {
                          if (isGeo($propClass)) {
                            $json['parents'] = $propClass::_getParents(FALSE);
                            if ($json['parents']) {
                              foreach ($json['parents'] as $parent) {
                                $obj->setProp($parent.'_id', NULL);
                              }
                            }
                          }
                          $json = success((($value) ? 'Changed '.substr($prop, 0, -3).' to '.$propObj->name : 'Removed '.substr($prop, 0, -3)).' for '.$obj->name, $json);
                        } else {
                          $json = failure('Property assignment failed');
                        }
                      }
                    } else {
                      $json = failure('Invalid property class '.$propClass);
                    }
                  } else {
                    $propClass = $class::$parents[substr($prop, 0, -3)];
                    if (isObj($propClass, TRUE)) {
                      if (in_array($prop, config::$addables)) {
                        if ($value) {
                          $propObj = $propClass(array(
                            'name' => $value,
                            'region_id' => (($region_id && isId($region_id)) ? $region_id : NULL),
                            'country_id' => (($country_id && isId($country_id)) ? $country_id : NULL),
                            'continent_id' => (($continent_id && isId($continent_id)) ? $continent_id : NULL)
                          ));
                          $value = $propObj->save();
                          if (isId($value)) {
                            $propObj = $propClass($value);
                            if ($obj) {
                              if ($obj->setProp($prop, $propObj)) {
                                $json = success('Added '.$propObj->name.' as '.$prop.' for '.$person->name);
                              } else {
                                $json = failure('Property assignment failed');
                              }
                            } else {
                              $json = failure(ucfirst($propClass).' creation failed');
                            }
                          } else {
                            $json = failure(ucfirst($propClass).' creation failed');
                          }
                        } else {
                          $json = failure(ucfirst($propClass).' creation failed');
                        }
                      } else {
                        $json = failure('Invalid property class '.$propClass);
                      }
                    } else if (in_array($prop, config::$divisions) && in_array($class, array('person', 'game'))) {
                      $tournament = tournament(config::$activeTournament);
                      if ($tournament) {
                        switch ($class) {
                          case 'person':
                            $subObj = 'player';
                          break;
                          case 'game':
                            $subObj = 'machine';
                          break;
                        }
                        $division = division($tournament, $prop);
                        if ($division) {
                          if ($value === 1 || $value === '1') {
                            if ($obj->addDivision($division)) {
                              if ($subObj) {
                                $$subObj = $subObj($obj, $division);
                                if (isObj($$subObj)) {
                                  $json = success('Added '.$obj->name.' to the '.$division->divisionName);
                                } else {
                                  $json = failure('Could not add '.$obj->name.' to the '.$division->divisionName);
                                }
                              }
                            } else {
                              $json = failure('Could not add '.$obj->name.' to the '.$division->divisionName);
                            }
                          } else if ($value === 0 || $value === '0') {
                            $$subObj = $subObj($obj, $division);
                            if ($$subObj) {
                              if ($$subObj->delete()) {
                                $$subObj = $subObj($obj, $division);
                                if (!isObj($$subObj)) {
                                  $json = success('Removed '.$obj->name.' from the '.$division->divisionName);
                                } else {
                                  $json = failure('Could not remove '.$obj->name.' from the '.$division->divisionName);
                                }
                              } else {
                                $json = failure('Could not remove '.$obj->name.' from the '.$division->divisionName);
                              }
                            } else {
                              $json = success($obj->name.' is not registered for the '.$division->divisionName);
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
                      $validator = $class::validate($prop, $value, TRUE);
                      if ($validator->valid || ($prop === 'password' && $loginPerson->admin)) {
                        if ($obj->setProp($prop, $value)) {
                          $json = success((($value) ? 'Changed '.$prop.(($prop != 'password') ? ' to '.$value : '') : 'Removed '.$prop).' for '.$obj->name);
                        } else {
                          $json = failure('Property assignment failed');
                        }
                      } else {
                        $json = failure($validator->reason);
                      }
                    }
                  }
                } else {
                  $json = failure('You are not authorized to make the change.');
                }
              } else {
                $json = failure('Could not find '.$class.' ID '.$id);
              }
            } else {
              $json = failure('Invalid ID '.$id);
            }
          } else {
            $json = failure('Invalid object class '.$class);
          }
        } else {
          $json = failure('No class provided');
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