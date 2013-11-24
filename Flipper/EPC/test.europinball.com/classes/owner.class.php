<?php

  class owner extends base {
        
    public static $instances;
    public static $arrClass = 'owners';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.shortName as shortName,
        o.shortName as acronym,
        o.national as national,
        o.contactPerson_id as contactPerson_id,
        o.telephoneNumber as telephoneNumber,
        o.mobileNumber as mobileNumber,
        o.mailAddress as mailAddress,
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
        o.account as account,
        o.paid as paid,
        o.comment as comment
      from team o
    ';
    
    public static $parents = array(
      'contactPerson' => 'person',
      'city' => 'city',
      'region' => 'region',
      'parentRegion' => 'region',
      'country' => 'country',
      'parentCountry' => 'country',
      'continent' => 'continent'
    );

  }

?>