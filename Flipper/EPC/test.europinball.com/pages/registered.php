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

  $page->addH2('Registered players and teams');
  $page->startDiv('tabs');
    $page->startUl();
      foreach ($divisions as $division) {
        $page->addLi('<a href="'.$division->shortName.'">'.$division->divisionName.'</a>');
      }
    $page->closeUl();
    foreach ($divisions as $division) {
      $page->startDiv($division->shortName);
        if ($division->isTeam) {
          if ($division->isNational) {
            $headers = array('Name', 'Tag', 'Country', 'Members', 'Picture');
          } else {
            $headers = array('Name', 'Tag', 'Members', 'Picture');
          }
        } else {
          $headers = array('Name', 'Tag', 'City', 'Region', 'Country', 'IFPA', 'Picture');
        }
        $page->addTable($division->shortName.'Table', $headers, NULL, TRUE);
      $page->closeDiv();
    }
  $page->closeDiv();
  $page->addScript('
    $("#tabs").tabs();
  ');
  
  $page->submit();

?>