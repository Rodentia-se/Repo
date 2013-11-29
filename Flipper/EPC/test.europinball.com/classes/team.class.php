<?php

  class team extends player {
        
    public static $instances;
    public static $arrClass = 'teams';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.initials as shortName,
        o.national as national,
        o.contactPlayer_id as contactPlayer_id,
        o.city_id as city_id,
        o.region_id as region_id,
        o.parentRegion_id as parentRegion_id,
        o.country_id as country_id,
        o.parentCountry_id as parentCountry_id,
        o.continent_id as continent_id,
        o.tournamentDivision_id as tournamentDivision_id,
        o.tournamentEdition_id as tournamentEdition_id,
        o.dateRegistered as dateRegistered,
        o.registerPerson_id as registerPerson_id,
        o.comment as comment
      from team o
    ';
    
    public static $parents = array(
      'contactPlayer' => 'player',
      'registerPerson_id' => 'person',
      'tournamentEdition' => 'tournament',
      'tournamentDivision' => 'division',
      'city' => 'city',
      'region' => 'region',
      'parentRegion' => 'region',
      'country' => 'country',
      'parentCountry' => 'country',
      'continent' => 'continent'
    );

    public static $children = array(
      'player' => array(
        'field' => 'team',
        'delete' => TRUE
      ),
      'teamPlayer' => array(
        'table' => 'teamPlayer',
        'field' => 'player',
        'delete' => TRUE
      )
    )

  }

?>