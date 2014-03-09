<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');

  $class = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;
  $data = (isset($_REQUEST['data'])) ? $_REQUEST['data'] : NULL;
  $data_id = (isset($_REQUEST['data_id'])) ? $_REQUEST['data_id'] : NULL;
  $search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : NULL;
  $search_id = (isset($_REQUEST['search_id'])) ? $_REQUEST['search_id'] : NULL;
  $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : NULL;

  $types = array('regSearch', 'registered', 'edit', 'photo', 'user', 'users', 'admin', 'members', 'groupsAdmin');
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
          debug($obj);
        } else {
          failure('Invalid '.$class.' ID '.$id);
        }
      } else {
        $data = objCheck($data, $data_id);
        $search = objCheck($search, $search_id);
        $obj = $class($data, $search);
      }
      if (isObj($obj)) {
        $arrClass = $class::$arrClass;
        $objs = $arrClass($obj);
      } else {
        debug($obj);
        failure('Could not find the '.$class);
      }
    } else if (isGroup($class, TRUE)) {
      $data = objCheck($data, $data_id);
      $search = objCheck($search, $search_id, 'search');
      $objs = $class($data, $search);
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
        'iTotalDisplayRecords' => count($objs),
        'aaData' => array()
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
        'iTotalDisplayRecords' => count($objs),
        'aaData' => array()
      );
      if (isGroup($objs) && count($objs) > 0) {
        foreach ($objs as $obj) {
          $json->aaData[] = $obj->getObj('getRegSearch');
        } 
      }
      jsonEcho($json);
    break;
    case 'admin':
      $person = person('login');
      if ($person) {
        if (!$person->admin) {
          echo new paragraph('You are not authorized for this section.');
          exit(1);
        }
      } else {
        jsonEcho(failure('Could not identify you. Are you logged in?'), TRUE);
      }
    case 'members':
    case 'photo':
    case 'edit':
    case 'user':
    case 'users':
    case 'groupsAdmin':
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