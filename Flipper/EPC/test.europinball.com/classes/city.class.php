<?php

  class city extends geography {
        
    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.region_id as region_id,
        o.parentRegion_id as parentRegion_id,
        o.country_id as country_id,
        o.parentCountry_id as parentCountry_id,
        o.continent_id as continent_id,
        o.latitude as latitude,
        o.longitude as longitude,
        o.comment as comment
      from city o
    ';
    
    public static $parents = array(
      'region' => 'region'
    );

  }

?>