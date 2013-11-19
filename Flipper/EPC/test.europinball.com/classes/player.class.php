<?php

class player extends participant {
  
  private $person;
  
  public static $select = '
    select 
      o.id as id,
      p.person_id as person_id,
      coalesce(p.firstName, o.firstName) as firstName,
      coalesce(p.lastName, o.lastName) as lastName,
      concat(ifnull(coalesce(p.firstName, o.firstName), " "), " ", ifnull(coalesce(p.lastName, o.lastName), " ")) as name,
      coalesce(p.initials, o.initials) as shortName,
      coalesce(p.streetAddress, o.streetAddress) as streetAddress,
      coalesce(p.zipCode, o.zipCode) as zipCode,
      coalesce(p.gender_id, o.gender_id) as gender_id,
      coalesce(p.city_id, o.city_id) as city_id,
      coalesce(p.region_id, o.region_id) as region_id,
      coalesce(p.parentRegion_id, o.parentRegion_id) as parentRegion_id,
      coalesce(p.country_id, o.country_id) as country_id,
      coalesce(p.parentCountry_id, o.parentCountry_id) as parentCountry_id,
      coalesce(p.continent_id, o.continent_id) as continent_id,
      coalesce(p.telephoneNumber, o.telephoneNumber) as telephoneNumber,
      coalesce(p.mobileNumber, o.mobileNumber) as mobileNumber,
      coalesce(p.mailAddress, o.mailAddress) as mailAddress,
      p.birthDate as birthDate,
      p.place as place,
      coalesce(p.wpprPlace, p.place) as wpprPlace,
      p.wpprPoints as wpprPoints,
      p.here as here,
      p.hereFinal as hereFinal,
      v.here as hereVol,
      o.ifpa_id as ifpa_id,
      coalesce(p.ifpaRank, o.ifpaRank) as ifpaRank,
      coalesce(p.adminLevel, o.adminLevel) as adminLevel,
      coalesce(p.comment, o.comment) as comment,
      p.qualGroup_id as qualGroup_id,
      if(v.id is not null,1,0) as volunteer,
      v.id as volunteer_id,
      ifnull(v.hours, 0) as hours,
      ifnull(v.alloc, 0) as alloc,
      timediff(time(concat(ifnull(v.hours, "00"), ":00:00")), ifnull(v.alloc, time("00:00:00"))) as hoursDiff,
      if(p.paid is not null, p.paid, 0) as paid,
      p.payDate as payDate,
      p.tournamentEdition_id as tournamentEdition_id,
      p.tournamentDivision_id as tournamentDivision_id,
      o.username as username
    from person p
    left join player p
      on p.person_id = o.id
    left join volunteer v
      on v.person_id = o.id
  ';

    public static $parents = array(
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
    
    public function __construct($data, $type = NULL) {
      parent::__construct($data, $type);
      if ($this->person_id) {
        $this->person == new person($this->person_id);
      }
    }
    
    public function __call($method, $args) {
      $this->person->$method($args[0]);
    }

  }

?>