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
      'city' => 'region',
      'city' => 'parentRegion',
      'region' => 'parentRegion',
      'location' => 'region',
      'location' => 'parentRegion',
      'owner' => 'region',
      'owner' => 'parentRegion',
      'person' => 'region',
      'person' => 'parentRegion',
      'player' => 'region',
      'player' => 'parentRegion',
      'team' => 'region',
      'team' => 'parentRegion',
      'volunteer' => 'region',
      'volunteer' => 'parentRegion'
    );

    public function getCities() {
      return $this->db->getObjectsByParent('city', $this);
    }

  }

?>