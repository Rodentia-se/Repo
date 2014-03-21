<?php

  class score extends base {
        
    public static $instances;
    public static $arrClass = 'scores';

    public static $table = 'qualScore';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.name as shortName,
        o.score as score,
        o.place as place,
        round(o.points) as points,
        o.points as fullPoints,
        o.order as `order`,
        o.round as round,
        o.url as url,
        o.person_id as person_id,
        o.player_id as player_id,
        o.registerPerson_id as registerPerson_id,
        o.game_id as game_id,
        o.machine_id as machine_id,
        o.city_id as city_id,
        o.country_id as country_id,
        o.qualEntry_id as qualEntry_id,
        o.tournamentDivision_id as tournamentDivision_id,
        o.tournamentEdition_id as tournamentEdition_id,
        o.comment as comment
      from qualScore o
    ';
    
    public static $parents = array(
      'registerPerson_id' => 'person',
      'city' => 'city',
      'country' => 'country',
      'tournamentEdition' => 'tournament',
      'tournamentDivision' => 'division',
      'qualEntry' => 'entry',
      'player' => 'player',
      'machine' => 'machine'
    );

    public function setPoints($points = NULL) {
      $this->points = ($points) ? $points : NULL;
      return $this->save();
    }

    public function setPlace($place = NULL) {
      $this->place = ($place) ? $place : NULL;
      return $this->save();
    }

    public function getResultsRow($array = FALSE) {
      // @todo: Handle custom headers
      // @todo: Change to object
      $return = array(
        (($score->place) ? $score->place : 999999),
        $score->place,
        $this->person->getLink(),
        (($this->person) ? $this->person->getPhotoIcon() : ''),
        (is_object($this->person->country)) ? $this->person->country->name : '',
        (is_object($this->person->country)) ? $this->person->country->getIcon() : '',
        $this->score,
        '<span title="'.$this->fullPoints.'">'.$this->points.'</span>'
      );
      return ($array) ? $return : (object) $return;
    }

  }

?>