<?php
  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  if (checkLogin($dbh, $ulogin, false)) {
    printHeader('EPC 2013', __baseHref__);
    printTopper("getObjects('geo');");
    $content = showEditPlayer($dbh, $ulogin);
    echo($content);
    echo'<span class="italic">If you intend to register another player, you need to logout first.</span>';
  } else {
    
    printHeader('EPC 2013', __baseHref__);
    printTopper("getObjects('geo');document.getElementById('ifpaIdText').focus()");

    $content = '<h2 class="entry-title">Register player</h2>';
    $content .= showLogin($ulogin, 'If you already have a user, please login.');
    $content .= '<br /><br /><h3 class="entry-title">Otherwise, register a new player:</h3>';

    $form = new regForm();
    $form->id = 'reg';
    $form->table = false;
    $newField = new formInput('hidden', 'dateRegistered', date('Y-m-d'));
    $form->addField($newField);

    $geoComment = new formInput('comment', 'ifpaComment', 'We might already know who you are! Enter your IFPA ID (visible in the address bar when you look at your IFPA page), your email address or phone number used for SO, SM or EPC registrations in the past, your first, last, middle, partial or full name (more than three letters) or even your three-letter TAG (include trailing spaces). Then press the button (or enter/return) and feel the magic. If we can\'t find you, just try another sarch - we\'ve got 20 000 friends, and you\'re most probably one of them.<br /><br /><p class="bold">PLEASE SEARCH BEFORE YOU DECIDE TO CLICK ON THE NEW GUY BUTTON! If you have ever played a pinball tournament, you are most likely NOT a new guy.</p>');
    $geoComment->label = '';
    $form->addField($geoComment);

    $newField = new formInput();
    $newField->id = 'ifpaIdText';
    $newField->name = 'ifpaId';
    $newField->label = 'Enter IFPA ID, email address, phone number or full name';
    $newField->type = 'text';
    $newField->keypress = 'checkIfpaBtn(this, event);';
    $form->addField($newField);
  

    $button = new formInput('button', 'ifpaButton', 'Look me up!');
    $button->type = 'button';
    $button->disabled = true;
    $button->action = "ifpaReg('ifpaIdText', 'ifpaRegResults');";
    $form->addField($button);

    $button = new formInput('button', 'newButton', 'I\'m a new guy!');
    $button->type = 'button';
    $button->disabled = true;
    $button->action = "newGuy('ifpaRegResults');";
    $form->addField($button);

    $content .= $form->output();

    $content .= '
    <span id="loadingObjects">Objects are loading... please be patient...<img src="'.__baseHref__.'/images/sel-loader.gif" alt="Loading data..."></span>
    <span id="noHits" style="display: none;">No hits! Try to search for something else.</span>
    <form id="newData" name="newData">
      <input type="hidden" name="loggedIn" id="loggedIn" value="false">
      <input type="hidden" name="baseHref" id="baseHref" value="'.__baseHref__.'">
      <div id="ifpaRegResults">
      <span id="playerLoading" style="display: none"><img src="'.__baseHref__.'/images/ajax-loader.gif" alt="Loading data..."></span>
        <div id="ifpaRegResultsTableDiv" style="display: none">
          <h3 id="ifpaRegResultsH3">People found:</h3>
          <table id="ifpaRegResultsTable" class="list">
          </table>
        </div>
      </div>
    </form>
    ';
  
    echo($content);
    echo('<script type="text/javascript">document.getElementById(\'ifpaIdText\').focus();</script>');
  }

    printFooter($dbh, $ulogin);
?>
