<?php

  class entries extends group {
    
    public static $objClass = 'entry';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>