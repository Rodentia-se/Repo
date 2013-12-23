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
        tc.name as color,
        tz.id as size_id,
        tz.name as size,
        ts.id as tshirt_id,
        o.tournamentEdition_id as tournamentEdition_id
      from tournamentTShirt o 
        left join tshirt ts on o.tshirt_id = ts.id
        left join color tc on ts.color_id = tc.id
        left join size tz on ts.size_id = tz.id
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament',
      'color' => 'color'
    );
    
    public static $children = array();

    public function getBuyers() {
      $query = player::$select.'
        left join personTShirt pt
          on pt.person_id = o.person_id
        where pt.id is not null
          and o.tournamentEdition_id = '.config::$currentTournament.'
          '.((isset($this) && $this instanceof tshirt && $this->tournamentTShirt_id) ? 'and pt.tournamentTShirt_id = '.$this->tournamentTShirt_id : '').'
        order by p.name
      ';
    }

  }

?>