<?php

  define('__ROOT__', dirname(dirname(dirname(__FILE__))));
  require_once(__ROOT__.'/functions/init.php');

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    $tournament = tournament('active');
    $persons = persons($tournament);
    $prefix = 'scores';
    $div = new div('scoresDiv');
      $div->data_title = "Scores";
      $selectDiv = $div->addDiv();
        $select = $selectDiv->addContent($persons->getSelectObj($prefix.'Profile', NULL, 'Edit scores'));
        $select->addCombobox();
        $select->addValueSpan('Person ID:');
      //selectDiv
      $scoresResultDiv = $div->addDiv('scoresResultDiv', NULL, array('data-title' => 'Player scores'));
      $select->addChange('
        var el = this;
        if ($(el).val() != 0) {
          $("#'.$scoresResultDiv->id.'").hide();
          $("body").addClass("modal");
          $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "person", type: "scores", id: $(this).val()})
          .done(function(data) {
            $("#'.$scoresResultDiv->id.'").html(data);
            $("body").removeClass("modal");
            $("#" + el.id + "Tabs").show();
          }
        });          
      ');
      $div->addFocus('#'.$select->id.'_combobox', TRUE);
    echo $div->getHtml();
  } else {
    echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
  }

?>