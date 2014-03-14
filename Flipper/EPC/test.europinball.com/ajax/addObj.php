<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');
  
  $class = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $props = (isset($_REQUEST['props'])) ? $_REQUEST['props'] : NULL;
  
  if ($class) {
    $volunteer = person('login');
    if ($volunteer) {
      if ($volunteer->receptionist) {
        if (isObj($class, TRUE)) {
          if (isJson($props)) {
            $props = json_decode($props);
            $obj = new $class;
            foreach ($props as $prop => $value) {
              $obj->$prop = $value;
            }
            $save = $obj->save();
            if ($save) {
              $json = success($obj->name.' added');
            } else {
              $json = failure('Could not add '.$obj->name);
            }
          } else {
            $json = failure('Invalid data');
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
