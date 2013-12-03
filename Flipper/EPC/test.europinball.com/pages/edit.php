<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register', true);
  
  if ($page->reqLogin) {
    $page->addParagraph('Hej.');
  } else {
    $page->addParagraph('You need to be logged in and registered as a participant to access this page. Please go to the <a href="'.config::$baseHref.'/reigstration/">registration page</a>.')
  }
  
  $page->submit();

?>