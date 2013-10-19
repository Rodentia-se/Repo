<?php
  
  class entry extends base {
    
    public $person_id;
    public $player_id;
    public $tournamentDivision_id;
    public $tournamentEdition_id;
    public $player;
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

    function getScores($dbh, $tournament = 1, $division = 1) {
      $query = '
        select
          qs.id as id,
          qs.name as name,
          qs.person_id as person_id,
          qs.player_id as player_id,
          qs.qualEntry_id as qualEntry_id,
          qs.qualEntry_id as entry_id,
          qs.tournamentDivision_id as tournamentDivision_id,
          qs.tournamentEdition_id as tournamentEdition_id,
          min(qs.place) as place,
          max(qs.points) as points,
          max(qs.score) as score,
          qs.firstName as firstName,
          qs.lastName as lastName,
          qs.initials as initials,
          concat(ifnull(qs.firstName, ""), " ", ifnull(qs.lastName, "")) as player,
          qs.country_id as country_id,
          qs.country as country,
          qs.city_id as city_id,
          qs.city as city,
          qs.machine_id as machine_id,
          qs.game_id as game_id,
          qs.game as game,
          qs.gameAcronym as gameShortName,
          qs.registerPerson_id as registerPerson_id
        from qualScore qs
        group by g.machine_id
        where
          qs.person_id = :personId
        order by max(qs.points) desc
      ';
      $query .= ($tournament) ? ' and tournamentEdition = :tournament' : '';
      $query .= ($tournament) ? ' and tournamentDivision = :division' : '';
      $query .= 'group by qe.id';
      $select[':personId'] = $this->id;
      $select[':tournament'] = $tournament;
      $select[':division'] = $division;
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('score')) {
        $objs[] = $obj;
      }
      return $objs;
    }

  }
?>