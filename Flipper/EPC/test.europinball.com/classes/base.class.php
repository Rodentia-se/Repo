<?php

  abstract class base implements JsonSerializable {
    
    public static $_db;
    public static $instances;
    public static $parents = array();
    public static $selfParent = FALSE;
    public static $parentDepth = 0;
    public static $count = 0;

    public function __construct($data = NULL, $search = NULL, $depth = NULL) {
      $depth = (preg_match('/^[0-9]+$/', $depth)) ? $depth : config::$parentDepth;
      if (!self::$_db) {
        self::$_db = new db();
      } 
      $this->db = self::$_db;
      if (!static::$instances)  {
        static::$instances = (property_exists($this, 'arrClass')) ? new static::$arrClass : array();
      }
      if ($search) {
        if (isAssoc($data)) {
          $obj = $this->db->getObjectByProps(get_class($this), $data);
        } else if ($data) {
          $obj = $this->db->getObjectByProp(get_class($this), $search, $data);
        }
        if ($obj) {
          $this->_set($obj);
        } else {
          $this->failed = TRUE;
        }
      } else {
        if ($data) {
          if (preg_match('/^[0-9]+/', $data)) {
            if (is_object(static::$instances['ID'.$data])) {
              $obj = static::$instances['ID'.$data];
            } else {
              $obj = $this->db->getObjectById(get_class($this), $data);
            }
            if ($obj) {
              $this->_set($obj);
            } else {
              $this->failed = TRUE;
            }
          } else if (is_string($data) && is_object(json_decode($data))) {
            $this->_set(json_decode($data));
          } else if (is_array($data) || is_object($data)) {
            $this->_set($data);
          }
        }
      }
      if ($this->id) {
        static::$instances['ID'.$this->id] = $this;
        $this->populate($depth);
      }
    }

    protected function _set($data) {
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }
      return TRUE;
    }
    
    public function __get($prop) {
      if (property_exists($this, $prop)) {
       return $this->$prop;
      } else if (property_exists($this, $prop.'_id')) {
        if ($this->{$prop.'_id'} && !$this->$prop) {
          self::$parentDepth = 0;
          $this->populate();
          return $this->$prop;
        }
      } else {
        return NULL;
      }
    }
    
    public function save($propagate = FALSE) {
      if ($this->id) {
        $return = $this->update();
      } else {
        $return = $this->add();
      }
      if ($propagate) {
        // Propagate
      }
      return $return;
    }
    
    public function getColNames() {
        $table = (property_exists($this, 'table')) ? static::$table : get_class($this);
        return $this->db->getColNames($table);
    }

    protected function update() {
      if ($this->id) {
        $cols = $this->getColNames();
        $query = 'update '.$table.' set ';
        $array = $this->getQueryArray($cols, ', ');
        $query .= $array['update'].' where id = '.$this->id;;
        return $this->db->update($query, $array['values']);
      }
      return false;
    }
    
    protected function add() {
      $table = (property_exists($this, 'table')) ? static::$table : get_class($this);
      $cols = $this->db->getColNames($table);
      $query = 'insert into '.$table.' set ';
      $array = $this->getQueryArray($cols, ', ');
      $query .= $array['update'];
      return $this->db->insert($query, $array['values']);
    }
    
    protected function getQueryArray($cols = NULL, $cond = 'or') {
      $obj = get_object_vars($this);
      $cols = ($cols) ? $cols : array_keys($obj);
      foreach ($cols as $col) {
        $prop = (static::$cols[$col]) ? static::$cols[$col] : $col;
        $updates[] = $col .' = :'.preg_replace('/[^a-zA-Z0-9_]/', '', $col);
        $values[':'.preg_replace('/[^a-zA-Z0-9_]/', '', $col)] = $obj[$prop];
      }
      return array('update' => implode($updates, $cond), 'values' => $values);
    }

    public function getLink($type = 'object') {
      switch ($type) {
        case 'photo':
          return FALSE;
        break;
        case 'object':
        default:
          return FALSE;
        break;
      }
    }

    protected function populate($depth = NULL) {
      $depth = ($depth || $depth == 0) ? $depth : config::$parentDepth;
      if (self::$parentDepth < $depth) {
        self::$parentDepth++;
        foreach (static::$parents as $field => $class) {
          if ($this->{$field.'_id'}) {
            $this->{$field.'ParentDepth'} = self::$parentDepth;
            if (is_object($class::$instances['ID'.$this->{$field.'_id'}])) {
              $this->$field = $class::$instances['ID'.$this->{$field.'_id'}];
            } else {
              $this->$field = $class($this->{$field.'_id'});
            }
            $this->{$field.'Name'} = $this->$field->name;
          }
        }
        self::$parentDepth--;
      }
    }
    
    public function delete($propagate = TRUE) {
      $table = (property_exists($this, 'table')) ? static::$table : get_class($this);
      if ($this->id) {
        $delete = (property_exists($this, 'delete')) ? static::$delete : 'delete from '.$table.' where id = :id';
        if ($this->db->delete($delete, array('id' => $this->id))) {
          if ($propagate) {
            foreach (static::$children as $class => $target) {
              if (isAssoc($target)) {
                $field = ($target['field']) ? $target['field'] : $field;
                $delete = ($target['delete']) ? $target['delete'] : NULL;
              } else {
                $field = (is_string($target)) ? $target : get_class($parent);
              }
              if ($delete) {
                $objs = new $class::$arrClass(array($field = $this->id));
                $objs->delete();
              } else {
                $objs = new $class::$arrClass;
                $objs->nullify(array($field.'_id' => $this->id));
              }
            }
          }
          return TRUE;
        } else {
          return FALSE;
        }
      } else {
        return FALSE;
      }
    }

    public function nullify($field, $value = NULL, $cond = 'or') {
      $table = (property_exists($this, 'table')) ? static::$table : get_class($this);
      if ($field) {
        $update = 'update '.$table.' set '.$field.' = null where id = :id';
        $values[':id'] = $this->id;
        if (isAssoc($value)) {
          $update .= ' and (';
          foreach ($value as $col => $val) {
            $updates[] = $col .' = :'.preg_replace('/[^a-zA-Z0-9_]/', '', $col);
            $values[':'.preg_replace('/[^a-zA-Z0-9_]/', '', $col)] = $val;
          }
          $update .= implode($updates, ' '.$cond.' ').')';
        } else if (is_array($value)) {
          foreach ($value as $val) {
            $i++;
            $updates[] = $field .' = :'.preg_replace('/[^a-zA-Z0-9_]/', '', $field).$i;
            $values[':'.preg_replace('/[^a-zA-Z0-9_]/', '', $field).$i] = $val;
          }
          $update .= implode($updates, ' '.$cond.' ').')';
        } else if ($value) {
          $update .= ' and '.$field.' = :value';
          $values[':value'] = $value;
        }
        if ($this->db->update($update, $values)) {
          return TRUE;
        }
      }
      return FALSE;
    }

/*
    function removeParent($parent, - $target = NULL) {
      $field = ($target) ? $target : get_class($parent).'_id';
      if ($this->$field == $parent->id) {
        return $this->setParent(NULL, $target);
      } else {
        return FALSE;
      }
    }
    
    function setParent($parent, $target = NULL)
      $parent = ($parent) ? $parent : $parent();
      $table = (property_exists($this, 'table')) ? static::$table : get_class($this);
      if (isAssoc($field)) {
        $table = ($field['table']) ? $field['table'] : $table;
        $field = ($field['field']) ? $field['field'] : $field;
      } else {
        $field = (is_string($field)) ? $field : get_class($parent).'_id';
      }
      return $this->db->update('update '.$table.' set '.$field.' = :parent where id = :id', array(':parent' => $parent->id, ':id' => $this->id));
    }
*/

    public function jsonSerialize() {
      self::$parentDepth = 999999;
      return $this;
    }
    
    public function flatten() {
      foreach (static::$parents as $field => $class) {
        unset($this->$field);
      }
    }
    
    public function getFlat() {
      $obj = clone $this;
      foreach (static::$parents as $field => $class) {
        unset($obj->$field);
      }
      return $obj;
    }

  }
?>