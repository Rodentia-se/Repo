<?php

class player extends participant {
  
  public static $select = '
    select 
      o.id as id,
      o.person_id as person_id,
      coalesce(o.firstName, p.firstName) as firstName,
      coalesce(o.lastName, p.lastName) as lastName,
      concat(ifnull(coalesce(o.firstName, p.firstName), " "), " ", ifnull(coalesce(o.lastName, p.lastName), " ")) as name,
      concat(ifnull(coalesce(o.firstName, p.firstName), " "), " ", ifnull(coalesce(o.lastName, p.lastName), " ")) as fullName,
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
    );
    
  }

?>