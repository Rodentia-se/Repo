<?php

  class tshirtOrder extends base {
        
    public static $instances;
    public static $arrClass = 'tshirtOrders';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.name as shortName,
        concat(tc.name, tz.name) as sortName,
        pt.number as number,
        pt.number as number_id,
        pt.person_id as person_id,
        pt.id as playerTshirt_id,
        tc.name as color,
        tc.id as color_id,
        tz.name as size,
        tz.id as size_id,
        tt.id as tournamentTshirt_id,
        tt.tournamentEdition_id as tournamentEdition_id
      from tshirt o 
        left join tournamentTShirt tt on tt.tshirt_id = o.id
        left join color tc on ts.color_id = tc.id
        left join size tz on ts.size_id = tz.id
        left join personTShirt pt on pt.tournamentTShirt_id = tt.id
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
    
    public function delete($propagate = TRUE) {
      parent::delete($propagate);
      //delete tournamentTShirt_id from personTShirt
    }

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