<?php

  class geoGroup extends group {

    public static $objClass = 'geography';

    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>