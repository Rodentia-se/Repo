<?php

  class owners extends group {
    
    public static $objClass = 'owner';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>