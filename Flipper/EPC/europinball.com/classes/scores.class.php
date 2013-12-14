<?php

  class scores extends group {
    
    public static $objClass = 'score';
    public static $order = array(
      'prop' => 'points',
      'type' => 'numeric',
      'dir' => 'desc'
    );
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>