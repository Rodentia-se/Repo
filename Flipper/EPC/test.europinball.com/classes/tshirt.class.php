<?php

  class tshirt extends base {
        
    public static $instances;
    public static $arrClass = 'tshirts';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.name as shortName,
        concat(tc.name, tz.name) as sortName,
        tc.name as color,
        tc.id as color_id,
        tz.name as size,
        tz.id as size_id,
        tt.id as tournamentTshirt_id,
        tt.tournamentEdition_id as tournamentEdition_id
      from tournamentTShirt o 
        left join tshirt ts on o.tshirt_id = ts.id
        left join color tc on ts.color_id = tc.id
        left join size tz on ts.size_id = tz.id
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament',
      'person' => 'person',
    );

    public static $children = array(
      'tshirt' => array(
        'table' => 'tournamentTshirt',
        'field' => 'tshirt',
        'delete' => TRUE
      ),
    );
  }

?>