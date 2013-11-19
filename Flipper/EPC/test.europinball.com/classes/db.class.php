<?php

  class db extends PDO {

    public function __construct() {
      parent::__construct('mysql:host='.config::$dbhost.';dbname='.config::$dbname.';charset='.config::$charset, config::$dbuser, config::$dbpass);
      $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function insert($query, $values = NULL) {
      return $this->action($query, $values);
    }

    public function update($query, $values = NULL) {
      return $this->action($query, $values);
    }

    public function delete($query, $values = NULL) {
      return $this->action($query, $values);
    }

    protected function action($query, $values = NULL) {
      if ($values) {
        $sth = $dbh->prepare($query);
        if (!$sth->execute($values)) {
          return FALSE;
        }
      } else {
        $sth = $this->query($query);
        if (!$sth) {
          return FALSE;
        }
      }
      $rowCount = $sth->rowCount();
      return ($rowCount == 0) ? TRUE : $rowCount;
    }

    public function select($query, $values = NULL) {
      if ($values) {
        $sth = $dbh->prepare($query);
        if (!$sth->execute($values)) {
          return FALSE;
        }
      } else {
        $sth = $this->query($query);
        if (!$sth) {
          return FALSE;
        }        
      }
      return $this->getRows($sth);
    }
    
    protected function getRow($sth, $class = null) {
      if ($this->getRowCount() == 1) {
        $obj = $sth->fetchObject($class);
        if ($class && $obj->id) {
          $class::$instances['ID'.$obj->id]) = $obj;
          return $class::$instances['ID'.$obj->id]);
        } else {
          return $obj;
        }
      } else {
        return FALSE;
      }
    }

    protected function getRows($sth, $class = null) {
      if ($this->getRowCount() == 1) {
        $obj = $sth->fetchObject($class);
        if ($class && $obj->id) {
          $class::$instances['ID'.$obj->id]) = $obj;
          return $class::$instances['ID'.$obj->id]);
        } else {
          return $obj;
        }
      } else if ($this->getRowCount() > 1) {
        while($obj = $sth->fetchObject($class)) {
          if ($class && $obj->id) {
            $class::$instances['ID'.$obj->id]) = $obj;
            $objs[] = $class::$instances['ID'.$obj->id]);
          } else {
            $objs[] = $obj;
          }
        }
        return $objs;
      } else {
        return FALSE;
      }
    }

    public function getObject($query, $class = NULL) {
      $sth = $this->query($query);
      return $this->getRow($sth, $class);
    }

    public function getObjects($query, $class = NULL) {
      $sth = $this->query($query);
      return $this->getRows($sth, $class);
    }
    
    protected function getObjectsByProps($class, $props, $cond = 'and') {
      foreach ($props as $key => $value) {
        if ($where) {
          $query = $class::$select.' where '.$key.' = :'.$key;
          $values = array(':'.$key => $value);
        } else {
          $query .= ' '.$cond.' '.$key.' = :'.$key;
          $values[':'.$key] = $value;
        }
      }
      $sth = $dbh->prepare($query);
      if (!$sth->execute($values)) {
        return FALSE;
      }
      return $this->getRows($sth, $class);
    }

    public function getObjectsByProp($class, $prop, $value) {
      $props[$prop] = $value;
      return $this->getObjectsByProps($class, $props);
    }

    public function getObjectById($class, $id) {
      if (is_object($class::$instances['ID'.$id])) {
        return $class::$instances['ID'.$id];
      } else {
        return $this->getObjectsByProp($class, 'id', $id);
      }
    }
    
    public function getObjectsByParent($class, $parent, $column = null) {
      $parentClass = get_class($parent);
      $column = ($column) ? $column : $parentClass.'_id';
      $query = $class::$select.' where o.'.$column.' = :parentId'.(($parentClass::$selfParent) ? ' or parent'.ucfirst($column).' = :parentId' : '');
      $values[':parentId'] = $parent->id;
      $sth = $dbh->prepare($query);
      if (!$sth->execute($values)) {
        return FALSE;
      }
      return $this->getRows($sth, $class);
    }
    
    public function getObjectsByWhere($class, $where) {
      
    }

    public function getRowCount() {
      return $this->query("SELECT FOUND_ROWS()")->fetchColumn();
    }
    
  }

?>