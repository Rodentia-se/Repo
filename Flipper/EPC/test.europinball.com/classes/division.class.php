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

    public function __construct($data = NULL, $search = 'noSearchCriteriaProvided', $depth = NULL) {
      if (is_string($data) && ($data == 'current' || in_array($data, config::$activeDivisions)) && $search == 'noSearchCriteriaProvided') {
        if ($data == 'current') {
          if (config::$mainDivision) {
            $data = config::$mainDivision;
          } else {
            $tournament = tournament(config::$activeTournament);
            $data = 'main';
          }
        } else if (in_array($data, config::$activeDivisions)) {
          if (config::${$data.'Division'}) {
            $data = config::${$data.'Division'};
          } else {
            $tournament = tournament(config::$activeTournament);
          }
        } else if ($data != 'empty' && $data != 'new') {
          $tournament = tournament(config::$activeTournament);
        }
        if ($tournament) {
          $division = $tournament->getDivision($data);
          if ($division && isId($division->id)) {
            $data = $division->id;
          }
        }
      }
      parent::__construct($data, $search, $depth);
    }

    public function getPlayers() {
      return players($this);
    }
    
  }

?>