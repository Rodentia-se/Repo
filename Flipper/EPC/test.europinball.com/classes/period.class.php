<?php

  class period extends timeSlot {
        
    public static $instances;
    public static $arrClass = 'periods';

    public static $select = '
      select 
        o.id as id,
        concat(o.date, " ", replace(replace(startTime, ":00", ""), ":00", ""), "-", replace(replace(endTime, ":00", ""), ":00", "")) as name,
        o.name as fullName,
        concat(replace(replace(startTime, ":00", ""), ":00", ""), "-", replace(replace(endTime, ":00", ""), ":00", "")) as shortName,
        o.date as date,
        o.startTime as startTime,
        o.endTime as endTime,
        o.tournamentEdition_id as tournamentEdition_id,
        o.comment as comment
      from period o
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament'
    );
    
    // @todo: Fix children
/*
    public static $children = array(
      'volunteerNeed' => array(
        'table' => 'volunteerNeed',
        'field' => 'period'
      ),
      'period' => array(
        'table' => 'volunteerPeriod',
        'field' => 'period',
        'delete' => TRUE
      )
    );
*/

  }

?>