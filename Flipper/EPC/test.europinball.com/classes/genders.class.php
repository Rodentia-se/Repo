<?php

  class genders extends group {
    
    public static $objClass = 'gender';
    public static $all = array();
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>