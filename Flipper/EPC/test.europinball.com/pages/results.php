<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $type = ($type) ? $type : (($_REQUEST['obj']) ? $_REQUEST['obj'] : 'players');
  $type = (isGroup($type, TRUE)) ? $type : ((isObj($type, TRUE)) ? $type::$arrClass : 'players');

  if (isId($_REQUEST['tournament_id'])) {
    $tournament = tournament($_REQUEST['tournament_id']);
  }
  if (!$tournament) {
    $tournament = tournament(config::$activeTournament);
  }
  if (!$tournament) {
    $tournament = getTournament();
  }
  if (!$tournament) {
    error('No tournament found!', NULL, FALSE, TRUE);
  }
  $divisions = divisions($tournament);
  if (!$divisions || count($divisions) < 1) {
    error('No divisions found!', NULL, FALSE, TRUE);
  }

  switch ($type) {
    case 'players':
      $title = 'players and teams';
      $divisions->filter('includeInStats');
    break;
    case 'persons':
      $title = 'players';
    break;
    case 'teams':
      $title = 'teams';
      $divisions->filter('includeInStats');
    break;
    case 'games':
    case 'machines':
      $title = 'games';
    break;
    case 'manufacturers':
      $title = 'game manufacturers';
    break;
    default:
      $title = $type;
    break;
  }

  $page = new page('Qualification results ');
  
  $page->addH2('Results');
  $page->startDiv('tabs');
    $page->startUl();
      foreach ($divisions as $division) {
        $objs[$division->id] = $type($division);
        $objs[$division->id]->filter('waiting', 0, '>', TRUE);
        if (count($objs[$division->id]) > 0 || config::$showEmptyDivisions) {
          $page->addLi('<a href="'.config::$baseHref.'/ajax/getStandings.php?class=division&id='.$division->id.'">'.$division->divisionName.'</a>');
        }
      }
    $page->closeUl();
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
  $page->closeDiv();
  
  $page->submit();

?>