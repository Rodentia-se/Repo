<?php

  class continent extends country {

    public static $instances;
    public static $arrClass = 'continents';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.name as shortName,
        o.altName as altName,
        o.latitude as latitude,
        o.longitude as longitude,
        o.comment as comment
      from continent o
    ';
    
    public static $parents = array(
    );
    
    public function getCountries() {
      return $this->db->getObjectsByParent('country', $this);
    }

  }

?>