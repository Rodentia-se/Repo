<?php

  class qualGroup extends timeSlot {
        
    public static $instances;
    public static $arrClass = 'qualGroups';

    public static $select = '
      select 
        o.id as id,
        concat(o.name, ": ", o.date, " ", replace(replace(startTime, ":00", ""), ":00", ""), "-", replace(replace(endTime, ":00", ""), ":00", "")) as name,
        concat(o.name, ": ", o.date, " ", startTime, " - ", endTime) as fullName,
        concat(o.name, ": ", replace(replace(startTime, ":00", ""), ":00", ""), "-", replace(replace(endTime, ":00", ""), ":00", "")) as shortName,
        concat(o.date, " ", startTime, " - ", endTime) as dateName,
        concat(o.date, startTime) as sortName,
        o.name as acronym,
        o.date as date,
        o.startTime as startTime,
        o.endTime as endTime,
        o.tournamentDivision_id as tournamentDivision_id,
        o.comment as comment
      from qualGroup o
    ';
    
    public static $parents = array(
      'tournamentDivision' => 'division'
    );
    
    // @todo: Fix children
/*
    public static $children = array(
      'player' => 'qualGroup',
      'team' => 'qualGroup',
      'playerQualGroups' => array(
        'field' => 'qualGroup',
        'delete' => TRUE
      )
    );
*/
    
    public function getRegRow($array = FALSE) {
      $return = array(
        $this->getLink()
/*
        $this->getLink('shortName'),
        (is_object($this->city)) ? $this->city->getLink() : $this->cityName,
        (is_object($this->region)) ? $this->region->getLink() : $this->regionName,
        (is_object($this->country)) ? $this->country->name : $this->countryName,
        (is_object($this->country)) ? $this->country->getIcon() : $this->countryName,
        (($this->ifpaRank) ? $this->ifpaRank : (($this->getLink('ifpa')) ? 99000 : 100000)),
        str_replace('Unranked', 'Unr', $this->getLink('ifpa')),
        (($this->person) ? $this->person->getPhotoIcon() : ''),
        (($this->waiting) ? ((isId($this->waiting)) ? $this->waiting : '*'): ''),
        (($this->paid) ? 'Yes' : '')
*/
      );
      return ($array) ? $return : (object) $return;
    }
    
    public function getEdit($type = 'groupsAdmin', $title = NULL, $tournament = NULL, $prefix = NULL) {
      switch ($type) {
        case 'payment': 
        default:
          $div = new div($prefix.'qualGroupEditDiv'.$this->id);
          $players = players($this->tournamentDivision);
          $groupPlayers = players($this);
          $headers = array('ID', 'Name', 'Action');
          $delIcon = new img(config::$baseHref.'/images/cancel.png', 'Click to remove player', array('class' => 'closeIcon'));
//          $delIcon->addClick('');
          foreach ($groupPlayers as $groupPlayer) {
            $rows[] = array($groupPlayer->id, $groupPlayer->name, $delIcon);
          }
          $div->addH3('Players', array('class' => 'entry-title'));
          $table = $div->addTable($rows, $headers);
          $table->addDatatables();
          $tr = new tr();
          $tr->addTd(0);
          $playerSelect = $players->getSelectObj($prefix.'qualGroupAddPlayer'.$this->id, NULL, FALSE);
          $playerSelect->addCombobox();
          $tr->addTd($playerSelect)->entities = FALSE;
          $addIcon = new img(config::$baseHref.'/images/add_icon.gif', 'Click to add player', array('class' => 'addIcon'));
          $tr->addTd($addIcon)->entities = FALSE;
          $tr->type = 'tbody';
          $table->addContent($tr);
          return $div;
        break;
      }
    }

  }

?>