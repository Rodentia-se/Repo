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
        $sth = $this->prepare($query);
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
        $sth = $this->prepare($query);
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
        preDump($sth);
        die('huff');
      $rowCount = $this->getRowCount();
      if ($rowCount > 1) {
        die('Error: Single object expected, '.$rowCount.' objects found...');
      } else if ($rowCount == 1) {
        $obj = $sth->fetchObject($class);
        return $obj;
      } else {
        return FALSE;
      }
    }

    protected function getRows($sth, $class = null) {
      $rowCount = $this->getRowCount();
      if ($rowCount > 0) {
        while($obj = $sth->fetchObject($class)) {
          $objs[] = $obj;
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
    
    protected function getObjectsByPropsHelper($class, $props, $cond = 'and') {
      foreach ($props as $key => $value) {
        $key = (preg_match('/\./', $key)) ? $key : 'o.'.$key; 
        if ($where) {
          $query .= ' '.$cond.' '.$key.' = :'.preg_replace('/[^a-zA-Z0-9_]/', '', $key);
          $values[':'.preg_replace('/[^a-zA-Z0-9_]/', '', $key)] = $value;
        } else {
          $query = $class::$select.' where '.$key.' = :'.preg_replace('/[^a-zA-Z0-9_]/', '', $key);
          $values = array(':'.preg_replace('/[^a-zA-Z0-9_]/', '', $key) => $value);
          $where = true;
        }
      }
      $sth = $this->prepare($query);
      if (!$sth->execute($values)) {
        return FALSE;
      }
      return $sth;
    }
    
    public function getObjectByProps($class, $props, $cond = 'and') {
      $sth = $this->getObjectsByPropsHelper($class, $props, $cond);
      return $this->getRow($sth, $class);
    }

    public function getObjectByProp($class, $prop, $value) {
      $props[$prop] = $value;
      return $this->getObjectByProps($class, $props);
    }

    public function getObjectByWhere($class, $where = 1) {
      $query = $class::$select.' '.((preg_match('/^where /', trim($where))) ? '' : 'where ').$where;
      $sth = $this->query($query);
      if (!$sth) {
        return FALSE;
      }        
      return $this->getRow($sth, $class);
    }

    public function getObjectsByProps($class, $props, $cond = 'and') {
      $sth = $this->getObjectsByPropsHelper($class, $props, $cond);
      return $this->getRows($sth, $class);
    }

    public function getObjectsByProp($class, $prop, $value) {
      $props[$prop] = $value;
      return $this->getObjectsByProps($class, $props);
    }

    public function getObjectsByWhere($class, $where = 1) {
      $query = $class::$select.' '.((preg_match('/^where /', trim($where))) ? '' : 'where ').$where;
      $sth = $this->query($query);
      if (!$sth) {
        return FALSE;
      }        
      return $this->getRows($sth, $class);
    }

    public function getObjectById($class, $id) {
      return $this->getObjectByProp($class, 'o.id', $id);
    }
    
    public function getObjectsByParent($class, $parent, $column = null) {
      $parentClass = get_class($parent);
      $column = ($column) ? $column : $parentClass.'_id';
      $query = $class::$select.' where o.'.$column.' = :parentId'.(($parentClass::$selfParent) ? ' or parent'.ucfirst($column).' = :parentId' : '');
      $values[':parentId'] = $parent->id;
      $sth = $this->prepare($query);
      if (!$sth->execute($values)) {
        return FALSE;
      }
      return $this->getRows($sth, $class);
    }
    
    public function getRowCount() {
      return $this->query("SELECT FOUND_ROWS()")->fetchColumn();
    }
    
  }

?>