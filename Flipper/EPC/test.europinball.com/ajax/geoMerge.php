<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');
  
  $obj = (isset($_REQUEST['obj'])) ? $_REQUEST['obj'] : NULL;
  $remove = (isId($_REQUEST['remove'])) ? $_REQUEST['remove'] : NULL;
  $keep = (isId($_REQUEST['keep'])) ? $_REQUEST['keep'] : NULL;

  $person = person('login');
  if ($person) {
    if ($person->admin) {
      if ($obj) {
        if (isGeo($obj, TRUE)) {
          if ($remove) {
            $removeObj = $obj($remove);
            if (isGeo($removeObj)) {
              if ($keep) {
                if ($keep != $remove) {
                  $keepObj = $obj($keep);
                  if (isGeo($keepObj)) {
                    $props = $removeObj->getColNames();
                    if ($props) {
                      foreach ($props as $prop) {
                        $keepObj->$prop = ($keepObj->$prop) ? $keepObj->$prop : $removeObj->$prop;
                      }
 //                     $save = $keepObj->save();
 debug($keepObj);
 $save = TRUE;
                      if ($save) {
                        foreach ($obj::$children as $childClass => $column) {
                          $table = (property_exists($childClass, 'table')) ? $childClass::$table : $childClass;
/*                          $query = '
                            update '.$table.'
                              set '.$column.'_id = '.$keepObj->id.',
                              '.$column.' = "'.$keepObj->name.'"
                            where '.$column.'_id = '.$removeObj->id'
                          ';
                          $update = $keepObj->db->update($query);
                          if (!$update) {
                            $failure = $table;
                          } */
                          debug($table);
                        }
                        if (!$failure) {
//                          $delete = $removeObj->delete();
$delete = TRUE;
                          if ($delete) {
                            $json = success('Merged '.$removeObj->name.' ID '.$remove.' into '.$keepObj->name.' ID '.$keep.'. '.$removeObj->name.' ID '.$remove.' has been removed.');
                          } else {
                            $json = failure('It seems the merging operation succeeded, but could not remove '.$removeObj->name.' ID '.$remove.' after the merge');
                          }
                        } else {
                          $json = failure('Something went wrong trying to update the '.$failure.' table');
                        }
                      } else {
                        $json = failure('Could not save '.$keepObj.' ID '.$keep);
                      }
                    } else {
                      $json = failure('Could not enumerate the '.$obj.' properties');
                    }
                  } else {
                    $json = failure('Could not find '.$obj.' with ID '.$keep);
                  }
                } else {
                  $json = failure('You can not merge '.$remove->name.' ID '.$remove.' into itself');
                }
              } else {
                $json = failure('Invalid '.$obj.' ID to keep');
              }
            } else {
              $json = failure('Could not find '.$obj.' with ID '.$remove);
            }
          } else {
            $json = failure('Invalid '.$obj.' ID to remove');
          }
        } else {
          $json = failure('Invalid typ of geographical object: '.$obj);
        }
      } else {
        $json = failure('No type of genographical object specified');
      }
    } else {
      $json = failure('You are not authorized for this action. Are you correctly logged in?');
    }
  } else {
    $json = failure('Could not identify you. Are you logged in?');
  }
  
  jsonEcho($json);
  
?>