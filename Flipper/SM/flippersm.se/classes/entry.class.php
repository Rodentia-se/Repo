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

    function getQualScores($dbh) {
      return getScores($dbh);
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

    function setPoints($dbh, $points = null) {
      $this->points = ($points) ? $points : null;
      $query = '
        update qualEntry set
          points = :points
        where id = :id
      ';
      $update[':points'] = ($points) ? $points : null;
      $update[':id'] = $this->id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function setPlace($dbh, $place = null) {
      $this->place = ($place) ? $place : null;
      $query = '
        update qualEntry set
          place = :place
        where id = :id
      ';
      $update[':place'] = ($place) ? $place : null;
      $update[':id'] = $this->id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function setPlayer($dbh, $player = null, $scores = true) {
      if ($this->tournamentDivision_id == 3) {
        return $this->setTeam($dbh, $player);
      } else {
        $this->player_id = ($player) ? (($player->classicsPlayerId) ? $player->classicsPlayerId : $player->mainPlayerId) : null;
        $this->player = ($player) ? $player->name : null;
        $this->person_id = ($player) ? $player->id : null;
        $this->firstName = ($player) ? $player->firstName : null;
        $this->lastName = ($player) ? $player->lastName : null;
        $this->initials = ($player) ? $player->initials : null;
        $this->country_id = ($player) ? $player->country_id : null;
        $this->country = ($player) ? $player->country : null;
        $this->city_id = ($player) ? $player->city_id : null;
        $this->city = ($player) ? $player->city : null;
        $this->name = ($player) ? $player->name.', '.(($player->classicsPlayerId) ? 'Classics' : 'Main').', 2013' : null;
        $query = '
          update qualEntry set
            name = :name,
            player_id = :playerId,
            person_id = :personId,
            firstName = :firstName,
            lastName = :lastName,
            initials = :initials,
            country_id = :countryId,
            country = :country,
            city_id = :cityId,
            city = :city
          where id = :id
        ';
        $update[':name'] = $this->name;
        $update[':playerId'] = $this->player_id;
        $update[':personId'] = $this->person_id;
        $update[':firstName'] = $this->firstName;
        $update[':lastName'] = $this->lastName;
        $update[':initials'] = $this->initials;
        $update[':countryId'] = $this->country_id;
        $update[':country'] = $this->country;
        $update[':cityId'] = $this->city_id;
        $update[':city'] = $this->city;
        $update[':id'] = $this->id;
        if ($scores) {
          $qualScores = $this->getScores($dbh);
          foreach ($qualScores as $qualScore) {
            $qualScore->setPlayer($dbh, $player, false);
          }
        }
        $sth = $dbh->prepare($query);
        return ($sth->execute($update)) ? true : false;
      }
    }

    function setTeam($dbh, $team = null, $scores = true) {
      if ($this->tournamentDivision_id != 3) {
        return $this->setPlayer($dbh, $team);
      } else {
        $this->team_id = ($team) ? $team->id : null;
        $this->team = ($team) ? $team->name : null;
        $this->initials = ($team) ? $team->initials : null;
        $this->country_id = ($team) ? $team->country_id : null;
        $this->country = ($team) ? $team->country : null;
        $this->city_id = ($team) ? $team->city_id : null;
        $this->city = ($team) ? $team->city : null;
        $this->name = ($team) ? $team->name.', Team, 2013' : null;
        $query = '
          update qualEntry set
            name = :name,
            player_id = :playerId,
            firstName = :firstName,
            initials = :initials,
            country_id = :countryId,
            country = :country,
            city_id = :cityId,
            city = :city
          where id = :id
        ';
        $update[':name'] = $this->name;
        $update[':playerId'] = $this->team_id;
        $update[':firstName'] = $this->team;
        $update[':initials'] = $this->initials;
        $update[':countryId'] = $this->country_id;
        $update[':country'] = $this->country;
        $update[':cityId'] = $this->city_id;
        $update[':city'] = $this->city;
        $update[':id'] = $this->id;
        if ($scores) {
          $qualScores = $this->getScores($dbh);
          foreach ($qualScores as $qualScore) {
            $qualScore->setTeam($dbh, $team, false);
          }
        }
        $sth = $dbh->prepare($query);
        return ($sth->execute($update)) ? true : false;
      }
    }
  }
?>