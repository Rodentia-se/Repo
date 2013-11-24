<?php

  class tournament extends base {
    
    public static $instances;
    public static $arrClass = 'tournaments';

    public static $select = '
      select 
        o.id as id,
        t.id as tournament_id,
        t.name as tournamentName,
        o.name as name,
        concat(t.name, " ", left(o.startDate,4)) as fullName,
        o.name as shortName,
        t.acronym as acronym,
        o.startDate as startDate,
        o.endDate as endDate,
        o.location_id as location_id,
        o.comment as comment
      from tournamentEdition o 
      left join tournament t
        on o.tournament_id = t.id
    ';
    
    public static $parents = array(
      'location' => 'location'
    );
    
    public function getDivisions() {
      return $this->db->getObjectsByParent('division', $this, 'tournamentEdition_id');
    }
  }

?>