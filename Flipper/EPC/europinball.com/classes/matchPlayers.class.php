<?php

  class matchPlayers extends group {
    
    public static $objClass = 'matchPlayer';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>