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
            } else {
              if (!is_string($prop)) {
                $prop = (property_exists($data, 'table')) ? get_class_vars(get_class($data))['table'].'_id' : get_class($data).'_id';
              }
              $objs = $objs->array_merge($this->db->getObjectsByProp(static::$objClass, $prop, $obj->id));
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
      } else if ($data == 'all') {
        $class = static::$objClass;
        $query = $class::$select;
        $objs = $this->db->getObjects($query, $class);
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
              $unset = TRUE;
            }
          }
        } else if ($prop) {
          if ($value) {
            if (($obj->$prop == $value) == ($out)) {
              unset($this[$index]);
              $unset = TRUE;
            }
          } else {
            if (($obj->$prop) == ($out)) {
              unset($this[$index]);
              $unset = TRUE;
            }
          }
        }
      }
      return $unset;
    }
    
    public static function _getSelect($id = NULL, $class = NULL, $label = TRUE, $selected = NULL, $add = FALSE, $objs = NULL) {
      $group = new group();
      if ($objs && count($objs) > 0) {
        foreach ($objs as $key => $obj) {
          if (is_int($obj->id)) {
            if ($group[$obj->id]) {
              if ($group[$obj->id]->id == $obj->id) {
                $group[$obj->id] = $obj;
              } else if ($group[$obj->id]->id) {
                $group[$group[$obj->id]->id] = $group[$obj->id];
                $group[$obj->id] = $obj;
              } else {
                $group[$obj->id]->id = end($group->array_keys()) + 1;
                $group[$group[$obj->id]->id] = $group[$obj->id];
                $group[$obj->id] = $obj;
              }
            } else {
              $group[$obj->id] = $obj;
            }
          } else {
            $obj->id = end($group->array_keys()) + 1;
            $group[$obj->id] = $obj;
          }
        }
      }
      return $group->getSelect($id, $class, $label, $selected, $add);
    }

    public function getSelect($id = NULL, $class = NULL, $label = TRUE, $selected = NULL, $add = FALSE) {
      $id = ($id) ? $id : static::$objClass;
      $label = ($label === TRUE) ? $id : $label;
      $selectedId = (is_object($select) && $selected->id) ? $selected->id : (($selected) ? $selected : 0);
      $select = ($label) ? '<label'.(($id) ? ' for="'.$id.'" id="'.$id.'Label"' : '').' class="'.(($class) ? $class.'Label ' : '').'label">'.$label.'</label>' : '';
      $select .= '
        <select'.(($id) ? ' id="'.$id.'" name="'.$id.'"' : '').(($class) ? ' class="'.$class.'"' : '').' previous="'.$selectedId.'">
          <option value="0"></option>
      ';
      if (count($this) > 0) {
        foreach ($this as $obj) {
          $select .= '<option value="'.$obj->id.'"'.(($obj->id == $selectedId) ? ' selected' : '').'>'.$obj->name.'</option>';
        }
      }
      $select .= '</select>';
      if ($add) {
        $select .= page::getIcon('images/add_icon.gif', 'add_'.static::$objClass, 'addIcon editIcon', 'Click to add new '.static::$objClass);
      }
      return $select;
    }
    
  }
?>