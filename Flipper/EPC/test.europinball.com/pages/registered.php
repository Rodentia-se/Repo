<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register', true);
  
  if (isId($_REQUEST['tournament_id'])) {
    $tournament = tournament($_REQUEST['tournament_id']);
  }
  if (!$tournament) {
    $tournament = tournament(config::$activeTournament);
  }
  if (!$tournament) {
    error('No tournament found!', TRUE);
  }
  $divisions = $tournament->getDivisions();
  $divisions->filter('includeInStats');
  if (count($divisions) < 1) {
    error('No divisions found!', TRUE);
  }
  

  $page->addH2('Registered players and teams');
  $page->startDiv('tabs');
    $page->startUl();
      foreach ($divisions as $division) {
        $page->addLi('<a href="#'.$division->shortName.'">'.$division->divisionName.'</a>');
      }
    $page->closeUl();
    foreach ($divisions as $division) {
      $players = $division->getPlayers();
      $rows = array();
      $page->startDiv($division->shortName);
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
            $rows[] = $player->getRegRow();
          }
          $page->addTable($division->shortName.'Table', $headers, $rows, TRUE);
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