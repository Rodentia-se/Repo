<?php

  class manufacturer extends base {
    
    public $link;
    public $games = array();
    
    public $class = 'manufacturer';
    
    public function getNoOfGames($dbh, $tournament = 1, $division = false) {
      $query = '
        select
          count(m.id)
        from machine m
        where m.manufacturer_id = '.$this->id;
      $query .= ($tournament) ? ' and m.tournamentEdition_id = '.$tournament : '';
      $query .= ($division) ? ' and m.tournamentDivision_id = '.$division : '';
      $sth = $dbh->query($query);
      $return = $sth->fetchColumn();
      return ($return) ? $return : 0;
    }
    
    public function getNoOfMainGames($dbh, $tournament = 1) {
      return $this->getNoOfGames($dbh, $tournament, 1);
    }
    
    public function getNoOfClassicsGames($dbh) {
      return $this->getNoOfGames($dbh, $tournament, 2);
    }
    
    public function getNoOfItems($dbh, $class, $tournament = 1) {
      global $classes;
      if ($class == 'game') {
        return getNoOfGames($dbh, $tournament);
      } else {
        return false;
      }
    }
    
    public function getStats($dbh, $tournament = 1, $division = false) {
      $stats['game'] = $this->getNoOfGames($dbh, $tournament);
      $stats['main'] = $this->getNoOfGames($dbh, $tournament, 1);
      $stats['classics'] = $this->getNoOfGames($dbh, $tournament, 2);
      return $stats;
    }
    
  }
?>
