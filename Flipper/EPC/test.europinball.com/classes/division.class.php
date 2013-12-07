<?php

  class division extends base {
    
    public static $instances;
    public static $arrClass = 'divisions';

    public static $table = 'tournamentDivision';

    public static $select = '
      select 
        o.id as id,
        d.id as division_id,
        d.name as divisionName,
        concat(substring_index(o.name, " ", 1), ", ", right(o.name, 4)) as name,
        o.name as fullName,
        substring_index(o.name, " ", 1) as shortName,
        d.acronym as acronym,
        d.main as main,
        d.team as team,
        d.national as national,
        if(ifnull(d.national, 0) = 1 and ifnull(d.team, 0) = 1, 1, 0) as nationalTeam,
        d.secondary as decondary,
        d.side as dide,
        d.recreational as recreational,
        d.modern as modern,
        d.classics as classics,
        d.eighties as eighties,
        d.youth as youth,
        d.teamMembers as teamMembers,
        o.wpprPercentage as wpprPercantage,
        o.includeInSTats as includeInStats,
        o.tournamentEdition_id as tournamentEdition_id
      from tournamentDivision o 
      left join division d
        on o.division_id = d.id
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament'
    );
    
    public static $children = array(
      'machine' => array(
        'field' => 'tournamentDivision',
        'delete' => TRUE
      ),
      'match' => array(
        'field' => 'tournamentDivision',
        'delete' => true
      ),
      'player' => array(
        'field' => 'tournamentDivision', 
        'delete' => true
      ),
      'team' => array(
        'field' => 'tournamentDivision',
        'delete' => true
      ),
      '´qualGroup' => array(
        'field' => 'tournamentDivision',
        'delete' => true
      ),
      'entry' => 'tournamentDivision',
      'score' => 'tournamentDivision'
    );

    public function __construct($data = NULL, $search = NOSEARCH, $depth = NULL) {
      if (is_string($data) && ($data == 'current' || $data == 'active' || in_array($data, config::$activeDivisions)) && $search == NOSEARCH) {
        if ($data == 'current' || $data == 'active') {
          if (config::$mainDivision) {
            $data = config::$mainDivision;
          } else {
            $delegated = TRUE;
            $tournament = tournament(config::$activeTournament);
            $data = 'main';
          }
        } else if (in_array($data, config::$activeDivisions)) {
          if (config::${$data.'Division'}) {
            $data = config::${$data.'Division'};
          } else {
            $delegated = TRUE;
            $tournament = tournament(config::$activeTournament);
          }
        } else if ($data != 'empty' && $data != 'new') {
          $delegated = TRUE;
          $tournament = tournament(config::$activeTournament);
        }
      } else if (is_object($data) && get_class($data) == 'tournament' && (is_string($search) || $search == NOSEARCH)) {
        debug($search, 'div1, search');
        $delegated = TRUE;
        $tournament = $data;
        $data = ($search == NOSEARCH) ? NULL : $search;
        debug($data, 'div2, data');
      }
      if ($delegated) {
        if ($tournament) {
          debug($tournament->id, 'div3, tourn');
          $divisions = divisions($tournament);
          $type = ($data) ? $data : 'main';
          foreach($divisions as $division) {
             debug($type, 'div4, type');
             debug($division, 'div5, divid');
            if ($division->id == 15) {
              debug($division);
            }
            if ($division->$type) {
              debug($division->id, 'div6, divid');
              parent::__construct($division->id, NOSEARCH, $depth);
              debug($this->id, 'div7, thisid');
              $hit = TRUE;
              break;
            }
            debug((($hit) ? 'hit' : 'nohit'), 'div8, hit');
          }
          debug($this->id, 'div9, divid');
          if (!$hit) {
            $this->failed = TRUE;
          }
          debug($this->id, 'div10, divid');
        } else {
          $this->failed = TRUE;
        }
          debug($this->id, 'div11, divid');
      } else {
        parent::__construct($data, $search, $depth);
      }
          debug($this, 'div12, divid');
    }

    public function getPlayers() {
      return players($this);
    }
    
    public function isActive() {
      return in_array($this->id, config::$activeDivisions);
    }
    
  }

?>