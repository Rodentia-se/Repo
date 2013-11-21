<?php

  class teams extends group {
    
    public static $objClass = 'team';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>