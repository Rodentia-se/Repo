<?php

  class game extends base {
        
    public static $instances;
    public static $arrClass = 'games';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.acronym as acronym,
        o.acronym as shortName,
        o.manufacturer_id as manufacturer_id,
        o.game_ipdb_id as ipdb,
        o.game_link_rulesheet as rules,
        o.game_year_released as year
      from game o
    ';
    
    public static $parents = array(
      'manufacturer' => 'manufacturer'
    );

    public static $children = array(
      'machine' => 'game'
    );

  }

?>