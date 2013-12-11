<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  $page->addH2('Password reset');

  if ($page->loggedin()) {
    $person = person('login');
    $page->addParagraph('You are already logged in as '.$person->name.'. You can go to the <a href="'.config::$baseHref.'/edit" id="editLink">Profile editor</a> to change your login');
    $page->addParagraph('If you are not '.$person->name.' and intended to reset the password for someone else, you need to '.page::getButton('Logout', 'resetLogoutButton').' first.');
    $page->startForm('resetLogoutForm');
      $page->addInput('logout', 'resetAction', 'action', 'hidden');
    $page->closeForm();
    $page->addScript('
      $("#resetLogoutButton").click(function() {
        $("#resetLogoutForm").submit();
      });
      $("#editLink").button();
    ');
  } else {
    
  }


  $page->submit();
?>