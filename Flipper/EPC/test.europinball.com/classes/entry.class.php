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

    public function populate() {
      parent::populate();
      if ($this->id) {
        $query = '
          select 
            max(score) as bestScore,
            max(points) as bestPoints,
            min(place) as bestPlace
          from qualScore 
          group by qualEntry_id
          where qualEntry_id = :qeId
        ';
        $values['qeId'] = $this->id;
        $sth = $this->db->select($query, $values);
        if ($sth) {
          foreach ($sth->fetch(PDO::FETCH_ASSOC) as $key => $value) {
            $this->$key = $value;
          }
        }
      }
    }

  }

?>