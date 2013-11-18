<?php

  class continent extends geography {

    public static $instances = array();
    public static $selfParent = FALSE;

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.latitude as latitude,
        o.longitude as longitude,
        o.comment as comment
      from continent o
    ';
    
    public static $parents = array(
    );

  }

?>