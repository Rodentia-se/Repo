<?php

  define('__ROOT__', dirname(dirname(dirname(__FILE__))));
  require_once(__ROOT__.'/functions/init.php');

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    $tournament = tournament('active');
    $prefix = 'games';
    ${$prefix.'Div'} = new div($prefix.'Div');
      ${$prefix.'Div'}->data_title = ucfirst($prefix);
      ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
      $games = games($tournament);
      ${$prefix.'SelectDiv'} = ${$prefix.'Div'}->addDiv();
        ${$prefix.'Select'} = ${$prefix.'SelectDiv'}->addContent($games->getSelectObj($prefix.'Games', NULL, 'Edit game ettings'));
        ${$prefix.'Select'}->addCombobox();
        ${$prefix.'Select'}->addValueSpan('Game ID:');
        ${$prefix.'Select'}->addChange('
          var el = this;
          $("#" + el.id + "EditDiv").hide();
          if ($(el).val() != 0) {
            $("body").addClass("modal");
            $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "game", type: "edit", id: $(el).val()})
            .done(function(data) {
              $("#" + el.id + "EditDiv").html(data);
              $("#" + el.id + "EditDiv").show();
              $(":button").button();
              $("body").removeClass("modal");
            });
          }
        ');
        ${$prefix.'Div'}->addFocus('#'.${$prefix.'Select'}->id.'_combobox', TRUE);
      //$usersSelectDiv
      ${$prefix.'Div'}->addDiv(${$prefix.'Select'}->id.'EditDiv');
      //$usersEditDiv
      $owners = owners('active');
      $mailAddresses = $owners->getListOf('mailAddress');
      if ($mailAddresses) {
        ${$prefix.'Div'}->addH2('Email addresses', array('class' => 'entry-title'))->addCss('margin-top', '15px');
        ${$prefix.'Div'}->addParagraph('Email addresses to all game owners that have registered their email address. Click in the box to copy the addresses to your clipboard.');
        ${$prefix.'Div'}->addParagraph(implode(', ', $mailAddresses), $prefix.'mailAddresses', 'toCopy');
      }
      ${$prefix.'Div'}->addParagraph('More coming soon...')->addCss('margin-top', '15px');
    //${$prefix.'Div'}
    echo ${$prefix.'Div'}->getHtml();
  } else {
    echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
  }

?>