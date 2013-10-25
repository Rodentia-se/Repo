<?php

  class country extends geography {
    
    public $selfParent = true;
    public $class = 'country';

    public function getNoOfRegions($dbh, $tournament = 1) {
      return $this->getNoOfItems($dbh, 'region', $tournament);
    }
    
    public function getNoOfCities($dbh, $tournament = 1) {
      return $this->getNoOfItems($dbh, 'city', $tournament);
    }

    public function getStats($dbh, $tournament = 1) {
      $stats = parent::getStats($dbh, $tournament);
      $stats['region'] = $this->getNoOfRegions($dbh, $tournament);
      $stats['city'] = $this->getNoOfCities($dbh, $tournament);
      $stats['team'] = $this->getNoOfTeams($dbh, $tournament);
      return $stats;
    }

  }
?>