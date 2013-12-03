<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  if ($page->reqLogin('You need to be logged in and registered as a participant to access this page. Please go to the <a href="'.config::$baseHref.'/reigstration/">registration page</a> or login here:')) {
    $person = $page->login->person;
    if ($person) {
      $page->startDiv('editDiv');
        $page->addContent($person->getEdit());
      $page->closeDiv(); 
      $page->jeditable = TRUE;
      $page->combobox = TRUE;
      $page->addScript('
        $(".editText").editable("'.config::$baseHref.'/setPlayerProp.php", {
          cssclass: "inherit"
        });
        $( ".combobox" ).combobox();
      ');
    } else {
      error('Could not find you in the database?', TRUE);
    }
  }
  
  $page->submit();

?>