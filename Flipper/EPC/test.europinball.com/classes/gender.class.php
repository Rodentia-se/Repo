<?php

  class gender extends base {
        
    public static $instances = array();

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.gender_def as shortName,
        o.gender_def as acronym,
        o.comment as comment
      from gender o
    ';
    
    public static $parents = array();

  }

?>