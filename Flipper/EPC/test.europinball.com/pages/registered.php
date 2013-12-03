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
  debug($divisions);
  $divisions->filter('includeInStats');
  debug($divisions);
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
      $page->startDiv($division->shortName);
        if ($division->team) {
          if ($division->national) {
            $headers = array('Name', 'Tag', 'Country', 'Members', 'Picture');
          } else {
            $headers = array('Name', 'Tag', 'Members', 'Picture');
          }
          $players = $division->getTeams();
        } else {
          $headers = array('Name', 'Tag', 'City', 'Region', 'Country', 'Continent', 'IFPA', 'Picture');
          $players = $division->getPlayers();
          foreach ($players as $player) {
            $row = array();
            $row[] = array(
              $player->getLink(),
              $player->shortName,
              (is_object($player->city)) ? $player->city->getLink() : $player->cityName,
              (is_object($player->region)) ? $player->region->getLink() : $player->regionName,
              (is_object($player->country)) ? $player->country->getLink() : $player->countryName,
              (is_object($player->continent)) ? $player->continent->getLink() : $player->continentName,
              $player->getLink('ifpa'),
              $player->getLink('photo')
            );
          }
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