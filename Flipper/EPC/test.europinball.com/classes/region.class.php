<?php

  class region extends city {

    public static $instances;
    public static $arrClass = 'regions';

    public static $selfParent = TRUE;

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.acronym as shortName,
        o.acronym as acronym,
        o.altName as altName,
        o.capitalCity_id as capitalCity_id,
        o.parentRegion_id as parentRegion_id,
        o.country_id as country_id,
        o.parentCountry_id as parentCountry_id,
        o.continent_id as continent_id,
        o.latitude as latitude,
        o.longitude as longitude,
        o.comment as comment
      from region o
    ';
    
    public static $parents = array(
      'parentRegion' => 'region',
      'country' => 'country',
      'parentCountry' => 'country',
      'continent' => 'continent',
      'capitalCity' => 'city'
    );
    
    public static $children = array(
      'region_id' => array(
        'classes' => array('city', 'location', 'owner', 'person', 'player', 'team', 'volunteer'),
        'fields' => array('name' => 'region')
      ),
      'parentRegion_id' => array(
        'classes' => array('city', 'region', 'location', 'owner', 'person', 'player', 'team', 'volunteer'),
        'fields' => array('name' => 'parentRegion')
      )
    );

    public static $infoProps = array(
      'name',
      'country',
      'continent'
    );

    public static $infoChildren = array(
      'cities',
      'players'
    );

    public function getCities() {
      return $this->db->getObjectsByParent('city', $this);
    }

  }

?>