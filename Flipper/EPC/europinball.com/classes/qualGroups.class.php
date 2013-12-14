<?php

  class qualGroups extends group {
    
    public static $objClass = 'qualGroup';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>