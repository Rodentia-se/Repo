<?php

  class location extends geography {
        
    public static $select = '
      select 
        o.id as id,
        o.streetAddress as streetAddress,
        o.zipCode as zipCode,
        o.city_id as city_id,
        o.region_id as region_id,
        o.parentRegion_id as parentRegion_id,
        o.country_id as country_id,
        o.parentCountry_id as parentCountry_id,
        o.continent_id as continent_id,
        o.latitude as latitude,
        o.longitude as longitude,
        o.comment as comment
      from location o
    ';

  }

?>