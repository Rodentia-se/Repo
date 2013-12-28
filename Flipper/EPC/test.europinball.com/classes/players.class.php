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
      if (isTeam($data)) {
        $tournament = ($this->tournamentEdition) ? $this->tournamentEdition : getTournament();
        $division = getDivision($tournament, 'main');
        if (isTournament($tournament)) {
          $data = '
            left join teamPerson tp 
              on tp.person_id = '.((static::$objClass == 'player') ? 'p' : 'o').'.id
            left join team t 
              on tp.team_id = t.id
            where t.id = '.$data->id.'
              and tp.tournamentEdition_id = '.$tournament->id.'
              '.((static::$objClass == 'player') ? 'and o.tournamentDivision_id = '.$division->id : '').'
          ';
        }
      }
      parent::__construct($data, $prop, $cond);
    }
    
    public function getTable($id = NULL, $class = NULL, array $headers = NULL) {
      $divisionIds = array();
      foreach ($this as $obj) {
        if (!in_array($obj->tournamentDivision_id, $divisionIds)) {
          $divisionIds[] = $obj->tournamentDivision_id;
        }
        $tbody[$this->tournamentDivision_id][] = $obj->getTr();
      }
      if (!$headers && $headers !== FALSE) {
        if ($this->team) {
          if ($this->national) {
            $headers = array('Name', 'Tag', 'Country', 'Members', 'Picture');
          } else {
            $headers = array('Name', 'Tag', 'Members', 'Picture');
          }
        } else {
          $headers = array('Name', 'Tag', 'City', 'Region', 'Country sort', 'Country', 'IFPA Rank', 'IFPA', 'Photo', 'Waiting', 'Paid');
        }
        if (count($divisionIds) > 1) {
          $tabs = new tabs(NULL, 'divisionTabs');
        } else {
          $tabs = new div('playerDiv');
        }
        foreach($divisionIds as $divisionId) {
          $division = division($divisionId);
          $thead = new tr();
          foreach ($headers as $label) {
            $thead->addTh($label);
          }
          $div = $tabs->addDiv($divisionId.'_divisionDiv', NULL, array('data-title' => $division->divisionName));
          $table = $div->addTable($tbody[$divisionId], $thead);
        }
      }
      return $tabs;
    }
/*
      $tabs = new tabs(NULL, 'childrenTabs');
        foreach (static::$infoChildren as $childArrayClass) {
          $childrenDiv = $tabs->addDiv($childArrayClass.'Div');
          $children = $childArrayClass($this);
          $childrenDiv->addContent($children->getTable());
        }
      //}
      return $tabs;
*/
  }

?>