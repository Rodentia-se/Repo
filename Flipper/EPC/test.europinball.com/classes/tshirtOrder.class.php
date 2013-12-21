<?php

  class tshirtOrder extends base {
        
    public static $instances;
    public static $arrClass = 'tshirtOrders';
    public static $table = 'personTShirt';

    public static $select = '
      select 
        o.id as id,
        concat(o.number, " ", tc.name, " ", tz.name) as name,
        concat(o.number, " ", LOWER(tc.name), " T-shirts, size ", tz.name) as fullName,
        concat(tc.name, " ", tz.name) as shortName,
        o.number as number,
        o.person_id as person_id,
        tc.id as color_id,
        tc.name as colorName,
        tz.id as size_id,
        tz.name as size,
        tt.id as tournamentTshirt_id,
        tt.tournamentEdition_id as tournamentEdition_id
      from personTShirt o 
        left join tournamentTShirt tt on o.tournamentTShirt_id = tt.id
        left join tshirt ts on tt.tshirt_id = ts.id
        left join color tc on ts.color_id = tc.id
        left join size tz on ts.size_id = tz.id
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament',
      'person' => 'person',
      'color' => 'color'
    );

    public static $children = array();

  }

?>