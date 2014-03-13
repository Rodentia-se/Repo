<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');
  
  $class = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $id = (isId($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;
  
  if ($class) {
    $volunteer = person('login');
    if ($volunteer) {
      if ($volunteer->receptionist) {
        if (isObj($class, TRUE)) {
          if (isId($id)) {
            $obj = $class($id);
            if ($obj) {
              if (isObj($obj)) {
                $delete = $obj->delete();
                if ($delete) {
                  $json = success($obj);
                } else {
                  $json = failure('Could not delete '.$obj->name);
                }
              } else {
                $json = failure('No such object');
              }
            } else {
              $json = failure('No such object');
            }
          } else {
            $json = failure('Invalid ID');
          }
        } else {
          $json = failure('No such class');
        }
      } else {
        $json = failure('You are not authorized to make the change.');
      }
    } else {
      $json = failure('Could not identify you. Are you logged in?');
    }
  } else {
    $json = failure('No class provided');
  }
  
  jsonEcho($json);
  
?>
