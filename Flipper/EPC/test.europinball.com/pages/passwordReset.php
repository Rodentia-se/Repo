<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  $page->addH2('Password reset');

  if ($page->loggedin()) {
    $person = person('login');
    $page->addParagraph('You are already logged in as '.$person->name.'. You can go to the <a href="'.config::$baseHref.'/edit" class="buttonLink">Profile editor</a> to change your login credentials.');
    $page->addParagraph('If you are not '.$person->name.' and intended to reset the password for someone else, you need to '.page::getButton('Log out', 'resetLogout').' first.');
    $page->addForm('resetLogout', array('action' => 'logout'));
  } else {
    $nonce = $_REQUEST['resetNonce'];
    if ($nonce) {
      if (ulNonce::Verify('resetReq', $nonce)) {
        $person = person(array('nonce' => $nonce));
        if ($person && isId($person->id)) {
          $page->addParagraph('You have been identified as '.$person->name.(($person->shortName) ? ' ('.$person->shortName.')' : '').(($person->cityName || $person->countryName) ? ' from '.(($person->cityName) ? $person->cityName.', ' : '').$person->countryName : '').'. If this is not corret, please '.page::getButton('reload').' this page.');
        }
      }
    }
  }
$person = person(1);
        if ($person && isId($person->id)) {
          $page->addParagraph('You have been identified as '.$person->name.(($person->shortName) ? ' ('.$person->shortName.')' : '').(($person->cityName || $person->countryName) ? ' from '.(($person->cityName) ? $person->cityName.', ' : '').$person->countryName : '').'. If this is not corret, please '.page::getButton('reload').' this page and try again.');
          $page->addForm('reload');
          $resetDone = ulNonce::Create('resetDone');
          $page->startDiv('security');
            $page->addNewUser('Provide new credentials', $person->id, 'reset');
          $page->closeDiv();
          $page->addScript('
            $("#resetaction").val("reset");
            $("#resetnewUserButton").val("Submit");
          ');
        }

  $page->submit();
?>