<?php

  class continent extends geography {

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.latitude as latitude,
        o.longitude as longitude,
        o.comment as comment
      from region o
    ';
    
    public static $parents = array(
    );

  }

?>