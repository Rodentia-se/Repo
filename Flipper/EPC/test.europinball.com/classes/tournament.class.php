<?php

  class tournament extends base {
    
    public static $instances = array();

    public static $select = '
      select 
        o.id as id,
        t.id as tournament_id,
        o.name as name,
        concat(t.name, " ", left(o.startDate,4)) as fullName,
        t.acronym as shortName,
        o.startDate as startDate,
        o.endDate as endDate,
        o.location_id as location_id
      from tournament t
      left join tournamentEdition o
        on o.tournament_id = t.id
    ';
    
    public static $parents = array(
      'location' => 'location'
    );
    
  }

?>