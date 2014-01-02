<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Admin tools');

  if ($page->reqLogin('You need to be logged in to access this page. If you don\'t have a user, please go to the <a href="'.config::$baseHref.'/registration/">registration page</a>.')) {
    $volunteer = volunteer('login');
    $persons = persons(tournament('active'));
    $adminDiv = new div('adminDiv');
    $loading = $adminDiv->addLoading();
    if ($volunteer->admin) {
      $tabs = $adminDiv->addTabs(NULL, 'adminTabs');
        $prefix = 'players';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->title, array('class' => 'entry-title'));
          $profileSelectDiv = ${$prefix.'Div'}->addDiv();
            $profileSelect = $profileSelectDiv->addContent($persons->getSelectObj($prefix.'Profile', NULL, 'Edit profile'));
            $profileSelect->addCombobox();
            $profileSelect->addValueSpan('Person ID:');
            $profileSelect->addChange('
              var el = this;
              $("#" + el.id + "Tabs").hide();
              if ($(el).val() != 0) {
                $("body").addClass("modal");
                var modals = 0;
                $.post("'.config::$baseHref.'/ajax/getPlayers.php", {obj: "person", type: "edit", id: $(this).val()})
                .done(function(data) {
                  $("#" + el.id + "EditDiv").html(data);
                  modals++;
                  if (modals == 2) {
                    $("body").removeClass("modal");
                    $("#" + el.id + "Tabs").show();
                  }
                });
                $.post("'.config::$baseHref.'/ajax/getPlayers.php", {obj: "person", type: "photo", id: $(this).val()})
                .done(function(data) {
                  $("#" + el.id + "PhotoDiv").html(data);
                  modals++;
                  if (modals == 2) {
                    $("body").removeClass("modal");
                    $("#" + el.id + "Tabs").show();
                  }
                });
              }
            ');
            ${$prefix.'Div'}->addFocus('#'.$profileSelect->id.'_combobox', TRUE);
          //$profileSelectDiv
          $profileTabs = ${$prefix.'Div'}->addTabs(NULL, $prefix.'ProfileTabs', 'hidden');
            $profileEditDiv = $profileTabs->addDiv($profileSelect->id.'EditDiv');
            //$profileEditDiv
            $profileEditDiv = $profileTabs->addDiv($profileSelect->id.'PhotoDiv');
            //$photoSelectDiv
            $profileTabs->addCss('margin-top', '15px');
          //}$profileTabs
        $waitingDiv = ${$prefix.'Div'}->addDiv();
            $waitingButton = $waitingDiv->addButton('Recalculate waiting list');
            $waitingButton->addTooltip('');
            $waitingButton->addClick('
              var el = this;
              $(el).tooltipster("update", "Recalculating waiting list...").tooltipster("show");
              $.post("'.config::$baseHref.'/ajax/calcWaiting.php", {})
              .done(function(data) {
                $(el).tooltipster("update", data.reason).tooltipster("show");
              })
            ');
            $waitingButton->addCss('margin-top', '15px');
          //$waitingDiv
          ${$prefix.'Div'}->addH2('Email addresses', array('class' => 'entry-title'))->addCss('margin-top', '15px');
          ${$prefix.'Div'}->addParagraph('Email addresses to all players that have registered their email address can be copied from here: ');
          $mailAddresses = $persons->array_map(function($person){
            if ($person->mailAddress) {
              return $person->mailAddress;
            }
          });
          ${$prefix.'Div'}->addParagraph(implode(', ', array_filter($mailAddresses)), $prefix.'mailAddresses', 'toCopy');
          ${$prefix.'Div'}->addParagraph('More coming soon...')->style = 'margin-top: 15px';
        //${$prefix.'Div'}
        $prefix = 'users';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->title, array('class' => 'entry-title'));
          ${$prefix.'Div'}->addParagraph('Coming soon...');
        //${$prefix.'Div'}
        $paymentDiv = $tabs->addDiv('paymentDiv');
          $prefix = 'payment';
          $paymentDiv->title = 'Payments';
          $paymentDiv->addH2($paymentDiv->title, array('class' => 'entry-title'));
          $paymentSelect = $paymentDiv->addContent($persons->getSelectObj($prefix.'Persons', NULL, 'Persons'));
            $paymentSelect->addCombobox();
            $paymentDiv->addFocus('#'.$paymentSelect->id.'_combobox', TRUE);
          //$paymentSelect
          $paidDiv = $paymentDiv->addDiv('paidDiv', 'noInput');
            $paidDiv->addLabel('Paid:');
            $paidSpan = $paidDiv->addMoneySpan(0, 'paid', config::$currencies[config::$defaultCurrency]['format']);
          //$paidDiv
          $costsDiv = $paymentDiv->addDiv('costsDiv');
            $costsDiv->addLabel('Should pay:');
            $costsSpan = $costsDiv->addMoneySpan(0, 'costs', config::$currencies[config::$defaultCurrency]['format']);
          //$costsDiv
          $payDiv = $paymentDiv->addDiv('payDiv');
            $payDiv->addLabel('Left to pay:');
            $paySpan = $payDiv->addMoneySpan(0, 'pay', config::$currencies[config::$defaultCurrency]['format']);
            $paySpan->addClasses('sum');
          //$payDiv
          $setDiv = $paymentDiv->addDiv();
            $setPaid = $setDiv->addInput('setPaid', 0, 'text', 'Set paid total', array('class' => 'short'));
            $setPaid->disabled = TRUE;
            $setPaid->addChange('
              var el = this;
              $("body").addClass("modal");
              $.post("'.config::$baseHref.'/ajax/setPersonProp.php", {person_id: $("#'.$paymentSelect->id.'").val(), prop: "paid", value: $(el).val()})
              .done(function(data) {
                if (data.valid) {
                  $("#'.$paymentSelect->id.'").change();
                  setTimeout(function() {
                    $("#'.$paymentSelect->id.'_combobox").focus().select()
                  }, 500);
                } else {
                  showMsg(data.reason);
                }
              });
            ');
          //$setDiv
          $paymentSelect->addChange('
            $("body").addClass("modal");
            var num = 3;
            $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "person", id: $(this).val(), prop: "paid"})
            .done(function(data) {
              num--;
              if (num == 0) {
                $("body").removeClass("modal");
              }
              if (data.valid) {
                $("#'.$paidSpan->id.'").html(parseInt(data.reason).toMoney(0, ".", " ", "", "'.config::$currencies[config::$defaultCurrency]['format'].'"));
                $("#'.$setPaid->id.'").val(data.reason).focus().select().prop("disabled", false);
              } else {
                showMsg(data.reason);
              }
            });
            $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "person", id: $(this).val(), prop: "costs"})
            .done(function(data) {
              num--;
              if (num == 0) {
                $("body").removeClass("modal");
              }
              if (data.valid) {
                $("#'.$costsSpan->id.'").html(parseInt(data.reason).toMoney(0, ".", " ", "", "'.config::$currencies[config::$defaultCurrency]['format'].'"));
              } else {
                showMsg(data.reason);
              }
            });
            $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "person", id: $(this).val(), prop: "toPay"})
            .done(function(data) {
              num--;
              if (num == 0) {
                $("body").removeClass("modal");
              }
              if (data.valid) {
                $("#'.$paySpan->id.'").html(parseInt(data.reason).toMoney(0, ".", " ", "", "'.config::$currencies[config::$defaultCurrency]['format'].'"));
              } else {
                showMsg(data.reason);
              }
            });
          ');
        //}
        $prefix = 'teams';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->title, array('class' => 'entry-title'));
          ${$prefix.'Div'}->addParagraph('Coming soon...');
        //${$prefix.'Div'}
        $prefix = 'groups';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->title, array('class' => 'entry-title'));
          ${$prefix.'Div'}->addParagraph('Coming soon...');
        //${$prefix.'Div'}
        $prefix = 'games';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->title, array('class' => 'entry-title'));
          ${$prefix.'Div'}->addParagraph('Coming soon...');
          $owners = owners('active');
          ${$prefix.'Div'}->addH2('Email addresses', array('class' => 'entry-title'))->addCss('margin-top', '15px');
          ${$prefix.'Div'}->addParagraph('Email addresses to all game owners that have registered their email address can be copied from here: ');
          $mailAddresses = $owners->array_map(function($person){
            if ($person->mailAddress) {
              return $person->mailAddress;
            } else if ($person->contactPerson->mailAddress) {
              return $person->contactPerson->mailAddress;
            }
          });
          ${$prefix.'Div'}->addParagraph(implode(', ', array_filter($mailAddresses)), $prefix.'mailAddresses', 'toCopy');
          ${$prefix.'Div'}->addParagraph('Coming soon...');
        //${$prefix.'Div'}
        $prefix = 'scores';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->title, array('class' => 'entry-title'));
          ${$prefix.'Div'}->addParagraph('Coming soon...');
        //${$prefix.'Div'}
        $prefix = 'results';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->title, array('class' => 'entry-title'));
          ${$prefix.'Div'}->addParagraph('Coming soon...');
        //${$prefix.'Div'}
        $prefix = 'volunteers';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->title, array('class' => 'entry-title'));
          $volunteers = volunteers('active');
          ${$prefix.'Div'}->addH2('Email addresses', array('class' => 'entry-title'))->addCss('margin-top', '15px');
          ${$prefix.'Div'}->addParagraph('Email addresses to all volunteers that have registered their email address can be copied from here: ');
          $mailAddresses = $volunteers->array_map(function($person){
            if ($person->mailAddress) {
              return $person->mailAddress;
            }
          });
          ${$prefix.'Div'}->addParagraph(implode(', ', array_filter($mailAddresses)), $prefix.'mailAddresses', 'toCopy');
          ${$prefix.'Div'}->addParagraph('Coming soon...');
        //${$prefix.'Div'}
        $prefix = 't-shirts';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->title, array('class' => 'entry-title'));
          $tshirtOrders = tshirtOrders('active');
          ${$prefix.'Div'}->addH2('Email addresses', array('class' => 'entry-title'))->addCss('margin-top', '15px');
          ${$prefix.'Div'}->addParagraph('Email addresses to all players that have chosen their T-shirts and registered their email address can be copied from here: ');
          $mailAddresses = $tshirtOrders->array_map(function($order){
            if ($order->person->mailAddress) {
              return $order->person->mailAddress;
            }
          });
          ${$prefix.'Div'}->addParagraph(implode(', ', array_filter($mailAddresses)), $prefix.'mailAddresses', 'toCopy');
          ${$prefix.'Div'}->addParagraph('Coming soon...');
        //${$prefix.'Div'}
        $otherDiv = $tabs->addDiv('otherDiv');
          $otherDiv->title = 'Other';
          $otherDiv->addH2($otherDiv->title, array('class' => 'entry-title'));
          $geoTabs = $otherDiv->addTabs(NULL, 'geoTabs');
            foreach (array('city', 'region') as $geoClass) {
              $arrClass = $geoClass::$arrClass;
              $geoDiv = $geoTabs->addDiv($arrClass.'Div');
                $objs = $arrClass('all');
                $geoDiv->addH2('Merge '.$geoClass.' duplicates', array('class' => 'entry-title'));
                  $actionSel['Remove'] = $objs->getSelectObj($arrClass.'DupesRemove', NULL, 'Remove this '.$geoClass.':', array('class' => 'dupeSelect '.$geoClass.'Select'));
                  $geoDiv->addFocus('#'.$actionSel['Remove']->id.'_combobox', TRUE);
                  $actionSel['Keep'] = new select($arrClass.'DupesKeep', NULL, NULL, 'Keep this '.$geoClass.':', array('class' => 'dupeSelect '.$geoClass.'Select'));
                  $actionSel['Keep']->contents = $actionSel['Remove']->contents;
                  $actionSel['Keep']->escape = FALSE;
                  foreach(array('Remove', 'Keep') as $action) {
                    $actionDiv = $geoDiv->addDiv();
                      $actionSel[$action]->addCombobox();
                      $actionDiv->addContent($actionSel[$action]);
                      $actionDiv->addLabel(ucfirst($geoClass).' ID:', NULL, NULL, 'short');
                      $actionDiv->addSpan('none', $arrClass.'Dupes'.$action.'IDSpan');
                    //$actionDiv
                  } 
                //$geoDiv
                $geoDiv->addLabel(' ');
                $mergeButton = $geoDiv->addButton('Merge', $geoClass.'MergeButton', array('class' => 'mergeButton'));
                $mergeButton->{'data-geoclass'} = $geoClass;
                $mergeButton->{'data-arrclass'} = $arrClass;
                $tooltip = $mergeButton->addTooltip('');
                $tooltip->timer = 8000;
                $geoDiv->addParagraph('Anything now related to the first '.$geoClass.' will be changed to be related to the second '.$geoClass.' when you click the button. Properties from the first city will be transfered to the second city only if the property is empty for the second city.', NULL, 'italic');
              //$geoDiv
            } 
          //$geoTabs
          $otherDiv->addChange('
            $("#" + this.id + "IDSpan").html($(this).val());
          ', '.dupeSelect');
          $otherDiv->addClick('
            var el = this;
            var geoClass = $(this).data("geoclass");
            var arrClass = $(this).data("arrclass");
            var $removeSel = $("#" + arrClass + "DupesRemove");
            var $keepSel = $("#" + arrClass + "DupesKeep");
            if ($removeSel.val() && $keepSel.val) {
              $("body").addClass("modal");
              $(el).tooltipster("update", "Merging " + arrClass + "...").tooltipster("show");
              $.post("'.config::$baseHref.'/ajax/geoMerge.php", {obj: geoClass, remove: $removeSel.val(), keep: $keepSel.val()})
              .done(function(data) {
                $(el).tooltipster("update", data.reason).tooltipster("show").tooltipster("timer", 15000);
                if (data.valid) {
                  $("." + geoClass + "Select option[value=\'" + $removeSel.val() + "\']").each(function() {
                    $(this).remove();
                  });
                  $removeSel.val(0);
                  $keepSel.val(0);
                  $("#" + arrClass + "DupesRemoveIDSpan").html("None");
                  $("#" + arrClass + "DupesKeepIDSpan").html("None");
                  $("#" + arrClass + "DupesRemove_combobox").val("Choose...");
                  $("#" + arrClass + "DupesKeep_combobox").val("Choose...");
                  $("#" + arrClass + "DupesRemove_combobox").focus();
                  $("#" + arrClass + "DupesRemove_combobox").select();
                }
                $("body").removeClass("modal");
              })
            } else {
              $(el).tooltipster("update", "Please choose " + arrClass + " to remove and to keep...").tooltipster("show");
            }
          ', '.mergeButton');
        //$otherDiv
      //$tabs
      $page->addContent($adminDiv);
    } else {
      $paragraph = new paragraph('You need to be an administrator to access this page. Please logout and log back in as administrator.');
      $page->addContent($paragraph);
    }
  }
  $page->submit();

?>