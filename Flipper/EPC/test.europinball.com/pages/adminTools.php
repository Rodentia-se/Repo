<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Admin tools');

  if ($page->reqLogin('You need to be logged in to access this page. If you don\'t have a user, please go to the <a href="'.config::$baseHref.'/registration/">registration page</a>.')) {
    $volunteer = volunteer('login');
    $persons = persons(tournament('active'));
    $personsSel = $persons->getSelectObj();
    $adminDiv = new div('adminDiv');
    $loading = $adminDiv->addLoading();
    if ($volunteer->admin) {
      $tabs = $adminDiv->addTabs(NULL, 'adminTabs');
        $prefix = 'players';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->title, array('class' => 'entry-title'));
          $profileSelect = ${$prefix.'Div'}->addContent($personsSel->getClone('Edit profile', $prefix.'Profile'));
          $profileSelect->addCombobox();
          ${$prefix.'Div'}->addFocus('#'.$paymentSelect->id.'_combobox', TRUE);
          $profileSelect->addIdSpan('Person ID:');
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
          ${$prefix.'Div'}->addParagraph('More coming soon...')->style = 'margin-top: 15px';
        //}
        $userDiv = $tabs->addDiv('userDiv');
          $userDiv->title = 'Users';
          $userDiv->addH2($userDiv->title, array('class' => 'entry-title'));
          $userDiv->addParagraph('Coming soon...');
        //}
        $paymentDiv = $tabs->addDiv('paymentDiv');
          $prefix = 'payment';
          $paymentDiv->title = 'Payments';
          $paymentDiv->addH2($paymentDiv->title, array('class' => 'entry-title'));
            $paymentSelect = $paymentDiv->addContent($personsSel->getClone('Persons', $prefix.'Persons'));
            $paymentSelect->addCombobox();
            $paymentDiv->addFocus('#'.$paymentSelect->id.'_combobox', TRUE);
          //}
          $paidDiv = $paymentDiv->addDiv('paidDiv', 'noInput');
            $paidDiv->addLabel('Paid:');
            $paidSpan = $paidDiv->addMoneySpan(0, 'paid', config::$currencies[config::$defaultCurrency]['format']);
          //}
          $costsDiv = $paymentDiv->addDiv('costsDiv');
            $costsDiv->addLabel('Should pay:');
            $costsSpan = $costsDiv->addMoneySpan(0, 'costs', config::$currencies[config::$defaultCurrency]['format']);
          //}
          $payDiv = $paymentDiv->addDiv('payDiv');
            $payDiv->addLabel('Left to pay:');
            $paySpan = $payDiv->addMoneySpan(0, 'pay', config::$currencies[config::$defaultCurrency]['format']);
            $paySpan->addClasses('sum');
          //}
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
          //}
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
        $teamDiv = $tabs->addDiv('teamDiv');
          $teamDiv->title = 'Teams';
          $teamDiv->addH2($teamDiv->title, array('class' => 'entry-title'));
          $teamDiv->addParagraph('Coming soon...');
        //}
        $qualGroupsDiv = $tabs->addDiv('qualGroupsDiv');
          $qualGroupsDiv->title = 'Groups';
          $qualGroupsDiv->addH2($qualGroupsDiv->title, array('class' => 'entry-title'));
          $qualGroupsDiv->addParagraph('Coming soon...');
        //}
        $gameDiv = $tabs->addDiv('gameDiv');
          $gameDiv->title = 'Games';
          $gameDiv->addH2($gameDiv->title, array('class' => 'entry-title'));
          $gameDiv->addParagraph('Coming soon...');
        //}
        $scoresDiv = $tabs->addDiv('scoresDiv');
          $scoresDiv->title = 'Scores';
          $scoresDiv->addH2($scoresDiv->title, array('class' => 'entry-title'));
          $scoresDiv->addParagraph('Coming soon...');
        //}
        $resultsDiv = $tabs->addDiv('resultsDiv');
          $resultsDiv->title = 'Results';
          $resultsDiv->addH2($resultsDiv->title, array('class' => 'entry-title'));
          $resultsDiv->addParagraph('Coming soon...');
        //}
        $volDiv = $tabs->addDiv('volDiv');
          $volDiv->title = 'Volunteers';
          $volDiv->addH2($volDiv->title, array('class' => 'entry-title'));
          $volDiv->addParagraph('Coming soon...');
        //}
        $tshirtDiv = $tabs->addDiv('tshirtDiv');
          $tshirtDiv->title = 'T-shirts';
          $tshirtDiv->addH2($tshirtDiv->title, array('class' => 'entry-title'));
          $tshirtDiv->addParagraph('Coming soon...');
        //}
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
                      //}
                    } 
                  //}
                  $geoDiv->addLabel(' ');
                  $mergeButton = $geoDiv->addButton('Merge', $geoClass.'MergeButton', array('class' => 'mergeButton'));
                  $mergeButton->{'data-geoclass'} = $geoClass;
                  $mergeButton->{'data-arrclass'} = $arrClass;
                  $tooltip = $mergeButton->addTooltip('');
                  $tooltip->timer = 8000;
                  $geoDiv->addParagraph('Anything now related to the first '.$geoClass.' will be changed to be related to the second '.$geoClass.' when you click the button. Properties from the first city will be transfered to the second city only if the property is empty for the second city.', NULL, 'italic');
                //}
              } 
            //}
          //}
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
        //}
      //}
      $page->addContent($adminDiv);
    } else {
      $paragraph = new paragraph('You need to be an administrator to access this page. Please logout and log back in as administrator.');
      $page->addContent($paragraph);
    }
  }
  $page->submit();

?>