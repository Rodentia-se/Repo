<?php

  class tshirtOrders extends group {
    
    public static $objClass = 'tshirtOrder';
    public static $all = array();
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>