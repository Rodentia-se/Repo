<?php

  define('__ROOT__', dirname(dirname(dirname(__FILE__))));
  require_once(__ROOT__.'/functions/init.php');

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    
    $tournament = tournament('active');
    $persons = persons($tournament);
    $prefix = 'players';
    ${$prefix.'Div'} = new div($prefix.'Div');
      ${$prefix.'Div'}->data_title = ucfirst($prefix);
      ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
      $profileSelectDiv = ${$prefix.'Div'}->addDiv();
        $profileSelect = $profileSelectDiv->addContent($persons->getSelectObj($prefix.'Profile', NULL, 'Edit profile and photo'));
        $profileSelect->addCombobox();
        $profileSelect->addValueSpan('Person ID:');
        $editSections = array('edit', 'photo', 'admin', 'qr');
        foreach ($editSections as $editSection) {
          $editScripts .= '
            $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "person", type: "'.$editSection.'", id: $(this).val()})
            .done(function(data) {
              $("#" + el.id + "'.ucfirst($editSection).'Div").html(data);
              modals--;
              if (modals == 0) {
                $("body").removeClass("modal");
                $("#" + el.id + "Tabs").show();
              }
            });
          ';
        }
        $profileSelect->addChange('
          var el = this;
          $("#" + el.id + "Tabs").hide();
          if ($(el).val() != 0) {
            $("body").addClass("modal");
            var modals = '.count($editSections).';
            '.$editScripts.'
          }
        ');
        ${$prefix.'Div'}->addFocus('#'.$profileSelect->id.'_combobox', TRUE);
      //$profileSelectDiv
      $profileTabs = ${$prefix.'Div'}->addTabs(NULL, $prefix.'ProfileTabs', 'hidden');
        $profileEditDiv = $profileTabs->addDiv($profileSelect->id.'EditDiv', NULL, array('data-title' => 'Player profile'));
        //$profileEditDiv
        $photoEditDiv = $profileTabs->addDiv($profileSelect->id.'PhotoDiv', NULL, array('data-title' => 'Player photo'));
        //$photoEditDiv
        $adminEditDiv = $profileTabs->addDiv($profileSelect->id.'AdminDiv', NULL, array('data-title' => 'Admin settings'));
        $qrEditDiv = $profileTabs->addDiv($profileSelect->id.'QrDiv', NULL, array('data-title' => 'QR code'));
        $qrEditP = $qrEditDiv->addParagraph('Click to print. ');
        $qrEditP->addLink(config::$baseHref.'/pages/qr.php?class=person', 'Click here to print all codes.');
        $profileTabs->addCss('margin-top', '15px');
      //}$profileTabs
      ${$prefix.'Div'}->addH2('Waiting list', array('class' => 'entry-title'));
      foreach (config::$activeSingleDivisions as $division_id) {
        $division = division($division_id);
        $players = players($division);
        $waitingDiv = ${$prefix.'Div'}->addDiv();
          $waitingDiv->addLabel($division->divisionName.':');
          $waitingButton = $waitingDiv->addButton('Recalculate waiting list', $division->id.'calcWaiting');
          $waitingButton->addTooltip('');
          $waitingButton->addCss('margin-top', '15px');
          $waitingDiv->addLabel('Number of players:');
          $calcSpan = $waitingDiv->addSpan($players->getNumOf('waiting'));
          $waitingDiv->addSpan(' players');
          $waitingButton->addClick('
            var el = this;
            $(el).tooltipster("update", "Recalculating waiting list...").tooltipster("show");
            $.post("'.config::$baseHref.'/ajax/calcWaiting.php", {division_id: '.$division->id.'})
            .done(function(data) {
              $(el).tooltipster("update", data.reason).tooltipster("show");
              if (data.valid) {
                $("#'.$calcSpan->id.'").html(data.number);
              }
            }) 
          ');
        //$waitingDiv
      }
      $personMailAddresses = $persons->getListOf('mailAddress');
      $mainPlayers = players(division('main'));
      $inTournamentPersons = $mainPlayers->getFiltered('waiting', 0, '>', TRUE);
      $inTournamentMailAddresses = $inTournamentPersons->getListOf('mailAddress'); 
      if ($personMailAddresses || $inTournamentMailAddresses) {
        ${$prefix.'Div'}->addH2('Email addresses', array('class' => 'entry-title'))->addCss('margin-top', '15px');
        ${$prefix.'Div'}->addParagraph('Note: Players that haven\'t registered their email address are not included. Click in the box to copy the addresses to your clipboard.', NULL, 'italic');
        $personMailTabs = ${$prefix.'Div'}->addTabs(NULL, 'personMailTabs');
      }
      if ($inTournamentMailAddresses) {
        $inTournamentMailDiv = $personMailTabs->addDiv('inTournamentMailDiv', NULL, array('data-title' => 'Players in the tournament'));
          $inTournamentMailDiv->addParagraph('Email addresses to the '.config::$participationLimit['main'].' players that are in the tournament, i.e. NOT on the waiting list');
          $inTournamentMailDiv->addParagraph(implode(', ', $inTournamentMailAddresses), $prefix.'inTournamentMailAddresses', 'toCopy');
        //}
      }
      if ($personMailAddresses) {
        $personMailDiv = $personMailTabs->addDiv('personMailDiv', NULL, array('data-title' => 'All registered players'));
          $personMailDiv->addParagraph('Email addresses to all players that have registered an email address, no matter if they are in the tournament or not;');
          $personMailDiv->addParagraph(implode(', ', $personMailAddresses), $prefix.'mailAddresses', 'toCopy');
        //}
      }
      echo ${$prefix.'Div'}->getHtml();
    //Players
  } else {
    echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
  }
  
?>