<?php
  require_once('functions/general.php');
  require_once('functions/header.php');
  printHeader('EPC 2013');
  printTopper("getObjects('geo');document.getElementById('ifpaIdText').focus()");

  $form = new regForm();
  $form->id = 'reg';
  $form->table = false;
  $newField = new formInput('hidden', 'dateRegistered', date('Y-m-d'));
  $form->addField($newField);
  $content = $form->output();

  $geoComment = new formInput('comment', 'ifpaComment', 'We might already know who you are! Enter your IFPA ID (visible in the address bar when you look at your IFPA page), your email address or phone number used for SO, SM or EPC registrations in the past, your first, last, middle, partial or full name (more than three letters) or even your three-letter TAG (include trailing spaces). Then press the button (or enter/return) and feel the magic. If we can\'t find you, just try another sarch - we\'ve got 20 000 friends, and you\'re most probably one of them.<br /><br />');
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

  $content = $form->output();

  $content .= '
    <form id="newData" name="newData">
      <div id="ifpaRegResults" style="display: none">
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

  printFooter();
?>
