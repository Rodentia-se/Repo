<?php

  class tshirtOrders extends group {
    
    public static $objClass = 'tshirtOrder';
    
    public static $order = array(
      'color_id',
      'size_id'
    );

    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>