<?php
  
  class entry extends base {
    
    public $person_id;
    public $player_id;
    public $team_id;
    public $tournamentDivision_id;
    public $tournamentEdition_id;
    public $player;
    public $team;
    public $firstName;
    public $lastName;
    public $initials;
    public $city_id;
    public $city;
    public $country_id;
    public $country;
    public $place;
    public $points;
    public $maxScore;
    public $maxPoints;
    public $bestPlace;
    public $class = 'entry';
    
    public function __construct($data = null, $type = 'array') {
      switch ($type) {
        case 'json':
          if ($data) {
            $this->set(json_decode($json, true));
          }
        break;
        case 'array':
          if ($data) {
            $this->set($data);
          }
        break;
      }
    }
    
    public function set($data) {
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }
    }

    function getQualScores($dbh, $tournament = 1, $division = 1) {
      return getScores($dbh, $tournament, $division);
    }

    function getScores($dbh) {
      $query = getScoreSelect().'
        where qs.qualEntry_id = '.$this->id.'
        group by qs.machine_id
        order by max(qs.points) desc, min(qs.place) asc
      ';
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('score')) {
        $objs[] = $obj;
      }
      return $objs;
    }

  }
?>