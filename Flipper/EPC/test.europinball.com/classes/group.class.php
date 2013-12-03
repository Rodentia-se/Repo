<?php

  abstract class group extends ArrayObject {
    
    public static $objClass = 'group';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct();
      if (!base::$_db) {
        base::$_db = new db();
      } 
      $this->db = base::$_db;
      if (isAssoc($data)) {
        $objs = $this->db->getObjectsByProps(static::$objClass, $data, $cond);
      } else if (is_array($data)) {
        $objs = [];
        foreach ($data as $obj) {
          if ($obj->id) {
            if (get_class($data) == static::$objClass) {
              $objs[] = $data;
            } else if (is_string($prop)) {
              $objs = $objs->array_merge($this->db->getObjectsByProp(static::$objClass, $prop, $obj->id));
            } else {
              $objs = $objs->array_merge($this->db->getObjectsByProp(static::$objClass, get_class($obj).'_id', $obj->id));
            }
          } else if (is_int($obj)) {
            $objs[] = $this->db->getObjectById(static::$objClass, $obj);
          }
        }
      } else if (is_object($data) && $data->id && is_string($prop)) {
        $objs = $this->db->getObjectsByProp(static::$objClass, $prop, $data->id);
      } else if (is_object($data) && $data->id) {
        if (get_class($data) == static::$objClass) {
          $objs = array($data);
        } else {
          $prop = (property_exists($data, 'table')) ? get_class_vars(get_class($data))['table'] : get_class($data);
          $objs = $this->db->getObjectsByProp(static::$objClass, $prop.'_id', $data->id);
        }
      } else if ($data && is_string($prop)) {
        $objs = $this->db->getObjectsByProp(static::$objClass, $prop, $data);
      } else if (is_string($data) && preg_match('/^where /', trim($data))) {
        $objs = $this->db->getObjectsByWhere(static::$objClass, $data);
      }
      if ($objs) {
        $objs = $objs->array_unique(SORT_REGULAR);
        foreach ($objs as $obj) {
          $this[] = $obj;
        }
      }
    }

    public function __call($func, $argv) {
      if (!is_callable($func) || substr($func, 0, 6) !== 'array_') {
        throw new BadMethodCallException(__CLASS__.'->'.$func);
      }
      return call_user_func_array($func, array_merge(array($this->getArrayCopy()), $argv));
    }

    public function nullify($field, $value = NULL, $cond = 'or') {
      if (count($this) > 0) {
        foreach($this as $obj) {
          $return = $obj->nullify($field, $value);
          if (!$return) {
            return FALSE;
          }
        }
        return TRUE;
      } else if ($field) {
        $table = (property_exists(static::$objClass, 'table')) ? get_class_vars(static::$objClass)['table'] : static::$objClass;
        $update = 'update '.$table.' set '.$field.' = null where 1 = :one';
        $values[':one'] = 1;
        if (isAssoc($value)) {
          $update .= ' and (';
          foreach ($value as $col => $val) {
            $updates[] = $col .' = '.db::getAlias($col);
            $values[db::getAlias($col)] = $val;
          }
          $update .= implode($updates, ' '.$cond.' ').')';
        } else if (is_array($value)) {
          foreach ($value as $val) {
            $i++;
            $updates[] = $field .' = '.db::getAlias($field).$i;
            $values[db::getAlias($field).$i] = $val;
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
    
    public function filter($prop, $value = NULL, $out = FALSE) {
      foreach ($this as $index => $obj) {
        if (isAssoc($prop)) {
          foreach ($prop as $key => $val) {
            if (($obj->$prop == $val) == ($out)) {
              unset($this[$index]);
            }
          }
        } else if ($prop) {
          if ($value) {
            if (($obj->$prop == $value) == ($out)) {
              unset($this[$index]);
            }
          } else {
            if (($obj->$prop) == ($out)) {
              unset($this[$index]);
            }
          }
        }
      }
      return FALSE;
    }

  }
?>