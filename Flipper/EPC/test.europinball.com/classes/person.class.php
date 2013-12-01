<?php

  class person extends base {
   
    public static $instances;
    public static $arrClass = 'persons';

    public static $select = '
      select
        o.id as id,
        o.firstName as firstName,
        o.lastName as lastName,
        concat(ifnull(o.firstName, " "), " ", ifnull(o.lastName, " ")) as name,
        concat(ifnull(o.firstName, " "), " ", ifnull(o.lastName, " ")) as fullName,
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
        o.ifpa_id as ifpa_id,
        o.ifpaRank as ifpaRank,
        o.comment as comment,
        o.nonce as nonce,
        o.username as username
      from person o
    ';

    public static $parents = array(
      'gender' => 'gender',
      'city' => 'city',
      'region' => 'region',
      'parentRegion' => 'region',
      'country' => 'country',
      'parentCountry' => 'country',
      'continent' => 'continent'
    );

    public static $children = array(
      'player' => array(
        'field' => 'person',
        'delete' => TRUE,
      ),
      'volunteer' => array(
        'field' => 'person',
        'delete' => TRUE,
      ),
      'matchPlayer' => 'person',
      'matchScore' => 'person',
      'owner' => 'contactPerson',
      'tshirt' => array(
        'table' => 'personTShirt', 
        'field' => 'person'
      ),
      'entry' => 'person',
      'score' => 'person',
      'score' => 'registerPerson',
      'team' => 'registerPerson'
    );
    
    public function getPlayer($division = NULL) {
      if (get_class($division) == 'division') {
        $division_id = $division->id;
      } else if (is_int($division)) {
        $division_id = $division;
      } else {
        $division_id = config::$mainDivision;
      }
      $player = new player(array(
        'person_id' => $this->id,
        'tournamentDivision_id' => $division_id
      ), TRUE);
      return $player;
    }

    public function addPlayer($division = NULL) {
      if (get_class($division) == 'division') {
        $division_id = $division->id;
      } else if (is_int($division)) {
        $division_id = $division;
      } else {
        $division_id = config::$mainDivision;
      }
      debug(get_object_vars($this), true);
      $player = new player();
      
    }

/*
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
*/

  }

?>