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
      } else if (is_object($data) && $data->id) {
        $objs = $this->db->getObjectsByProp(static::$type, get_class($data).'_id', $data->id);
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

  }
?>