<?php

  class db extends PDO {

    public function __construct() {
      parent::__construct('mysql:host='.config::$dbhost.';dbname='.config::$dbname.';charset='.config::$charset, config::$dbuser, config::$dbpass);
      $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function getObjectById($class, $id) {
      if (is_object($class::$instances['ID'.$id])) {
        return $class::$instances['ID'.$id];
      } else {
        $query = $class::$select.' where o.id = '.$id;
        $sth = $this->query($query);
        $obj = $sth->fetchObject($class);
        if ($this->last_row_count()) {
          $class::$instances['ID'.$id] = $obj;
          return $class::$instances['ID'.$id];
        } else {
          return FALSE;
        }
      }
    }
    
    public function getObjectsByParent($class, $parent, $column = null) {
      $parentClass = get_class($parent);
      $column = ($column) ? $column : $parentClass.'_id';
      $query = $class::$select.' where o.'.$column.' = '.$parent->id.(($parentClass::$selfParent) ? ' or parent'.ucfirst($column).' = '.$parent->id : '');
      $sth = $this->query($query);
      while($obj = $sth->fetchObject($class)) {
        $objs[] = $obj;
      }
      return $objs;
    }

    public function last_row_count() {
      return $this->query("SELECT FOUND_ROWS()")->fetchColumn();
    }

  }


?>