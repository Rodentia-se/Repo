<?php

  abstract class base {
    
    public static $_db;

    public $db;
    public $id;
    public $name;
    public $fullName;
    public $shortName;
    public $acronym;
    public $photo;
    public $icon;
    public $comment;
    
    public static $parents = array();
    
    public function __construct($data = null) {
      if (!self::$_db) {
        self::$_db = new db();
      } 
      $this->db = self::$_db;
      if ($data) {
        if (preg_match('/^[0-9]+/', $data)) {
          echo get_class($this);
          $obj = $this->db->getObjectById(get_class($this), $data);
          if ($obj) {
            $this->_set($obj);
          }
        } else if (is_string($data) && is_object(json_decode($data))) {
          $this->_set(json_decode($json, true));
        } else if (is_array($data)) {
          $this->_set($data);
        }
      }
      $this->populate();
    }
    
    protected function _set($data) {
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }
    }
    
    protected function populate() {
      var_dump(static::parents);
      foreach (static::parents as $field => $class) {
        $this->$field = new $class($this->{$field.'_id'});
      }
    }

  }
?>