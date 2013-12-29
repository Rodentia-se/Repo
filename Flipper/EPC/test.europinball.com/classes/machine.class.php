<?php

  class machine extends base {
        
    public static $instances;
    public static $arrClass = 'machines';

    public static $select = '
      select 
        o.id as id,
        g.id as game_id,
        g.name as name,
        g.name as fullName,
        g.acronym as acronym,
        g.acronym as shortName,
        g.manufacturer_id as manufacturer_id,
        g.game_ipdb_id as ipdb,
        g.game_link_rulesheet as rules,
        g.game_year_released as year,
        o.tournamentDivision_id as tournamentDivision_id,
        o.tournamentEdition_id as tournamentEdition_id,
        o.gameType as type,
        o.balls as balls,
        o.extraBalls as extraBalls,
        o.onePlayerAllowed as onePlayerAllowed,
        o.owner_id as owner_id,
        o.paid as paid,
        o.comment as comment
      from machine o
      left join game g
        on o.game_id = g.id
    ';
    
    public static $parents = array(
      'game' => 'game',
      'manufacturer' => 'manufacturer',
      'tournamentDivision' => 'division',
      'tournamentEdition' => 'tournament',
      'owner' => 'owner'
    );

    public static $children = array(
      'matchScore' => 'machine',
      'set' => 'machine',
      'score' => 'machine'
    );

    public function getTr($headers = NULL) {
      // @todo: Handle custom headers
      $cells = $this->getRegRow(TRUE);
      $tr = new tr();
      foreach ($cells as $cell) {
        $tr->addTd($cell)->escape = FALSE;
      }
      return $tr;
    }

    public function getRegRow($array = FALSE) {
      // @todo: Handle custom headers
      // @todo: Change to object
      $return = array(
        $this->getLink(),
        $this->shortName,
        (is_object($this->manufacturer)) ? (($this->manufacturer->getLink()) ? $this->manufacturer->getLink() : $this->manufacturer->name) : $this->manufacturerName,
        (is_object($this->owner)) ? (($this->owner->getLink()) ? $this->owner->getLink() : $this->owner->name) : $this->ownerName,
        ($this->ipdb) ? $this->getLink('ipdb') : '',
        ($this->rules) ? $this->getLink('rules') : '',
        $this->year
      );
      return ($array) ? $return : (object) $return;
    }

    public function getLink($type = 'object', $anchor = true, $thumbnail = false, $preview = false, $defaults = true) {
      switch ($type) {
        case 'ipdb':
          if ($this->ipdb) {
            $url = 'http://www.ipdb.org/machine.cgi?id='.$this->ipdb;
          } else {
            return FALSE;
          }
          return ($url && $anchor) ? '<a href="'.$url.'" target="_new">'.$this->ipdb.'</a>' : $url;
        break;
        case 'rules':
          if ($this->rules) {
            $url = $this->rules;
          } else {
            return FALSE;
          }
          return ($url && $anchor) ? '<a href="'.$url.'" target="_new" border="0"><img src="'.config::$baseHref.'/images/textbook_icon.png" alt="Rules" title="Rules" class="icon" border="0"></a>' : $url;
        break;
        default:
          return parent::getLink($type, $anchor, $thumbnail, $preview, $defaults);
        break;
      }
    }

  }

?>