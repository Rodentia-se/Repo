    $tournament = tournament('active');
    $persons = persons($tournament);
    $volunteers = volunteers($tournament);

        $prefix = 'players';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->data_title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
          $profileSelectDiv = ${$prefix.'Div'}->addDiv();
            $profileSelect = $profileSelectDiv->addContent($persons->getSelectObj($prefix.'Profile', NULL, 'Edit profile and photo'));
            $profileSelect->addCombobox();
            $profileSelect->addValueSpan('Person ID:');
            $editSections = array('edit', 'photo', 'admin');
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
          if ($personMailAddresses) {
            ${$prefix.'Div'}->addH2('Email addresses', array('class' => 'entry-title'))->addCss('margin-top', '15px');
            ${$prefix.'Div'}->addParagraph('Email addresses to all players that have registered their email address. Click in the box to copy the addresses to your clipboard.');
            ${$prefix.'Div'}->addParagraph(implode(', ', $personMailAddresses), $prefix.'mailAddresses', 'toCopy');
            ${$prefix.'Div'}->addParagraph('More coming soon...')->style = 'margin-top: 15px';
          }
        //Players
