<?php

  define('__ROOT__', dirname(dirname(dirname(__FILE__))));
  require_once(__ROOT__.'/functions/init.php');

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    $tournament = tournament('active');
    $persons = persons($tournament);
    $prefix = 'scores';

    ${$prefix.'Div'} = new div($prefix.'Div');
      ${$prefix.'Div'}->data_title = ucfirst($prefix);
      ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
      $profileSelectDiv = ${$prefix.'Div'}->addDiv();
        $profileSelect = $profileSelectDiv->addContent($persons->getSelectObj($prefix.'Profile', NULL, 'Edit scores'));
        $profileSelect->addCombobox();
        $profileSelect->addValueSpan('Person ID:');
        $editSections = array('scores');
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
    echo ${$prefix.'Div'}->getHtml();
  } else {
    echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
  }

?>