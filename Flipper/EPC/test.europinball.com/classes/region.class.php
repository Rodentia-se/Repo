<?php
  
  class region extends geography {
    
    public $country_id;
    public $country;
    public $selfParent = true;
    public $class = 'region';

    public function getNoOfCities($dbh, $tournament = 1) {
      return $this->getNoOfItems($dbh, 'city', $tournament);
    }

    public function getStats($dbh, $tournament = 1) {
      $stats = parent::getStats($dbh, $tournament);
      $stats['city'] = $this->getNoOfCities($dbh, $tournament);
      return $stats;
    }

  }
?>