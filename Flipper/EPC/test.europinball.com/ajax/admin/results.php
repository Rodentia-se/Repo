<?php

  define('__ROOT__', dirname(dirname(dirname(__FILE__))));
  require_once(__ROOT__.'/functions/init.php');

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    $tournament = tournament('active');
    $persons = persons($tournament);
    $prefix = 'results';
    $div = new div('resultsDiv');
      $div->addH2('Edit results')->addClasses('entry-title');
      $div->addParagraph('<b>Place</b>: This is the final place in the tournament. Four players on a tied 5th place should all get 5 here.')->escape = FALSE;
      $div->addParagraph('<b>WPPR</b>: This is the place reported to IFPA, with the average place rounded up. Four players on a tied 5th place should all get 6 here.')->escape = FALSE;
      $tabs = $div->addTabs(NULL, 'groupTabs');
      $personDiv = $tabs->addDiv('Specific person');
      $personDiv->data_title('Specific person');
      $selectDiv = $personDiv->addDiv();
        $select = $selectDiv->addContent($persons->getSelectObj('scoresSelect', NULL, 'Edit results for:'));
        $select->addCombobox();
        $select->addValueSpan('Person ID:');
      //selectDiv
      $resultsPersonDiv = $personDiv->addDiv('resultsPersonDiv', NULL, array('data-title' => 'Player results'));
      $select->addChange('
        var el = this;
        if ($(el).val() != 0) {
          $("#'.$resultsPersonDiv->id.'").hide();
          $("body").addClass("modal");
          $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "person", type: "resultsEdit", id: $(el).val()})
          .done(function(data) {
            $("#'.$resultsPersonDiv->id.'").html(data);
            $("body").removeClass("modal");
            $("#'.$resultsPersonDiv->id.'").show();
          });
        }          
      ');
      $div->addFocus('#'.$select->id.'_combobox', TRUE);
      foreach (config::$activeDivisions as $divisionId) {
        $division = division($divisionId);
        $divisionDiv[$divisionId] = $tabs->addDiv($division->divisionName);
        $players = players($division);
        $players->order('place', 'numeric', 'desc');
        foreach ($players as $player) {
          $divisionDiv[$divisionId]->addContent($player->getEdit('resultsEdit'));
        }
      }
    echo $div->getHtml();
  } else {
    echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
  }

?>