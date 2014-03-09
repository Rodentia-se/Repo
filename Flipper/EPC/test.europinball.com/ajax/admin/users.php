    $volunteers = volunteers($tournament);

        $prefix = 'users';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->data_title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
          ${$prefix.'SelectDiv'} = ${$prefix.'Div'}->addDiv();
            ${$prefix.'Select'} = ${$prefix.'SelectDiv'}->addContent($persons->getSelectObj($prefix.'Persons', NULL, 'Edit user settings'));
            ${$prefix.'Select'}->addCombobox();
            ${$prefix.'Select'}->addValueSpan('Person ID:');
            ${$prefix.'Select'}->addChange('
              var el = this;
              $("#" + el.id + "EditDiv").hide();
              if ($(el).val() != 0) {
                $("body").addClass("modal");
                $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "person", type: "'.$prefix.'", id: $(el).val()})
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
          $adminLevels = adminLevels('all');
          $vols = clone $volunteers;
          foreach ($adminLevels as $adminLevel) {
            if ($adminLevel->id > 15) {
              $vols[$adminLevel->id] = $vols->getFiltered($adminLevel->name);
              $volAddresses[$adminLevel->id] = $vols->getListOf('mailAddress');
            }
            if ($volAddresses[$adminLevel->id]) {
              $volAddressFound = TRUE;
            }
          }
          if ($volAddressFound) {
            ${$prefix.'Div'}->addH2('Email addresses', array('class' => 'entry-title'))->addCss('margin-top', '15px');
            ${$prefix.'MailTabs'} = ${$prefix.'Div'}->addTabs(NULL, $prefix.'MailTabs');
          }
          foreach ($adminLevels as $adminLevel) {
            if ($volAddresses[$adminLevel->id]) {
              ${$prefix.'MailDiv'}[$adminLevel->id] = ${$prefix.'MailTabs'}->addDiv($prefix.'MailDiv_'.$adminLevel->id, NULL, array('data-title' => ucfirst($adminLevel->name)));
                ${$prefix.'MailDiv'}[$adminLevel->id]->addH2(ucfirst($adminLevel->name).' email addresses', array('class' => 'entry-title'));
                ${$prefix.'MailDiv'}[$adminLevel->id]->addParagraph('Email addresses to all volunteers with level '.$adminLevel->id.' or higher that have registered their email address. Click in the box to copy the addresses to your clipboard.');
                ${$prefix.'MailDiv'}[$adminLevel->id]->addParagraph(implode(', ', $volAddresses[$adminLevel->id]), $prefix.'volAddresses_'.$adminLevel->id, 'toCopy');
              //${$prefix.'MailDiv'}
            }
          }
          ${$prefix.'Div'}->addParagraph('More coming soon...')->addCss('margin-top', '15px');
        //Users
