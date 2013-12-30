<?php

  class countries extends geoGroup {
    
    public static $objClass = 'country';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>