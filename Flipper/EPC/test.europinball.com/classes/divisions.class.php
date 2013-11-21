<?php

  class divisions extends group {
    
    public static $objClass = 'division';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>