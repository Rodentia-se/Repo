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
              $objs = array_merge($objs, $this->db->getObjectsByProp(static::$objClass, $prop, $obj->id));
            } else {
              $objs = array_merge($objs, $this->db->getObjectsByProp(static::$objClass, get_class($obj).'_id', $obj->id));
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
          $objs = $this->db->getObjectsByProp(static::$objClass, get_class($data).'_id', $data->id);
        }
      } else if ($data && is_string($prop)) {
        $objs = $this->db->getObjectsByProp(static::$objClass, $prop, $data);
      } else if (is_string($data) && preg_match('/^where /', trim($data))) {
        $objs = $this->db->getObjectsByWhere(static::$objClass, $data);
      }
      if ($objs) {
        $objs = array_unique($objs, SORT_REGULAR);
        foreach ($objs as $obj) {
          $this[] = $obj;
        }
      }
    }

    function nullify($field, $value = NULL, $cond = 'or') {
      if (count($this) > 0) {
        foreach($this as $obj) {
          $return = $obj->nullify($field, $value);
          if (!$return) {
            return FALSE;
          }
        }
        return TRUE;
      } else if ($field) {
        $table = (property_exists(static::$objClass, 'table')) ? static::$objClass::$table : static::$objClass;
        $update = 'update '.$table.' set '.$field.' = null where 1 = :one';
        $values[':one'] = 1;
        if (isAssoc($value)) {
          $update .= ' and (';
          foreach ($value as $col => $val) {
            $updates[] = $col .' = :'.preg_replace('/[^a-zA-Z0-9_]/', $col);
            $values[':'.preg_replace('/[^a-zA-Z0-9_]/', $col)] = $val;
          }
          $update .= implode($updates, ' '.$cond.' ').')';
        } else if (is_array($value)) {
          foreach ($value as $val) {
            $i++;
            $updates[] = $field .' = :'.preg_replace('/[^a-zA-Z0-9_]/', $field).$i;
            $values[':'.preg_replace('/[^a-zA-Z0-9_]/', $field).$i] = $val;
          }
          $update .= implode($updates, ' '.$cond.' ').')';
        } else if ($value) {
          $update .= ' and '.$field.' = :value';
          $values[':value'] = $value;
        }
        if ($this->db->update($update, $values) {
          return TRUE;
        }
      }
      return FALSE;
    }

  }
?>