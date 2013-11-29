<?php

  class matchPlayer extends base {
    
    public static $instances;
    public static $arrClass = 'matchPlayers';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.match_id as match_id,
        o.player_id as player_id,
        p.firstName as firstName,
        p.lastName as lastName,
        concat(ifnull(p.firstName, ""), " ", ifnull(p.lastName, "")) as fullName,
        p.initials as shortName,
        p.city_id as city_id,
        p.country_id as country_id,
        o.order as order,
        o.points as points,
        o.place as place,
        o.comment as comment
      from matchPlayer o
      left join player p
        on o.player_id = p.id
    ';

    public static $parents = array(
      'match' => 'match',
      'person' => 'person',
      'player' => 'player',
      'city' => 'city',
      'country' => 'country'
    );
    
    public static $children = array(
      'matchScore' => 'matchPlayer'
    );

  }

?>