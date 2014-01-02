<?php

  class tshirtOrders extends group {
    
    public static $objClass = 'tshirtOrder';
    public static $all = array();
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      if (isTournament($data) || in_array($data, array('active', 'current'))) {
        $tournament = tournament($data);
        $context = $tournament;
        $secondParam = 'prop';
      }
      if (isTournament($prop) || in_array($prop, array('active', 'current'))) {
        $tournament = tournament($prop);
        $context = $tournament;
        $secondParam = 'data';
      } 
      if ($context) {
        $class = static::$objClass;
        if (isObj($$secondParam)) {
          $secondColumn = (property_exists($$secondParam, 'table')) ? get_class_vars(get_class($$secondParam))['table'].'_id' : get_class($$secondParam).'_id';
          $secondId = $$secondParam->id;
        }
        $data = '
          where tt.tournamentEdition_id = '.$context->id.'
            '.((isObj($$secondParam)) ? 'and o.'.$secondColumn.' = '.$secondId : '').'
            and tt.id is not null
          group by o.id
        ';
        $prop = NULL;
      }
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>