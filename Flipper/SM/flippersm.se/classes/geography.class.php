<?php

  class geography extends base {
    
    public $continent_id;
    public $continent;
    public $parentCountry_id;
    public $parentCountry;
    public $country_id;
    public $country;
    public $parentRegion_id;
    public $parentRegion;
    public $region_id;
    public $region;
    public $city_id;
    public $city;
    public $altName;
    public $shortName;
    public $capitalCity_id;
    public $capitalCity;
    public $latitude;
    public $longitude;
    public $selfParent = false;
    public $class = 'geography';
    
    public function getNoOfPlayers($dbh, $tournament = 1) {
      $query = '
        select
          count(pl.id)
        from player pl
        where
          (pl.'.$this->class.'_id = '.$this->id.'
      ';
      $query .= ($this->selfParent) ? ' or pl.parent'.ucFirst($this->class).'_id = '.$this->id : '';
      $query .= ') and pl.tournamentDivision_id = '.$tournament;
      $sth = $dbh->query($query);
      $return = $sth->fetchColumn();
      return ($return) ? $return : 0;
    }

    public function getNoOfTeams($dbh, $tournament = 1, $national = true) {
      $query = '
        select
          count(t.id)
        from team t
        where
          (t.'.$this->class.'_id = '.$this->id.'
      ';
      $query .= ($this->selfParent) ? ' or t.parent'.ucFirst($this->class).'_id = '.$this->id : '';
      $query .= ') and t.tournamentEdition_id = '.$tournament;
      $query .= ($national) ? ' and t.tournamentDivision_id = 12' : ' and t.tournamentDivision_id = 3';
      $sth = $dbh->query($query);
      $return = $sth->fetchColumn();
      return ($return) ? $return : 0;
    }
              
    public function getNoOfItems($dbh, $class, $tournament = 1) {
      if ($class == 'player') {
        return $this->getNoOfPlayers($dbh, $tournament);
      } else if ($class == 'team') {
        return $this->getNoOfTeams($dbh, $tournament);
      } else {
        $query = '
          select count(distinct '.$class.'_id)
            from player
          WHERE tournamentDivision_id = '.$tournament.'
            and '.$class.'_id is not null
            AND ('.$this->class.'_id = '.$this->id.'
        ';
        $query .= ($this->selfParent) ? ' or parent'.ucFirst($this->class).'_id = '.$this->id.')' : ')';
        $sth = $dbh->query($query);
        $return = $sth->fetchColumn();
        return ($return) ? $return : 0;
      }
    }
    
    public function getStats($dbh, $tournament = 1) {
      $stats['player'] = $this->getNoOfPlayers($dbh, $tournament);
      return $stats;
    }

  }
?>