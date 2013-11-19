<?php

  class person extends base {
   
    public static $select = '
      select 
        o.id as id,
        o.firstName as firstName,
        o.lastName as lastName,
        concat(ifnull(o.firstName, " "), " ", ifnull(o.lastName, " ")) as name,
        o.initials as shortName,
        o.streetAddress as streetAddress,
        o.zipCode as zipCode,
        o.gender_id as gender_id,
        o.city_id as city_id,
        o.region_id as region_id,
        o.parentRegion_id as parentRegion_id,
        o.country_id as country_id,
        o.parentCountry_id as parentCountry_id,
        o.continent_id as continent_id,
        o.telephoneNumber as telephoneNumber,
        o.mobileNumber as mobileNumber,
        o.mailAddress as mailAddress,
        o.birthDate as birthDate,
        o.place as place,
        o.ifpa_id as ifpa_id,
        o.ifpaRank as ifpaRank,
        o.adminLevel as adminLevel,
        o.comment as comment,
        if(v.id is not null,1,0) as volunteer,
        v.id as volunteer_id,
        ifnull(v.hours, 0) as hours,
        ifnull(v.alloc, 0) as alloc,
        timediff(time(concat(ifnull(v.hours, "00"), ":00:00")), ifnull(v.alloc, time("00:00:00"))) as hoursDiff,
        o.username as username
      from person p
      left join volunteer v
        on v.person_id = o.id and v.tournamentEdition_id = 1
    ';

    public function __construct($data, $type = NULL, $search = NULL) {
      switch ($type) {
        case 'username':
          $query = 'select id from person where username = :search';
          $values[':search'] = $search;
          $sth = $this->db->select($query, $values);
          if ($sth) {
            $id = $sth->fetchColumn();
          }
          if ($id) {
            
          }
        break;
        default:
          parent::__construct($data, $type);
        break;
      }
    }

  }

?>