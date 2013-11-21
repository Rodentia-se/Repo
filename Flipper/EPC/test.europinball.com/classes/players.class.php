<?php

  class players extends group {
    
    public static $objClass = 'player';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      if (!$prop && (is_string($data) || is_int($data))) {
        if(preg_match('/@/',$data)) {
          $data = (static::$objClass == 'player') ? array(
            'o.mailAddress' => trim($data),
            'p.mailAddress' => trim($data)
          ) : array('o.mailAddress' => trim($data));
          $cond = 'or';
        } else if (preg_match('/^[0-9]{1,5}$/', $data)) {
          $prop = (static::$objClass == 'player') ? 'p.ifpa_id' : 'ifpa_id';
          $data = trim($data);
        } else if (preg_match('/^[0-9 \-\+\(\)]{6,}$/',$data)) {
          $where = 'where replace(replace(replace(replace(replace(o.telephoneNumber," ",""),")",""),")",""),"-",""),"+","") like "%'.preg_replace('/[^0-9]/','',$data).'%"';
          $where .= ' or replace(replace(replace(replace(replace(o.mobileNumber," ",""),")",""),")",""),"-",""),"+","") like "%'.preg_replace('/[^0-9]/','',$data).'%"';
          $where .= (static::$objClass == 'player') ? ' or replace(replace(replace(replace(replace(p.telephoneNumber," ",""),")",""),")",""),"-",""),"+","") like "%'.preg_replace('/[^0-9]/','',$data).'%"' : '';
          $where .= (static::$objClass == 'player') ? ' or replace(replace(replace(replace(replace(p.mobileNumber," ",""),")",""),")",""),"-",""),"+","") like "%'.preg_replace('/[^0-9]/','',$data).'%"' : '';
          $data = $where;
        } else if (preg_match('/^[a-zA-Z0-9 \-]{3}$/',$data)) {
          $data = (static::$objClass == 'player') ? array(
            'o.initials' => trim($data),
            'p.initials' => trim($data)
          ) : array('o.initials' => trim($data));
          $cond = 'or';
        } else {
          $where = 'where concat(ifnull(o.firstName,""), " ", ifnull(o.lastName,"")) like "%'.trim($data).'%"';
          $where .= (static::$objClass == 'player') ? ' or concat(ifnull(p.firstName,""), " ", ifnull(p.lastName,"")) like "%'.trim($data).'%"' : '';
          $data = $where;
        }
      }
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>