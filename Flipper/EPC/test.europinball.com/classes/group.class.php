<?php

  abstract class group extends ArrayObject {
    
    public static $type = 'group';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      if (!base::$_db) {
        base::$_db = new db();
      } 
      $this->db = base::$_db;
      if (isAssoc($data)) {
        $objs = $this->db->getObjectsByProps(static::$type, $data, $cond);
      } else if (is_object($data) && $data->id && is_string($prop)) {
        $objs = $this->db->getObjectsByProp(static::$type, $prop, $data->id);
      } else if (is_object($data) && $data->id) {
        $objs = $this->db->getObjectsByProp(static::$type, get_class($data).'_id', $data->id);
      } else if ($data && is_string($prop)) {
        $objs = $this->db->getObjectsByProp(static::$type, $prop, $data);
      } else if (is_string($data) && preg_match('/^where /', trim($data))) {
        $objs = $this->db->getObjectsByWhere(static::$type, $data);
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