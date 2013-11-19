<?php

  class set extends base {
        
    public static $instances = array();

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.name as shortName,
        o.match_id as match_id,
        o.machine_id as machine_id,
        o.reverseRound as reverseRound,
        o.sets as sets,
        o.tournamentDivision_id as tournamentDivision_id,
        o.tournamentEdition_id as tournamentEdition_id,
        o.comment as comment
      from matchSet o
    ';
    
    public static $parents = array(
      'match' => 'match',
      'machine' => 'game',
      'tournamentEdition' => 'tournament',
      'tournamentDivision' => 'division'
    );

  }

?>