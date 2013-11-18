<?php

  class db extends PDO {
    
    private $host = 'localhost';
    private $name = 'epc_test';
    private $user = 'epc';
    private $pass = 'vLdqLYyvxSZermEv';
    private $charset = 'utf8';
    private $sth;
    
    public function __construct() {
      parent::__construct('mysql:host='.$this->host.';dbname='.$this->name.';charset='.$this->charset, $this->user, $this->pass);
      $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      unset($this->host);
      unset($this->name);
      unset($this->user);
      unset($this->pass);
      unset($this->charset);
    }
    
    public function getObjectById($class, $id) {
      if (array_key_exists('ID'.$id, $class::$instances)) {
        return $class::$instances['ID'.$id];
      } else {
        $query = $class::$select.' where o.id = '.$id;
        $this->sth = $this->query($query);
        $obj = $this->sth->fetchObject($class);
        unset($obj->db->sth);
        if ($this->last_row_count()) {
          $class::$instances['ID'.$id] = $obj;
          return $class::$instances['ID'.$id];
        } else {
          return FALSE;
        }
      }
    }
    
    public function getObjectsByParent($class, $parent, $column = null) {
      $column = ($column) ? $column : get_class($parent).'_id';
      $parentClass = get_class($parent);
      $query = $class::$select.' where o.'.$column.' = '.$parent->id.(($parentClass::$selfParent) ? ' or parent'.ucfirst($column).' = '.$parent->id : '');
      echo $query;
      $this->sth = $this->query($query);
      pre_dump($this->sth);
      while($obj = $this->sth->fetchObject($class)) {
        $class::$instances['ID'.$id] = $obj;
        $objs[] = $obj;
      }
      unset($obj->db->sth);
      return $objs;
    }

    public function last_row_count() {
      return $this->query("SELECT FOUND_ROWS()")->fetchColumn();
    }

  }


?>