<?php

  class entries extends group {
    
    public static $objClass = 'entry';
    public static $all = array();
    public static $order = array(
      'prop' => 'bestPoints',
      'type' => 'numeric',
      'dir' => 'desc'
    );

    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      debug($prop);
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>