<?php

  class machine extends base {
        
    public static $instances;
    public static $arrClass = 'machines';

    public static $select = '
      select 
        o.id as id,
        g.íd as game_id,
        g.name as name,
        g.name as fullName,
        g.acronym as acronym,
        g.acronym as shortName,
        g.manufacturer_id as manufacturer_id,
        g.game_ipdb_id as ipdb,
        g.game_link_rulesheet as rules,
        g.game_year_released as year,
        o.tournamentDivision_id as tournamentDivision_id,
        o.tournamentEdition_id as tournamentEdition_id,
        o.gameType as type,
        o.balls as balls,
        o.extraBalls as extraBalls,
        o.onePlayerAllowed as onePlayerAllowed,
        o.owner_id as owner_id,
        o.paid as paid,
        o.comment as comment
      from machine o
      left join game g
        on o.game_id = g.id
    ';
    
    public static $parents = array(
      'game' => 'game',
      'manufacturer' => 'manufacturer',
      'tournamentDivision' => 'division',
      'tournamentEdition' => 'tournament',
      'owner' => 'owner'
    );

  }

?>