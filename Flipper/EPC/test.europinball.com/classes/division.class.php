<?php

  class division extends base {
    
    public static $instances;
    public static $arrClass = 'divisions';

    public static $select = '
      select 
        o.id as id,
        d.id as division_id,
        d.name as divisionName,
        concat(substring_index(o.name, " ", 1), ", ", right(o.name, 4)) as name,
        o.name as fullName,
        substring_index(o.name, " ", 1) as shortName,
        d.acronym as acronym,
        o.tournamentEdition_id as tournamentEdition_id
      from tournamentDivision o 
      left join division d
        on o.division_id = d.id
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament'
    );
    
  }

?>