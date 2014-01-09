<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  config::$debug = TRUE;
  
  $person_id = (isset($_REQUEST['person_id'])) ? $_REQUEST['person_id'] : NULL;
  $username = (isset($_REQUEST['username'])) ? $_REQUEST['username'] : NULL;
  $password = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : NULL;

  $loginPerson = person('login');

  if ($person_id) {
    if (isId($person_id)) {
      $person = person($person_id);
      if (isPerson($person)) {
        if ($username) {
          $usernameValidator = person::validate('username', $username, TRUE);
          if ($usernameValidator->valid) {
            if ($password) {
              $passwordValidator = person::validate('password', $password, TRUE);
              if ($passwordValidator->valid) {
                if ($loginPerson->id === $person->id || $loginPerson->receptionist) {
                  $newUser = config::$login->addUser($username, $password, $person);
                  if ($newUser) {
                    $info = (object) array('username' => $username);
                    $json = success('Added user '.$username.' for '.$person->name, $info);
                  } else {
                    $json = failure('User could not be added: '.config::$msg);
                  }
                } else {
                  $json = failure('You are not authorized to make this change');
                }
              } else {
                $json = failure($passwordValidator->reason);
              }
            } else {
              $json = failure('No password provided');
            }
          } else {
            $json = failure($usernameValidator->reason);
          }
        } else {
          $json = failure('No username provided');
        }
      } else {
        $json = failure('Could not find person ID '.$person_id);
      }
    } else {
      $json = failure('Invalid target person ID '.$person_id);
    }
  } else {
    $json = failure('No target person provided');
  }
  
  jsonEcho($json);

?>