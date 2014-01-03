<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');

  $class = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $data = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : NULL;
  $search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : NULL;
  $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;
  $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : NULL;
  $single = (isset($_REQUEST['single'])) ? TRUE : FALSE;

  $types = array('regSearch', 'registered', 'edit', 'photo', 'user', 'users');
  if (!in_array($type, $types)) {
    jsonEcho(failure('Invalid type '.$type), TRUE);
  }

  function objCheck($class, $id, $type = 'data') {
    if ($id) {
      if (isId($id)) {
        if ($class) {
          if (isObj($class, TRUE)) {
            $obj = $class($id);
            if (isObj($obj)) {
              return $obj;
            } else {
              jsonEcho(failure('Could not find '.$type.' '.$data.' ID '.$data_id), TRUE);
            }
          } else {
            jsonEcho(failure('Invalid '.$type.' class '.$data), TRUE);
          }
        } else {
          jsonEcho(failure('No '.$type.' class provided'), TRUE);
        }
      } else {
        jsonEcho(failure('Invalid '.$type.' ID provided '.$data_id), TRUE);
      }
    } else {
      return $class;
    }
  }
  
  if ($class) {
    if (isObj($class, TRUE)) {
      if ($id) {
        if (isId($id)) {
          $obj = $class($id);
          if (isObj($obj)) {
            $arrClass = $class::$arrClass;
            $objs = $arrClass($obj);
          } else {
            jsonEcho(failure('Could not find '.$class.' ID '.$id), TRUE);
          }
        } else {
          jsonEcho(failure('Invalid '.$class.' ID '.$id), TRUE);
        }
      } else {
        $data = objCheck($data, $data_id);
        $search = objCheck($search, $search_id);
        $objs = $class($data, $search);
      }
    } else {
      jsonEcho(failure('No such class'), TRUE);
    }
  } else {
    jsonEcho(failure('No class provided'), TRUE);
  }

  switch ($type) {
    case 'registered':
      $json = (object) array(
        'sEcho' => $_REQUEST['sEcho'],
        'iTotalRecords' => count($objs),
        'iTotalDisplayRecords' => count($objs)
      );
      if (isGroup($objs) && count($objs) > 0) {
        foreach ($objs as $obj) {
          $json->aaData[] = $obj->getObj('getRegRow', TRUE);
        }
      }
      jsonEcho($json);
    break;
    case 'regSearch':
      $json = (object) array(
        'sEcho' => $_REQUEST['sEcho'],
        'iTotalRecords' => count($objs),
        'iTotalDisplayRecords' => count($objs)
      );
      if (isGroup($objs) && count($objs) > 0) {
        foreach ($objs as $obj) {
          $json->aaData[] = $obj->getObj('getRegSearch');
        } 
      }
      jsonEcho($json);
    break;
    case 'photo':
    case 'edit':
    case 'user':
    case 'users':
      if (isGroup($objs) && count($objs) > 0) {
        foreach ($objs as $obj) {
          echo $obj->getEdit($type);
        }
      } else {
        echo 'Could not find '.$class.' to edit';
      }
    break;
  }
  
?>