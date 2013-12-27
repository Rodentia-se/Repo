<?php

  class continents extends group {
    
    public static $objClass = 'continent';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      if (isTournament($data) || in_array($data, array('active', 'current'))) {
        $tournament = tournament($data);
        $context = $tournament;
        if (isDivision($prop)) {
          $context = $prop;
        } else if (in_array($prop, config::$divisions)) {
          $context = division($tournament, $prop);
        }
      } 
      if (isDivision($data)) {
        $context = $data;
      } else if (in_array($data, config::$divisions)) {
        $tournament = getTournament($prop);
        $context = division($tournament, $data);
      }
      if ($context) {
        $data = '
          left join player pl 
            on pl.continent_id = o.id
          where pl.tournament'.((isTournament($context)) ? 'Edition' : 'Division').'_id = '.$context->id.'
            and pl.id is not null
        ';
        $prop = NULL;
      }
      parent::__construct($data, $prop, $cond);
    } 

  }

?>