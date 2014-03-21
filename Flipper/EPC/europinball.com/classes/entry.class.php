<?php

  class entry extends base {
        
    public static $instances;
    public static $arrClass = 'entries';
    
    public static $table = 'qualEntry';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.name as shortName,
        o.place as place,
        round(o.points) as points,
        o.points as fullPoints,
        o.person_id as person_id,
        o.player_id as player_id,
        o.city_id as city_id,
        o.country_id as country_id,
        o.tournamentDivision_id as tournamentDivision_id,
        o.tournamentEdition_id as tournamentEdition_id,
        o.comment as comment
      from qualEntry o
    ';
    
    public static $parents = array(
      'city' => 'city',
      'country' => 'country',
      'tournamentEdition' => 'tournament',
      'tournamentDivision' => 'division',
      'player' => 'player'
    );
    
    // @todo: Fix children
/*
    public static $children = array(
      'score' => array(
        'field' => 'qualEntry',
        'delete' => TRUE
      )
    );
*/
    
    public function addScore($machine, $score = NULL) {
      $machine = machine($machine);
      if (isMachine($machine) && $machine->tournamentDivision_id == $this->tournamentDivision_id) {
        $tournament = tournament($this->tournamentEdition_id);
        $division = division($this->tournamentDivision_id);
        $player = player($this->player_id);
        $score = new score(array(
          'name' => $tournament->name.', '.$division->divisionName.', '.(($player->shortName) ? $player->shortName : substr($player->firstName, 0, 1).' '.substr($player->lastName, 0, 1)).', '.$machine->name,
          'score' => $score,
          'person_id' => $this->person_id,
          'player_id' => $this->player_id,
          'machine_id' => $machine->id,
          'tournamentDivision_id' => $division->id,
          'tournamentEdition_id' => $tournament->id,
          'qualEntry_id' => $this->id,
          'firstName' => $player->firstName,
          'lastName' => $player->lastName,
          'city_id' => $player->city_id,
          'country_id' => $player->country_id
        ));
        $score->id = $score->save();
        return ($score->id) ? $score : FALSE;
      }
      return FALSE;
    }

    public function populate() {
      parent::populate();
      if ($this->id) {
        $query = '
          select 
            max(score) as bestScore,
            max(points) as bestPoints,
            min(place) as bestPlace
          from qualScore 
          where qualEntry_id = :qeId
          group by qualEntry_id
        ';
        $values['qeId'] = $this->id;
        $fields = $this->db->select($query, $values);
        if ($fields) {
          foreach ($fields[0] as $key => $value) {
            $this->$key = $value;
          }
        }
      }
    }

  }

?>