<?php

  class entries extends group {
    
    public static $objClass = 'entry';
    public static $all = array();
    public static $order = array(
      'prop' => 'fullPoints',
      'type' => 'numeric',
      'dir' => 'desc'
    );

    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
    public function calcPoints($save = TRUE) {
      if (count($this) > 0) {
        foreach ($this as $entry) {
          $entry->calcPoints($save);
        }
        return TRUE;
      }
      return FALSE;
    }
        
    public function calcPlaces($calcPoints = TRUE) {
      if (count($this) > 0) {
        if ($calcPoints) {
          $this->calcPoints();
        }
        $this->order('fullPoints', 'numeric', 'desc');
        $place = 0;
        foreach ($this as $entry) {
          $place++;
          $entry->setPlace((($entry->pooints) ? $place : NULL));
        }
        return TRUE;
      } else {
        return FALSE;
      }
    }

  }

?>