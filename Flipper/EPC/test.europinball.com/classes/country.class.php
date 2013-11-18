<?php

  class country extends geography {

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.parentCountry_id as parentCountry_id,
        o.continent_id as continent_id,
        o.latitude as latitude,
        o.longitude as longitude,
        o.comment as comment
      from country o
    ';
    
    public static $parents = array(
      'continent' => 'continent'
    );

  }

?>