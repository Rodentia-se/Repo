<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
          $page->datatables = TRUE;
          $page->datatablesReload = TRUE;
  
  if (isId($_REQUEST['tournament_id'])) {
    $tournament = tournament($_REQUEST['tournament_id']);
  }
  if (!$tournament) {
    $tournament = tournament(config::$activeTournament);
  }
  if (!$tournament) {
    error('No tournament found!', NULL, FALSE, TRUE);
  }
  $divisions = divisions($tournament);
  $divisions->filter('includeInStats');
  if (count($divisions) < 1) {
    error('No divisions found!', NULL, FALSE, TRUE);
  }
  

  $page->addH2('Registered players and teams');
  $page->startDiv('tabs');
    $page->startUl();
      foreach ($divisions as $division) {
        $page->addLi('<a href="#'.$division->shortName.'Players">'.$division->divisionName.'</a>');
      } 
    $page->closeUl();
    foreach ($divisions as $division) {
      $players = players($division);
      $rows = array();
      $page->startDiv($division->shortName."Players");
        if (count($players) > 0) {
          if ($division->team) { 
            if ($division->national) {
              $headers = array('Name', 'Tag', 'Country', 'Members', 'Picture');
            } else {
              $headers = array('Name', 'Tag', 'Members', 'Picture');
            }
          } else {
            $headers = array('Name', 'Tag', 'City', 'Region', 'Country', 'Continent', 'IFPA', 'Picture');
          }
          foreach ($players as $player) {
            $rows[] = $player->getRegRow(TRUE);
          }
          $page->addParagraph('<input type="button" id="'.$division->shortName.'_reloadButton" class="reloadButton" value="Reload the table">');
          $page->addTable($division->shortName.'Table', $headers, $rows, 'regTable');
          $page->addScript('
            var tbl = [];
            tbl["'.$division->shortName.'"] = $("#'.$division->shortName.'Table").dataTable({
              "bProcessing": true,
              "bDestroy": true,
              "bJQueryUI": true,
          	  "sPaginationType": "full_numbers",
              "oLanguage": {
                "sProcessing": "<img src=\"'.config::$baseHref.'/images/ajax-loader.gif\" alt=\"Loading data...\">"
              },
              "iDisplayLength": -1,
              "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
            });
            $("#'.$division->shortName.'_reloadButton").click(function() {
              tbl["'.$division->shortName.'"].fnReloadAjax("'.config::$baseHref.'/ajax/getPlayers.php?type=registered&obj=division&id='.$division->id.'");
            });
          ');
        } else {
          $page->addParagraph('No players have registered for the '.$division->divisionName);
        }
      $page->closeDiv();
    }
  $page->closeDiv();
  $page->addScript('
    var index = "key";
    var dataStore = window.sessionStorage;
    try {
      var oldIndex = dataStore.getItem(index);
    } catch(e) {
      var oldIndex = 0;
    }
    $("#tabs").tabs({
      active: oldIndex,
      activate: function(event, ui) {
        var newIndex = ui.newTab.parent().children().index(ui.newTab);
        dataStore.setItem(index, newIndex) 
      }
    });
  ');
  
  $page->submit();

?>