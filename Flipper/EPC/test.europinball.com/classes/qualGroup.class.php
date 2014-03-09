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
    
    public function getEdit($array = FALSE) {
        $return = array(
          $this->getLink(),
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
        );
        return ($array) ? $return : (object) $return;
    }

  }

?>