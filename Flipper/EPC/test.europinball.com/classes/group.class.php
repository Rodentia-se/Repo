<?php

  abstract class group extends ArrayObject {
    
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
      parent::__construct();
      if ($objs) {
        foreach ($objs as $obj) {
          $this[] = $obj;
        }
      }
    }

/*
    public function offsetSet($index, $value) {
      if (is_null($index)) {
        $this->objects[] = $value;
      } else {
        $this->objects[$index] = $value;
      }
    }

    public function offsetExists($index) {
        return isset($this->objects[$index]);
    }

    public function offsetUnset($index) {
        unset($this->objects[$index]);
    }

    public function offsetGet($index) {
        return isset($this->objects[$index]) ? $this->objects[$index] : null;
    }
*/
    
  }
?>