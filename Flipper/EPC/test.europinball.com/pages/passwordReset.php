<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  $page->addH2('Password reset');

  if ($page->loggedin()) {
    $person = person('login');
    $page->addParagraph('You are already logged in as '.$person->name.'. You can go to the <a href="'.config::$baseHref.'/edit" class="buttonLink">Profile editor</a> to change your login credentials.');
    $page->addParagraph('If you are not '.$person->name.' and intended to reset the password for someone else, you need to '.page::getButton('Logout', 'resetLogout').' first.');
    $page->startForm('resetLogoutForm');
      $page->addInput('logout', 'resetAction', 'action', 'hidden');
    $page->closeForm();
  } else {
    $nonce = $_REQUEST['nonce'];
    if ($nonce) {
      if (config::$login->verified) {
        $person = person(array('nonce' => $nonce));
        if ($person) {
          $page->addParagraph('You have been identified as '.$person->name.(($person->shortName) ? ' ('.$person->shortName.')' : '').(($person->cityName || $person->countryName) ? ' from '.(($person->cityName) ? $person->cityName.', ' : '').$person->countryName : '').'. If this is not corret, please '.page::getButton('reload').');
        }
      }
    }
  }


  $page->submit();
?>