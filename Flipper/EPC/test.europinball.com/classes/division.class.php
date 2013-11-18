<?php

  class division extends base {
    
    public static $instances = array();

    public static $select = '
      select 
        o.id as id,
        d.id as division_id,
        d.name as divisionName,
        concat(substring_index(o.name, " ", 1), ", ", right(o.name, 4)) as name,
        o.name as fullName,
        d.acronym as acronym,
        substring_index(o.name, " ", 1) as shortName,
        o.tournamentEdition_id as tournamentEdition_id
      from division d
      left join tournamentDivision o
        on o.division_id = d.id
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament'
    );
    
  }

?>