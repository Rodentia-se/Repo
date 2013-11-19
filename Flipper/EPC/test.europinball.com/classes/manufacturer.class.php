<?php

  class manufacturer extends base {
        
    public static $instances = array();

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.shortName as shortName,
        o.shortName as acronym,
        o.url as url,
        o.comment as comment
      from manufacturer o
    ';
    
    public static $parents = array();

  }

?>