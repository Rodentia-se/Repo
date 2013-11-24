<?php

  abstract class base implements JsonSerializable {
    
    public static $_db;
    public static $instances;
    public static $parents = array();
    public static $selfParent = FALSE;
    public static $parentDepth = 0;

    public function __construct($data = NULL, $search = NULL) {
      if (!self::$_db) {
        self::$_db = new db();
      } 
      $this->db = self::$_db;
      echo (get_class($this) == 'player') ? "HUP".get_class(static::$instances) : '';
      if (!static::$instances && property_exists($this, 'arrClass')) {
        static::$instances = new static::$arrClass;
        echo (get_class($this) == 'player') ? "HUPP".get_class(static::$instances) : '';
      } else {
        static::$instances = array();
      }
      if ($search) {
        if (isAssoc($data)) {
          $obj = $this->db->getObjectByProps(get_class($this), $data);
        } else if ($data) {
          $obj = $this->db->getObjectByProp(get_class($this), $search, $data);
          if ($obj) {
            $this->_set($obj);
          } else {
            return FALSE;
          }
        }
      } else  {
        if ($data) {
          echo (get_class($this) == 'player') ? "HUPP2".get_class(static::$instances) : '';
          if (preg_match('/^[0-9]+/', $data)) {
            if (is_object(static::$instances['ID'.$data])) {
              $obj = static::$instances['ID'.$data];
            } else {
              $obj = $this->db->getObjectById(get_class($this), $data);
            }
            echo (get_class($this) == 'player') ? "HUPP3".get_class(static::$instances) : '';
            if ($obj) {
              $this->_set($obj);
            } else {
              return FALSE;
            }
          } else if (is_string($data) && is_object(json_decode($data))) {
            $this->_set(json_decode($data));
          } else if (is_array($data)) {
            $this->_set($data);
          }
          echo (get_class($this) == 'player') ? "HUPP4".get_class(static::$instances) : '';
        }
        echo (get_class($this) == 'player') ? "HUPP5".get_class(static::$instances) : '';
      }
      echo (get_class($this) == 'player') ? "HUPP6".get_class(static::$instances) : '';
      if ($this->id) {
        static::$instances['ID'.$this->id] = $this;
        $this->populate();
      }
      echo (get_class($this) == 'player') ? "HUPP7".get_class(static::$instances) : '';
    }
    
    protected function _set($data) {
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }
    }
    
    public function __get($prop) {
      if ($this->{$prop.'_id'} && !$this->$prop) {
        self::$parentDepth = 0;
        $this->populate();
      }
      return $this->$prop;
    }
    
    protected function populate() {
      if (self::$parentDepth < config::$parentDepth) {
        self::$parentDepth++;
        foreach (static::$parents as $field => $class) {
          if ($this->{$field.'_id'}) {
            $this->{$field.'ParentDepth'} = self::$parentDepth;
            if (is_object($class::$instances['ID'.$this->{$field.'_id'}])) {
              $this->$field = $class::$instances['ID'.$this->{$field.'_id'}];
            } else {
              $this->$field = new $class($this->{$field.'_id'});
            }
            $this->{$field.'Name'} = $this->$field->name;
          }
        }
        self::$parentDepth--;
      }
    }

    function jsonSerialize() {
      self::$parentDepth = 999999;
      return $this;
    }
    
    function flatten() {
      foreach (static::$parents as $field => $class) {
        unset($this->$field);
      }
    }
    
    function getFlat() {
      $obj = clone $this;
      foreach (static::$parents as $field => $class) {
        unset($obj->$field);
      }
      return $obj;
    }

  }
?>