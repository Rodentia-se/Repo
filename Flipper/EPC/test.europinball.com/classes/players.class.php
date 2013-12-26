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
      if (isTeam($data) {
        $tournament = ($this->tournamentEdition) ? $this->tournamentEdition : getTournament();
        $division = getDivision($tournament, 'main');
        if (isDivision($division)) {
          $query = ((static::$objClass == 'player') ? player::$select : person::$select).'
            left join teamPerson tp on tp.person_id = '.((static::$objClass == 'player') ? 'p' : 'o').'.id
            left join team t on tp.team_id = t.id
            where t.id = :id
              and tp.tournamentEdition_id = :tournament
          ';
          $values[':id'] = $data->id;
          $values[':tournament'] = $tournament->id;
          if (static::$objClass == 'player') {
            $query .= '
              and o.tournamentDivision_id = :division
            '; 
            $values[':division'] = $division->id;
          }
          $data = $this->db->select($query, $values, (($asPlayers) ? 'player' : 'person'));
        }
      }
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>