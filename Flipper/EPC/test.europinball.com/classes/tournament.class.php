<?php

  class tournament extends base {
    
    public static $instances;
    public static $arrClass = 'tournaments';

    public static $table = 'tournamentEdition';

    public static $select = '
      select 
        o.id as id,
        t.id as tournament_id,
        t.name as tournamentName,
        o.name as name,
        concat(t.name, " ", left(o.startDate,4)) as fullName,
        o.name as shortName,
        t.acronym as acronym,
        o.startDate as startDate,
        o.endDate as endDate,
        o.location_id as location_id,
        o.comment as comment
      from tournamentEdition o 
      left join tournament t
        on o.tournament_id = t.id
    ';
    
    public static $parents = array(
      'location' => 'location'
    );
    
    public static $children = array(
      'match' => 'tournamentEdition',
      'machine' => 'tournamentEdition',
      'period' => 'tournamentEdition',
      'player' => 'tournamentEdition',
      'team' => 'tournamentEdition',
      'division' => array(
        'field' => 'tournamentEdition',
        'delete' => TRUE
      ),
      'tshirt' => array(
        'table' => 'tournamentTShirt',
        'field' => 'tournamentEdition'
      ),
      'entry' => 'tournamentEdition',
      'score' => 'tournamentEdition',
      'task' => 'tournamentEdition',
      'volunteer' => 'tournamentEdition',
      'period' => array(
        'table' => 'volunteerPeriod',
        'field' => 'tournamentEdition'
      )
    );
    
    public function __construct($data = NULL, $search = NOSEARCH, $depth = NULL) {
      if (is_string($data) && ($data == 'current' || $data == 'active') && $search == NOSEARCH) {
        $data = config::$activeTournament;
      }
      parent::__construct($data, $search, $depth);
    }
    
    public function getDivisions() {
      return divisions($this);
    }
    
    public function getDivision($type = NULL) {
      $type = ($type) ? $type : 'main';
      $divisions = divisions($this);
      foreach ($divisions as $division) {
        if ($division->{$type}) {
          return $division;
        }
      }
      return NULL;
    }
    
    public function isActive() {
      return ($this->id == config::$activeTournament);
    }
    
  }

?>