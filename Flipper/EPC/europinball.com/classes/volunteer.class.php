<?php

<?php

  class volunteer extends base {
    
    public static $instances;
    public static $arrClass = 'volunteers';

    public static $select = '
      select 
        o.id as id,
        p.id as person_id,
        o.firstName as firstName,
        o.lastName as lastName,
        concat(ifnull(o.firstName, " "), " ", ifnull(o.lastName, " ")) as name,
        concat(ifnull(o.firstName, " "), " ", ifnull(o.lastName, " ")) as fullName,
        o.initials as shortName,
        o.streetAddress as streetAddress,
        o.zipCode as zipCode,
        o.gender_id as gender_id,
        o.city_id as city_id,
        o.region_id as region_id,
        o.parentRegion_id as parentRegion_id,
        o.country_id as country_id,
        o.parentCountry_id as parentCountry_id,
        o.continent_id as continent_id,
        o.telephoneNumber as telephoneNumber,
        o.mobileNumber as mobileNumber,
        o.mailAddress as mailAddress,
        o.here as here,
        p.username as username,
        p.nonce as nonce,
        o.tournamentEdition_id as tournamentEdition_id,
        o.comment as comment,
        o.adminLevel as adminLevel,
        ifnull(o.hours, 0) as hours,
        ifnull(o.alloc, 0) as alloc,
        timediff(time(concat(ifnull(o.hours, "00"), ":00:00")), ifnull(o.alloc, time("00:00:00"))) as hoursDiff
      from volunteer o
      left join person p
        on o.person_id = p.id
    ';

    public static $parents = array(
      'person' => 'person',
      'tournamentEdition' => 'tournament',
      'gender' => 'gender',
      'city' => 'city',
      'region' => 'region',
      'parentRegion' => 'region',
      'country' => 'country',
      'parentCountry' => 'country',
      'continent' => 'continent'
    );
    
    public static $children = array(
      'period' => array(
        'table' => 'volunteerPeriod',
        'field' => 'volunteer',
        'delete' => TRUE
      )
    );

  }

?>