<?php

  class player extends participant {
    
    public static $instances;
    public static $arrClass = 'players';

    public static $select = '
      select 
        o.id as id,
        o.person_id as person_id,
        o.team_id as team_id,
        coalesce(o.firstName, p.firstName) as firstName,
        coalesce(o.lastName, p.lastName) as lastName,
        o.teamName as teamName,
        if (o.team_id is not null, o.teamName,
          concat(
            ifnull(coalesce(o.firstName, p.firstName), " "), " ", 
            ifnull(coalesce(o.lastName, p.lastName), " ")
          )
        ) as name,
        if (o.team_id is not null, o.teamName,
          concat(
            ifnull(coalesce(o.firstName, p.firstName), " "), " ", 
            ifnull(coalesce(o.lastName, p.lastName), " ")
          )
        ) as fullName,
        coalesce(o.initials, p.initials) as shortName,
        coalesce(o.streetAddress, p.streetAddress) as streetAddress,
        coalesce(o.zipCode, p.zipCode) as zipCode,
        coalesce(o.gender_id, p.gender_id) as gender_id,
        coalesce(o.city_id, p.city_id) as city_id,
        coalesce(o.region_id, p.region_id) as region_id,
        coalesce(o.parentRegion_id, p.parentRegion_id) as parentRegion_id,
        coalesce(o.country_id, p.country_id) as country_id,
        coalesce(o.parentCountry_id, p.parentCountry_id) as parentCountry_id,
        coalesce(o.continent_id, p.continent_id) as continent_id,
        coalesce(o.telephoneNumber, p.telephoneNumber) as telephoneNumber,
        coalesce(o.mobileNumber, p.mobileNumber) as mobileNumber,
        coalesce(o.mailAddress, p.mailAddress) as mailAddress,
        p.birthDate as birthDate,
        o.qualGroup_id as qualGroup_id,
        o.qualChangeReq as qualChangeReq,
        o.qualPlace as qualPlace,
        o.place as place,
        coalesce(o.wpprPlace, o.place) as wpprPlace,
        o.wpprPoints as wpprPoints,
        o.here as here,
        o.hereFinal as hereFinal,
        if(o.paid is not null, o.paid, 0) as paid,
        o.payDate as payDate,
        o.waiting as waiting,
        p.ifpa_id as ifpa_id,
        coalesce(o.ifpaRank, p.ifpaRank) as ifpaRank,
        p.username as username,
        p.nonce as nonce,
        o.tournamentEdition_id as tournamentEdition_id,
        o.tournamentDivision_id as tournamentDivision_id,
        coalesce(o.comment, p.comment) as comment,
        if(v.id is not null,1,0) as volunteer,
        v.id as volunteer_id,
        v.adminLevel as adminLevel,
        v.here as hereVol,
        ifnull(v.hours, 0) as hours,
        ifnull(v.alloc, 0) as alloc,
        timediff(time(concat(ifnull(v.hours, "00"), ":00:00")), ifnull(v.alloc, time("00:00:00"))) as hoursDiff
      from player o
      left join person p
        on o.person_id = p.id
      left join volunteer v
        on v.person_id = p.id
    ';

    public static $parents = array(
      'person' => 'person',
      'team' => 'team',
      'qualGroup' => 'qualGroup',
      'tournamentEdition' => 'tournament',
      'tournamentDivision' => 'division',
      'gender' => 'gender',
      'city' => 'city',
      'region' => 'region',
      'parentRegion' => 'region',
      'country' => 'country',
      'parentCountry' => 'country',
      'continent' => 'continent'
    );
    
    public static $children = array(
      'matchPlayer' => 'player',
      'matchScore' => 'player',
      'playerQualGroups' => array(
        'field' => 'player',
        'delete' => TRUE
      ),
      'entry' => array(
        'field' => 'player',
        'delete' => TRUE
      ),
      'score' => 'player',
      'team' => 'contactPlayer',
      'teamPlayer' => array(
        'table' => 'teamPlayer',
        'field' => 'player'
      )
    );
    
    public static $cols = array(
      'initials' => 'shortName',
      'city' => 'cityName',
      'region' => 'regionName',
      'parentRegion' => 'parentRegionName',
      'country' => 'countryName',
      'parentCountry' => 'parentCountryName',
      'continent' => 'continentName',
      'gender' => 'genderName'
    );

    public static $validators = array(
      'mailAddress' => array('person', 'validateEmail'),
      'username' => array('person', 'validateUsername')
    );

    public function __construct($data = NULL, $search = NOSEARCH, $depth = NULL) {
      debug(get_class($this), 'player, class');
      debug($data, 'player, data');
      debug($search, 'player, search');
      $constructors = array('current', 'active', 'login', 'auth');
      if (is_string($data) && (in_array($data, $constructors) || in_array($data, config::$activeDivisions)) && $search == NOSEARCH) {
        $delegated = TRUE;
        $login = new auth();
        $person = $login->person;
      } else if (is_object($data) && get_class($data) == 'person') {
        $delegated = TRUE;
        $person = $data;
      } else if (is_object($data) && get_class($data) == 'division' && $search != NOSEARCH && $search) {
        $delegated = TRUE;
        $person = person($search);
        $earch = $data;
      }
      if ($delegated) {
        if ($person && isId($person->id)) {
          $type = ($search == NOSEARCH) ? 'main' : $search;
          $search = NOSEARCH;
          $division = division($type);
          if ($division && isId($division->id)) {
            $data = array(
              'person_id' => $person->id,
              'tournamentDivision_id' => $division->id
            );
            parent::__construct($data, TRUE, $depth);
          } else {
            $this->failed == TRUE;
          }
        } else {
          $this->failed == TRUE;
        }
      } else {
        parent::__construct($data, $search, $depth);
      }
    }

    public function getLink($type = 'object', $anchor = TRUE, $thumbnail = FALSE) {
      switch ($type) {
        case 'ifpa':
          if ($this->ifpa_id) {
            return '<a href="http://www.ifpapinball.com/player.php?player_id='.$this->ifpa_id.'" target="_new">'.(($this->ifpaRank && $this->ifpaRank != 0) ? $this->ifpaRank : 'Unranked').'</a>';
          } else {
            return 'Unranked';
          }
        break;
        default:
          return parent::getLink($type, $anchor, $thumbnail);
        break;
      }
    }
    
    public function setUsername($username) {
      if (!is_object($this->person)) {
        $this->populate(1);
      }
      if (!is_object($this->person)) {
        return $this->person->setUsername($username);
      }
      return FALSE;
    }
    
    public function getRegRow($array = FALSE) {
      if ($this->team) {
        $members = $this->team->getMembers();
        unset($memberLinks);
        foreach($members as $member) {
          $memberLinks[] = $member->getLink();
        }
        $memberCell = implode($memberLinks, '<br />');
        if ($this->team->national) {
          $return = array(
            $this->getLink(),
            $this->shortName,
            (is_object($this->country)) ? $this->country->getLink() : $this->countryName,
            $memberCell,
            $this->getLink('photo')
          );
          return ($array) ? $return : (object) $return;
        } else {
          $return = array(
            $this->getLink(),
            $this->shortName,
            $memberCell,
            $this->getLink('photo')
          );
          return ($array) ? $return : (object) $return;
        }
      } else {
        $return = array(
          $this->getLink(),
          $this->shortName,
          (is_object($this->city)) ? $this->city->getLink() : $this->cityName,
          (is_object($this->region)) ? $this->region->getLink() : $this->regionName,
          (is_object($this->country)) ? $this->country->getLink() : $this->countryName,
          (is_object($this->continent)) ? $this->continent->getLink() : $this->continentName,
          $this->getLink('ifpa'),
          $this->getLink('photo')
        );
        return ($array) ? $return : (object) $return;
      }
      return FALSE;
    }

  }

?>