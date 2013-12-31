<?php

  class matchScore extends base {
    
    public static $instances;
    public static $arrClass = 'matchPlayers';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.matchSet_id as matchSet_id,
        o.match_id as match_id,
        o.matchPlayer_id as matchPlayer_id,
        o.player_id as player_id,
        o.machine_id as machine_id,
        o.score as score,
        o.points as points,
        o.place as place,
        o.comment as comment
      from matchScore o
    ';

    public static $parents = array(
      'set' => 'matchSet',
      'match' => 'match',
      'matchPlayer' => 'matchPlayer',
      'player' => 'player',
      'machine' => 'machine'
    );
    
    public static $children = array(
      'matchScore' => 'matchPlayer'
    );

  }

?>