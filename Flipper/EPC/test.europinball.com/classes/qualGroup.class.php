<?php

  class qualGroup extends timeSlot {
        
    public static $instances;
    public static $arrClass = 'qualGroups';

    public static $select = '
      select 
        o.id as id,
        concat(o.name, ": ", o.date, " ", replace(replace(startTime, ":00", ""), ":00", ""), "-", replace(replace(endTime, ":00", ""), ":00", "")) as name,
        concat(o.name, ": ", o.date, " ", startTime, " - ", endTime) as fullName,
        concat(o.name, ": ", replace(replace(startTime, ":00", ""), ":00", ""), "-", replace(replace(endTime, ":00", ""), ":00", "")) as shortName,
        o.name as acronym,
        o.date as date,
        o.startTime as startTime,
        o.endTime as endTime,
        o.tournamentDivision_id as tournamentDivision_id,
        o.comment as comment
      from qualGroup o
    ';
    
    public static $parents = array(
      'tournamentDivision' => 'division'
    );
    
    public static $children = array(
      'player' => 'qualGroup',
      'team' => 'qualGroup',
      'playerQualGroups' => array(
        'field' => 'qualGroup',
        'delete' => TRUE
      )
    );


  }

?>