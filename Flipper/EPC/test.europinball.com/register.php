<?php

  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register', true);

    debug($_SESSION);
  if ($page->loggedin()) {
    $person = $page->login->person;
    $player = $person->getPlayer();
    debug($player, TRUE);
    if ($player) {
      header('Location: '.config::$baseHref.'/edit/');
    } else {
      if ($_REQUEST['action'] == 'register') {
        $person->addPlayer();
        header('Location: '.config::$baseHref.'/edit/');
      } else {
        $page->content .= '
          <h2 class="entry-title">Register player</h2>
          <p>You are logged in as '.$page->login->person->name.'. Press the button to register for EPC 2014:
          <a href="'.config::$baseHref.'/registration/?action=register"><input type="button" id="registerButton" value="Register"></a>
        ';
      }
    }
  } else {
    $page->setEditable();
    $page->content .= '
        <div id="login">
          <h2 class="entry-title">Register existing player</h2>
    ';
    $page->addLogin('If you participated in EPC 2013 or a any other tournament using this system, then please login here', true);
    $page->content .= '
          <p>If you are sure you do not have any user, please click this button to proceed: <input type="button" id="view_search" class="viewButton" value="Search">
        </div>
        <div id="search" style="display: none">
          <h2 class="entry-title">Register a new player</h2>
          <p>We might already know who you are! Enter your IFPA ID (visible in the address bar when you look at your IFPA page), your email address or phone number used for SO, SM or EPC registrations in the past, your first, last, middle, partial or full name (more than three letters) or even your three-letter TAG (include trailing spaces). Then press the button (or enter/return) and feel the magic. If we can\'t find you, just try another sarch - we\'ve got more than 20 000 friends, and you\'re most probably one of them.</p>
          <p>PLEASE SEARCH BEFORE YOU DECIDE TO CLICK ON THE NEW GUY BUTTON! If you have ever played a pinball tournament, you are most likely NOT a new guy.</p>
          <p>Enter IFPA ID, email address, phone number, name or tag: <input type="text" id="search" name="search">
          <input type="button" id="nologinButton" value="Search"><input type="button" id="nologinButton" value="Search"><input type="button" id="view_login" class="viewButton" value="Back to login">
          <div id="searchResults">Resultat</div>
        </div>
    ';
  }
  $page->addScript("
            $('.viewButton').click(function(event){
              $('#login').hide();
              $('#search').hide();
              $('#' + event.target.id.replace('view_', '')).show();
            });
  ", TRUE);
  
  $page->submit();

?>