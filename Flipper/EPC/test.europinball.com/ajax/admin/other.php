<?php

  define('__ROOT__', dirname(dirname(dirname(__FILE__))));
  require_once(__ROOT__.'/functions/init.php');

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    $prefix = 'other';
    ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
      ${$prefix.'Div'}->data_title = ucfirst($prefix);
      ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
      $geoTabs = ${$prefix.'Div'}->addTabs(NULL, 'geoTabs');
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
      ${$prefix.'Div'}->addChange('
        $("#" + this.id + "IDSpan").html($(this).val());
      ', '.dupeSelect');
      ${$prefix.'Div'}->addClick('
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
    echo ${$prefix.'Div'}->getHtml();
  } else {
    echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
  }

?>