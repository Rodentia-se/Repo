<?php
  
  class score extends base {
    
    public $entry_id;
    public $qualEntry_id;
    public $machine_id;
    public $game_id;
    public $game;
    public $gameAcronym;
    public $place;
    public $points;
    public $score;
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
  }
?>