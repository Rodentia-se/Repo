<?php

  class size extends base {
   
    public static $arrClass = 'sizes';

    public static $select = '
      select
        o.id as id,
        o.name as name,
        o.comment as comment
      from size o
    ';

    public static $parents = array();
    public static $children = array();
    
  }
  
?>