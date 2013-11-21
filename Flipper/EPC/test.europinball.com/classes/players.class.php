<?php

  class players extends group implements ArrayAccess {
    
    public static $type = 'player';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      if (!base::$_db) {
        base::$_db = new db();
      } 
      $this->db = base::$_db;
      if (!$prop && (is_string($data) || is_int($data))) {
        if(preg_match('/@/',$data)) {
          $data = array(
            'o.mailAddress' => trim($data),
            'p.mailAddress' => trim($data)
          );
          $cond = 'or';
        } else if (preg_match('/^[0-9]{1,5}$/', $data)) {
          $prop = 'p.ifpa_id';
          $data = trim($data);
        } else if (preg_match('/^[0-9 \-\+\(\)]{6,}$/',$data)) {
          $where = 'where replace(replace(replace(replace(replace(p.telephoneNumber," ",""),")",""),")",""),"-",""),"+","") like "%'.preg_replace('/[^0-9]/','',$data).'%"';
          $where .= ' or replace(replace(replace(replace(replace(p.mobileNumber," ",""),")",""),")",""),"-",""),"+","") like "%'.preg_replace('/[^0-9]/','',$data).'%"';
          $where .= ' or replace(replace(replace(replace(replace(o.telephoneNumber," ",""),")",""),")",""),"-",""),"+","") like "%'.preg_replace('/[^0-9]/','',$data).'%"';
          $where .= ' or replace(replace(replace(replace(replace(o.mobileNumber," ",""),")",""),")",""),"-",""),"+","") like "%'.preg_replace('/[^0-9]/','',$data).'%"';
          $data = $where;
        } else if (preg_match('/^[a-zA-Z0-9 \-]{3}$/',$data)) {
          $data = array(
            'o.initials' => $data, 
            'p.initials' => $data
          );
          $cond = 'or';
        } else {
          $where = 'where concat(ifnull(o.firstName,""), " ", ifnull(o.lastName,"")) like "%'.trim($data).'%"';
          $where .= ' or concat(ifnull(p.firstName,""), " ", ifnull(p.lastName,"")) like "%'.trim($data).'%"';
          $data = $where;
        }
      }
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>