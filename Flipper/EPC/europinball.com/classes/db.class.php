<?php

  class db extends PDO {

    public function __construct() {
      parent::__construct('mysql:host='.config::$dbhost.';dbname='.config::$dbname.';charset='.config::$charset, config::$dbuser, config::$dbpass);
      $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function insert($query, $values = NULL) {
      if ($this->action($query, $values)) {
        return $this->lastInsertId();
      } else {
        return FALSE;
      }
    }

    public function update($query, $values = NULL) {
      $sth = $this->action($query, $values);
      if ($sth) {
        $rowCount = $sth->rowCount();
        return ($rowCount == 0) ? TRUE : $rowCount;
      } else {
        return FALSE;
      }
    }

    public function delete($query, $values = NULL) {
      $sth = $this->action($query, $values);
      if ($sth) {
        $rowCount = $sth->rowCount();
        return ($rowCount == 0) ? TRUE : $rowCount;
      } else {
        return FALSE;
      }
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
      return $sth;
    }

    public function select($query, $values = NULL, $class = NULL) {
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
      return ($class) ? $this->getRows($sth, $class) : $this->getRows($sth);
    }
    
    protected function getRow($sth, $class = null) {
      $rowCount = $this->getRowCount();
      if ($rowCount > 1) {
        die('Error: Single object expected, '.$rowCount.' objects found...');
      } else if ($rowCount == 1) {
        $obj = $sth->fetchObject();
        return $obj;
      } else {
        return FALSE;
      }
    }

    protected function getRows($sth, $class = null) {
      $rowCount = $this->getRowCount();
      if ($rowCount > 0) {
        $objs = ($class) ? new $class::$arrClass() : array();
        while ($obj = $sth->fetchObject($class)) {
          $objs[] = $obj;
        }
        return $objs;
      }
      return FALSE;
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
          $query .= ' '.$cond.' '.$key.(($value === NULL) ? ' is ' : ' = ').self::getAlias($key);
          $values[self::getAlias($key)] = $value;
        } else {
          $query = $class::$select.' where '.$key.(($value === NULL) ? ' is ' : ' = ').self::getAlias($key);
          $values = array(self::getAlias($key) => $value);
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
      $parentTable = (property_exists($parent, 'table')) ? $parentClass::$table : $parentClass;
      $column = ($column) ? $column : $parentTable.'_id';
      $query = $class::$select.' where o.'.$column.' = :parentId'.(($parentClass::$selfParent) ? ' or parent'.ucfirst($column).' = :parentId' : '');
      $values[':parentId'] = $parent->id;
      $sth = $this->prepare($query);
      if (!$sth->execute($values)) {
        return FALSE;
      }
      return $this->getRows($sth, $class);
    }
    
    public static function getAlias($prop) {
      $alias = ':'.preg_replace('/[^a-zA-Z0-9_]/', '', $prop);
      return ($alias && $alias != ':') ? $alias : FALSE;
    }
    
    public function getRowCount() {
      return $this->query("SELECT FOUND_ROWS()")->fetchColumn();
    }
    
    public function getColNames($table) {
      if ($table) {
        $query = '
          select column_name 
            from information_schema.columns 
            where table_schema = :db 
              and table_name = :table
              and column_name != "id"
        ';
        $values[':db'] = config::$dbname;
        $values[':table'] = $table;
        $sth = $this->prepare($query);
        if ($sth->execute($values)) {
          return $sth->fetchAll(PDO::FETCH_COLUMN);
        }
      }
      return FALSE;
    }
    
    public function seqWaiting($division) {
      $division = getDivision($division);
      if (isDivision($division)) {
        $query = '
          update player 
            right join (
              SELECT @rownum := @rownum +1 seq, 
                id AS pid, 
                waiting
              FROM player, 
                (SELECT @rownum :=0)r
              WHERE waiting =1
                and tournamentDivision_id = '.$division->id.'
            ) AS players
            ON players.pid = player.id
          set player.waiting = players.seq
        ';
      }
      return $this->update($query);
    } 

  }

?>