<?php

  class scores extends group {
    
    public static $objClass = 'score';
    public static $all = array();
    public static $order = array(
      'prop' => 'points',
      'type' => 'numeric',
      'dir' => 'desc'
    );
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
    public function setPoints($points = NULL) {
      $this->points = ($points) ? $points : NULL;
      return $this->save();
    }

    public function setPlace($place = NULL) {
      $this->place = ($place) ? $place : NULL;
      return $this->save();
    }

  }

?>