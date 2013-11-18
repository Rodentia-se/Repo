<?php

  class country extends region {

    public static $instances = array();
    public static $selfParent = TRUE;

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
      'parentCountry' => 'country',
      'continent' => 'continent'
    );

    public function getRegions() {
      return $this->db->getObjectsByParent('region', $this);
    }

  }

?>