<?php

  class city extends geography {
        
    public static $instances;
    public static $arrClass = 'cities';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.name as shortName,
        o.altName as altName,
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
      'region' => 'region',
      'parentRegion' => 'region',
      'country' => 'country',
      'parentCountry' => 'country',
      'continent' => 'continent'
    );
    
    public static $children = array(
      'country' => 'capitalCity',
      'region' => 'capitalCity',
      'location' => 'city',
      'owner' => 'city',
      'person' => 'city',
      'player' => 'city',
      'team' => 'city',
      'volunteer' => 'city',
      'matchPlayer' => 'city',
      'matchScore' => 'city',
      'entry' => 'city',
      'score' => 'city'
    );

    public static $infoProps = array(
      'name',
      'region',
      'country',
      'continent'
    );

    public function getLocations() {
      return $this->db->getObjectsByParent('location', $this);
    }
    
    public function getParent($selfParents = FALSE, $obj = TRUE) {
      foreach (static::$parents as $parent => $class) {
        if ($selfParents || substr($parent, 0, 6) != 'parent') {
          if ($this->{$parent.'_id'}) {
            return ($obj) ? $class($this->{$parent.'_id'}) : $parent;
          }
        }
      }
      return FALSE;
    }
    
    public static function _getParents($selfParents = FALSE) {
      foreach (static::$parents as $parent => $class) {
        if ($selfParents || substr($parent, 0, 6) != 'parent') {
          $parents[] = $parent;
        }
      }
      return $parents;
    }

    public function getParents($selfParents = FALSE, $objs = TRUE) {
      foreach (static::$parents as $parent => $class) {
        if ($selfParents || substr($parent, 0, 6) != 'parent') {
          $parents[] = ($objs) ? $class($this->{$parent.'_id'}) : $parent;
        }
      }
      return $parents;
    }

  }

?>