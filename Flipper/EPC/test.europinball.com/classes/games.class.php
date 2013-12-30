<?php

  class games extends group {
    
    public static $objClass = 'game';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      if (isTournament($data) || in_array($data, array('active', 'current'))) {
        $tournament = tournament($data);
        $context = $tournament;
        $secondParam = 'prop';
        if (isDivision($prop)) {
          $context = $prop;
        } else if (in_array($prop, config::$divisions)) {
          $context = division($tournament, $prop);
        }
      }
      if (isDivision($data)) {
        $context = $data;
        $secondParam = 'prop';
      } else if (in_array($data, config::$divisions)) {
        $tournament = getTournament($prop);
        $context = division($tournament, $data);
        $secondParam = 'prop';
      }
      if (isTournament($prop) || in_array($prop, array('active', 'current'))) {
        $tournament = tournament($prop);
        $context = $tournament;
        $secondParam = 'data';
        if (isDivision($prop)) {
          $context = $prop;
        } else if (in_array($prop, config::$divisions)) {
          $context = division($tournament, $prop);
        }
      } 
      if (isDivision($prop)) {
        $context = $prop;
        $secondParam = 'data';
      } else if (in_array($prop, config::$divisions)) {
        $tournament = getTournament($prop);
        $context = division($tournament, $prop);
        $secondParam = 'data';
      }
      if ($context) {
        $class = static::$objClass;
        $column = (property_exists($class, 'table')) ? $class::$table.'_id' : $class.'_id';
        if (isObj($$secondParam)) {
          $secondColumn = (property_exists($$secondParam, 'table')) ? get_class_vars(get_class($$secondParam))['table'].'_id' : get_class($$secondParam).'_id';
          $secondId = $$secondParam->id;
        }
        $data = '
          left join machine m 
            on m.'.$column.' = o.id
          where m.tournament'.((isTournament($context)) ? 'Edition' : 'Division').'_id = '.$context->id.'
            '.((isObj($$secondParam)) ? 'and o.'.$secondColumn.' = '.$secondId : '').'
            and m.id is not null
          group by o.id
        ';
        $prop = NULL;
        parent::__construct($data, $prop, $cond);
      }
    }
    
  }

?>