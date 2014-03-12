<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    $tournament = tournament('active');
    $persons = persons($tournament);
    foreach ($persons as $person) {
      $output .= $person->getQrLabel();
    }
    echo $output;
  } else {
    echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
  }

?>