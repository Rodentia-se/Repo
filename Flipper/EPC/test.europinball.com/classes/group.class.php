<?php

  abstract class group implements ArrayAccess {
    
    private $container = array();
    public static $type = 'group';
    
    public function __construct($data = NULL, $prop = NULL) {
      if (!base::$_db) {
        base::$_db = new db();
      } 
      $this->db = base::$_db;
      if (isAssoc($data)) {
        $objs = $this->db->getObjectsByProps(static::$type, $data);
      } else if ($data && $prop) {
        $objs = $this->db->getObjectsByProp(static::$type, $prop, $data);
      }
      if ($objs) {
        foreach ($objs as $obj) {
          $this[] = $obj;
        }
      } else {
        return FALSE;
      }
    }

    public function offsetSet($offset, $value) {
      if (is_null($offset)) {
        $this->container[] = $value;
      } else {
        $this->container[$offset] = $value;
      }
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
    
  }
?>