<?php

  class country extends region {

    public static $instances;
    public static $arrClass = 'countries';

    public static $selfParent = TRUE;

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.iso3 as shortName,
        o.iso2 as acronym,
        o.altName as altName,
        o.numCode as numCode,
        o.parentCountry_id as parentCountry_id,
        o.continent_id as continent_id,
        o.capitalCity_id as capitalCity_id,
        o.latitude as latitude,
        o.longitude as longitude,
        o.comment as comment
      from country o
    ';
    
    public static $parents = array(
      'parentCountry' => 'country',
      'continent' => 'continent',
      'capitalCity' => 'city'
    );

    public static $children = array(
      'location' => 'country',
      'location' => 'parentCountry',
      'city' => 'country',
      'city' => 'parentCountry',
      'region' => 'country',
      'region' => 'parentCountry',
      'owner' => 'country',
      'owner' => 'parentCountry',
      'person' => 'country',
      'person' => 'parentCountry',
      'player' => 'country',
      'player' => 'parentCountry',
      'team' => 'country',
      'team' => 'parentCountry',
      'volunteer' => 'country',
      'volunteer' => 'parentCountry',
      'country' => 'parentCountry',
      'matchPlayer' => 'country',
      'matchScore' => 'country',
      'entry' => 'country',
      'score' => 'country'
    );

    public static $infoProps = array(
      'name',
      'continent'
    );

    public function getRegions() {
      return $this->db->getObjectsByParent('region', $this);
    }

  }

?>