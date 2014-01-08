<?php

  class ifpaPersons extends persons {
    
    public static $objClass = 'ifpaPerson';
    public static $all = array();
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      debug($data);
      parent::__construct($data, $prop, $cond);
    }
    
    public function updateRank($rank = NULL) {
      $this->setProp('ifpaRank', (($rank) ? (int) $rank : 0));
    }

  }

?>