<?php

  class ifpaPersons extends persons {
    
    public static $objClass = 'ifpaPerson';
    public static $all = array();
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
    public function updateReq($req = TRUE) {
      foreach ($this as $person) {
        if (!$this->setProp('ifpaUpdateReq', (($req) ? 1 : 0))) {
          $fail = TRUE;
        }
      }
      return !$fail;
    }

  }

?>