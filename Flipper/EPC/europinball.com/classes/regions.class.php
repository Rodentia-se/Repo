<?php

  class regions extends geoGroup {
    
    public static $objClass = 'region';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>