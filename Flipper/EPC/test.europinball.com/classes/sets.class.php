<?php

  class sets extends group {
    
    public static $objClass = 'set';
    public static $order = array(
      'prop' => 'id',
      'type' => 'numeric',
      'dir' => 'asc'
    );
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>