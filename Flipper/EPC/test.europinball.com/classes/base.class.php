<?php

  abstract class base {
    
    public static $_db;
    public static $instances = array();
    public static $parents = array();
    public static $selfParent = FALSE;

    public function __construct($data = NULL, $depth = 0) {
      if (!self::$_db) {
        self::$_db = new db();
      } 
      $this->db = self::$_db;
      if ($data) {
        if (preg_match('/^[0-9]+/', $data)) {
          if (is_object(static::$instances['ID'.$data])) {
            $obj = static::$instances['ID'.$data];
          } else {
            $obj = $this->db->getObjectById(get_class($this), $data);
          }
          if ($obj) {
            $this->_set($obj);
          }
        } else if (is_string($data) && is_object(json_decode($data))) {
          $this->_set(json_decode($data));
        } else if (is_array($data)) {
          $this->_set($data);
        }
      }
      if ($this->id) {
        static::$instances['ID'.$this->id] = $this;
      }
      echo $depth;
      if ($depth < config::$depth) {
        $this->populate($depth);
      }
    }
    
    protected function _set($data) {
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }
    }
    
    protected function populate($depth) {
      foreach (static::$parents as $field => $class) {
        if ($depth < config::$depth) {
          if ($this->{$field.'_id'}) {
            if (is_object($class::$instances['ID'.$this->{$field.'_id'}])) {
              $this->$field = $class::$instances['ID'.$this->{$field.'_id'}];
            } else {
              $this->$field = new $class($this->{$field.'_id'}, $depth + 1);
            }
          }
        }
      }
    }

  }
?>