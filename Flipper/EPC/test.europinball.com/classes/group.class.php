<?php

  abstract class group extends ArrayObject {
    
    public static $objClass = 'group';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      if (!base::$_db) {
        base::$_db = new db();
      } 
      $this->db = base::$_db;
      if (isAssoc($data)) {
        $objs = $this->db->getObjectsByProps(static::$objClass, $data, $cond);
      } else if (is_object($data) && $data->id && is_string($prop)) {
        $objs = $this->db->getObjectsByProp(static::$objClass, $prop, $data->id);
      } else if (is_object($data) && $data->id) {
        $objs = $this->db->getObjectsByProp(static::$objClass, get_class($data).'_id', $data->id);
      } else if ($data && is_string($prop)) {
        $objs = $this->db->getObjectsByProp(static::$objClass, $prop, $data);
      } else if (is_string($data) && preg_match('/^where /', trim($data))) {
        $objs = $this->db->getObjectsByWhere(static::$objClass, $data);
      }
      parent::__construct();
      if ($objs) {
        foreach ($objs as $obj) {
          $this[] = $obj;
        }
      }
    }

  }
?>