<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  if ($page->reqLogin('You need to be logged in and registered as a participant to access this page. Please go to the <a href="'.config::$baseHref.'/reigstration/">registration page</a> or login here:')) {
    $page->addParagraph('Hej.');
  } else {
    $page->addParagraph('Huff');
  }
  
  $page->submit();

?>