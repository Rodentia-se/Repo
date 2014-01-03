<?php

  class tshirt extends base {
        
    public static $instances;
    public static $arrClass = 'tshirts';
    public static $table = 'tournamentTShirt';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.name as shortName,
        concat(tc.name, tz.id) as sortName,
        tc.id as color_id,
        tc.name as colorName,
        tz.id as size_id,
        ts.id as tshirt_id,
        o.tournamentEdition_id as tournamentEdition_id
      from tournamentTShirt o 
        left join tshirt ts on o.tshirt_id = ts.id
        left join color tc on ts.color_id = tc.id
        left join size tz on ts.size_id = tz.id
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament',
      'color' => 'color',
      'size' => 'size'
    );
    
    public static $children = array();

  }

?>