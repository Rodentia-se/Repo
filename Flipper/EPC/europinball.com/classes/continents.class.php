<?php

  class continents extends geoGroup {
    
    public static $objClass = 'continent';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }

  }

?>