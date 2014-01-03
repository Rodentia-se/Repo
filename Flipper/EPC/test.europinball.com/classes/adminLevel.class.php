<?php

  class adminLevel extends base {
   
    public static $arrClass = 'adminLevels';

    public static $select = '
      select
        o.id as id,
        o.name as LOWER(name),
        o.comment as comment
      from adminLevel o
    ';

    public static $parents = array();
    public static $children = array();
    
  }
  
?>