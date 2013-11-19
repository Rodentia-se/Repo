<?php

  class task extends base {
        
    public static $instances = array();

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.acronym as shortName,
        o.acronym as acronym,
        o.tournamentEdition_id as tournamentEdition_id,
        o.comment as comment
      from task o
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament'
    );

  }

?>