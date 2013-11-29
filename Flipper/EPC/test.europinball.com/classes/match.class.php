<?php

  class match extends base {
        
    public static $instances;
    public static $arrClass = 'matches';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.name as shortName,
        o.round as round,
        o.reverseRound as reverseRound,
        o.sets as sets,
        o.tournamentDivision_id as tournamentDivision_id,
        o.tournamentEdition_id as tournamentEdition_id,
        o.comment as comment
      from match o
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament',
      'tournamentDivision' => 'division'
    );

    public static $children = array(
      'matchPlayer' => 'match',
      'matchScore' => 'match',
      'set' => 'match'
    );

  }

?>