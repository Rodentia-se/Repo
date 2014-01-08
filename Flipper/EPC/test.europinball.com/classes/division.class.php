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
        d.shortName as shortName,
        d.acronym as acronym,
        d.shortName as type,
        d.main as main,
        d.team as team,
        d.national as national,
        if(ifnull(d.national, 0) = 1 and ifnull(d.team, 0) = 1, 1, 0) as nationalTeam,
        d.secondary as secondary,
        d.side as side,
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
    
    // @todo: Fix children
/*
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
*/
    
    public function __construct($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
      $aliases = array('current', 'active');
      $divisions = array_merge($aliases, config::$divisions);
      if (is_string($data) && in_array($data, $aliases)) {
        $data = tournament($data);
        $search = ($search) ? $search : 'main';
        if (!$data || !isId($data->id)) {
          $this->failed = TRUE;
          return FALSE;
        }
      } else if (is_string($data) && in_array($data, config::$activeDivisions)) {
        if (config::${$data.'Division'}) {
          $data = config::${$data.'Division'};
          $search = config::NOSEARCH;
        } else {
          $this->failed = TRUE;
          return FALSE;
        }
      } else if (is_string($data) && in_array($data, config::$divisions)) {
        $data = array($data => 1);
        $search = TRUE;
      }
      if (isObj($data) && get_class($data) == 'tournament') {
        $search = (is_string($search) && in_array($search, config::$divisions)) ? $search : 'main';
        $data = array(
          'tournamentEdition_id' => $data->id, 
          'd.'.$search => 1
        );
      }
      parent::__construct($data, $search, $depth);
    }

    public function isActive() {
      return in_array($this->id, config::$activeDivisions);
    }
    
    public function calcWaiting($number = NULL) {
      $query = '
        update player 
          right join (
            select @rownum := @rownum +1 seq, 
              id AS pid, 
              waiting
            from player, 
              (SELECT @rownum :=0)r
            where tournamentDivision_id = '.$this->id.'
             order by noWaiting desc, id asc
            ) AS players
            ON players.pid = player.id
          set player.waiting = if(players.seq > '.(($number) ? $number : config::$participationLimit[$this->type]).', players.seq - '.(($number) ? $number : config::$participationLimit[$this->type]).', NULL)
        ';
      }
      $return = $this->update($query);
      if ($return) {
        $query = 'select max(waiting) from player where tournamentDivision_id = '.$this->id;
        return $this->getValue($query);
      } else {
        return FALSE;
      }
    } 
    
  }

?>