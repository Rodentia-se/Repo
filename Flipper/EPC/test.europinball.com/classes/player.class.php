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
        if(p.birthDate = "0000-00-00", NULL, p.birthDate) as birthDate,
        o.qualGroup_id as qualGroup_id,
        o.qualChangeReq as qualChangeReq,
        o.qualPlace as qualPlace,
        o.place as place,
        coalesce(o.wpprPlace, o.place) as wpprPlace,
        o.wpprPoints as wpprPoints,
        o.here as here,
        o.hereFinal as hereFinal,
        ifnull(p.paid, 0) as paid,
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
        v.adminLevel_id as adminLevel_id,
        v.adminLevel_id as adminLevel,
        if(v.adminLevel_id > 0, 1, null) as scorereader,
        if(v.adminLevel_id > 7, 1, null) as allreader,
        if(v.adminLevel_id > 15, 1, null) as scorekeeper,
        if(v.adminLevel_id > 23, 1, null) as receptionist,
        if(v.adminLevel_id > 31, 1, null) as admin,
        v.here as hereVol,
        ifnull(v.hours, 0) as hours,
        ifnull(v.alloc, 0) as alloc,
        timediff(time(concat(ifnull(v.hours, "00"), ":00:00")), ifnull(v.alloc, time("00:00:00"))) as hoursDiff
      from player o
      left join person p
        on o.person_id = p.id
      left join volunteer v
        on v.person_id = p.id
        and v.tournamentEdition_id = o.tournamentEdition_id
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
    
    public static $infoProps = array(
      'name',
      'tag',
      'city',
      'region',
      'country',
      'continent',
      'IFPA' => 'ifpaLink'
    );

    public function __construct($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
      $persons = array('current', 'active', 'login', 'auth');
      $divisions = array_merge(array('current', 'active'), config::$divisions);
      if (is_string($data) && in_array($data, $persons)) {
        $data = person($data);
        if (!$data || !isId($data->id)) {
          $this->failed = TRUE;
          return FALSE;
        }
      } else if ((isObj($data) && get_class($data) == 'tournament') || (is_string($data) && in_array($data, $divisions))) {
        $data = division($data, $search);
        if (!$data || !isId($data->id)) {
          $this->failed = TRUE;
          return FALSE;
        }
      }
      if ((isObj($data) && get_class($data) == 'person') || (is_string($search) && in_array($search, $divisions))) {
        $search = division((($search !== config::NOSEARCH) ? $search : 'main'));
        if (!$search || !isId($search->id)) {
          $this->failed = TRUE;
          return FALSE;
        }
      } else if (is_string($search) && in_array($search, $persons)) {
        $search = person($search);
        if (!$search || !isId($search->id)) {
          $this->failed = TRUE;
          return FALSE;
        }
      }
      parent::__construct($data, $search, $depth);
      $this->ifpaLink = $this->getLink('ifpa');
    }

    public function getLink($type = 'object', $anchor = true, $thumbnail = false, $preview = false, $defaults = true) {
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
    
    public function setPaid($amount = 1) {
      if (!is_object($this->person)) {
        $this->populate(1);
      }
      if (!is_object($this->person)) {
        return $this->person->setPaid($amount);
      }
      return FALSE;
    }

    public function getRegRow($array = FALSE) {
      if ($this->team) {
        $members = $this->team->getMembers();
        unset($memberLinks);
        if($members) {
          foreach($members as $member) {
            $memberLinks[] = $member->getLink();
          }
          $memberCell = implode($memberLinks, '<br />');
        }
        if ($this->team->national) {
          $return = array(
            $this->getLink(),
            $this->shortName,
            (is_object($this->country)) ? $this->country->getLink() : $this->countryName,
            $memberCell,
            $this->team->getPhotoIcon(),
          );
          return ($array) ? $return : (object) $return;
        } else {
          $return = array(
            $this->getLink(),
            $this->shortName,
            $memberCell,
            $this->team->getPhotoIcon(),
          );
          return ($array) ? $return : (object) $return;
        }
      } else {
        $return = array(
          $this->getLink(),
          $this->shortName,
          (is_object($this->city)) ? $this->city->getLink() : $this->cityName,
          (is_object($this->region)) ? $this->region->getLink() : $this->regionName,
          (is_object($this->country)) ? $this->country->name : $this->countryName,
          (is_object($this->country)) ? $this->country->getIcon() : $this->countryName,
          (($this->ifpaRank) ? $this->ifpaRank : 100000),
          str_replace('Unranked', 'Unr', $this->getLink('ifpa')),
          (($this->person) ? $this->person->getPhotoIcon() : ''),
          (($this->waiting) ? ((isId($this->waiting)) ? $this->waiting : '*'): ''),
          (($this->paid) ? 'Yes' : '')
        );
        return ($array) ? $return : (object) $return;
      }
      return FALSE;
    }

    public function getPhoto($defaults = TRUE, $thumbnail = FALSE, $anchor = FALSE) {
      if ($this->team_id) {
        $team = team($this->team_id);
      }
      if (isTeam($team)) {
        return $team->getPhoto($defaults, $thumbnail, $anchor);
      } else {
        return parent::getPhoto($defaults, $thumbnail, $anchor);
      }
    }


  }

?>