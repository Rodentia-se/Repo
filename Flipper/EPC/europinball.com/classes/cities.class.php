<?php

  class cities extends geoGroup {
    
    public static $objClass = 'city';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>