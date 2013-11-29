<?php

  class task extends base {
        
    public static $instances;
    public static $arrClass = 'tasks';

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

    public static $children = array(
      'volunteerNeed' => array(
        'table' => 'volunteerNeed',
        'field' => 'task'
      ),
      'period' => array(
        'table' => 'volunteerPeriod',
        'field' => 'task'
      )
    );

  }

?>