<?php
  
  class score extends base {
    
    public $entry_id;
    public $qualEntry_id;
    public $machine_id;
    public $game_id;
    public $game;
    public $gameAcronym;
    public $gameShortName;
    public $score;
    public $place;
    public $points;
    public $valid;
    public $person_id;
    public $player_id;
    public $team_id;
    public $tournamentDivision_id;
    public $tournamentEdition_id;
    public $team;
    public $player;
    public $firstName;
    public $lastName;
    public $initials;
    public $city_id;
    public $city;
    public $country_id;
    public $country;
    public $registerPerson_id;
    public $class = 'score';
    
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

    function getQUalEntry($dbh) {
      return $this->getEntry($dbh);
    }

    function getEntry($dbh) {
      return getEntryById($dbh, $this->entry_id);
    }

    function setScore($dbh, $score = null) {
      $this->points = ($score) ? $score : null;
      $query = '
        update qualScore set
          score = :score
        where id = :id
      ';
      $update[':score'] = ($score) ? $score : null;
      $update[':id'] = $this->id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function setPoints($dbh, $points = null) {
      $this->points = ($points) ? $points : null;
      $query = '
        update qualScore set
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
        update qualScore set
          place = :place
        where id = :id
      ';
      $update[':place'] = ($place) ? $place : null;
      $update[':id'] = $this->id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function setPlayer($dbh, $player = null) {
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
        $this->name = ($player) ? (($this->tournamentDivision_id == 2) ? 'Classics' : 'Main').' 2013: '.$this->player.' on '.$this->gameShortName : null;
        $query = '
          update qualScore set
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
        $sth = $dbh->prepare($query);
        if ($sth->execute($update)) {
          $qualEntry = $this->getEntry($dbh);
          return $qualEntry->setPlayer($dbh, $player);
        }
        return false;
      }
    }

    function setTeam($dbh, $team = null) {
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
        $this->name = ($team) ? 'Team 2013: '.$this->team.' on '.$this->gameShortName : null;
        $query = '
          update qualScore set
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
        $sth = $dbh->prepare($query);
        if ($sth->execute($update)) {
          $qualEntry = $this->getEntry($dbh);
          return $qualEntry->setTeam($dbh, $team);
        }
        return false;
      }
    }

    function setGame($dbh, $game = null) {
      $machine = ($game) ? $game->getMachine($dbh, $this->tournamentDivision_id) : null;
      $this->game_id = ($game) ? $game->id : null;
      $this->game = ($game) ? $game->name : null;
      $this->gameAcronym = ($game) ? $game->shortName : null;
      $this->gameShortName = $this->gameAcronym;
      $this->machine_id = ($machine) ? $machine->id : null;
      $this->name = ($game) ? (($this->tournamentDivision_id == 3) ? 'Team' : (($this->tournamentDivision_id == 2) ? 'Classics' : 'Main')).' 2013: '.(($this->tournamentDivision_id == 3) ? $this->team : $this->player).' on '.$game->shortName : null;
      $query = '
        update qualScore set
          game_id = :gameId,
          game = :game,
          gameAcronym = :gameAcronym,
          machine_id = :machineId,
          name = :name
        where id = :id
      ';
      $update[':name'] = $this->name;
      $update[':gameId'] = $this->game_id;
      $update[':game'] = $this->game;
      $update[':gameAcronym'] = $this->gameAcronym;
      $update[':machineId'] = $this->machine_id;
      $update[':country'] = $this->country;
      $update[':cityId'] = $this->city_id;
      $update[':city'] = $this->city;
      $update[':id'] = $this->id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }
  }
?>