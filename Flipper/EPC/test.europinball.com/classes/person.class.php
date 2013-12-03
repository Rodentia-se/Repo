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
    
    public function getPlayer($division = NULL) {
      if (get_class($division) == 'division') {
        $division_id = $division->id;
      } else if (is_int($division)) {
        $division_id = $division;
      } else {
        $division_id = config::$mainDivision;
      }
      return player(array(
        'person_id' => $this->id,
        'tournamentDivision_id' => $division_id
      ), TRUE);
    }

    public function addPlayer($division = NULL) {
      if (get_class($division) == 'division') {
        $division_id = $division->id;
      } else if (is_int($division)) {
        $division_id = $division;
      } else {
        $division_id = config::$mainDivision;
      }
      $division = division($division_id);
      $player = player($this->getFlat(), NULL, 0);
      unset($player->id);
      $player->tournamentDivision_id = $division->id;
      $player->tournamentEdition_id = $division->tournamentEdition_id;
      $player->person_id = $this->id;
      $player->dateRegistered = date('Y-m-d');
      $id = $player->save();
    }
    
    public function getEdit($title = 'Edit profile', $tournament = NULL) {
      if (is_object($tournament) && $tournament->id) {
        $tournament_id = $tournament->id;
      } else if (isId($tournament)) {
        $tournament_id = $tournament;
      } else {
        $tournament_id = config::$activeTournament;
      }
      $tournament = tournament($tournament_id);
      $divisions = $tournament->getDivisions();
      foreach ($divisions as $division) {
        $player = $this->getPlayer($division);
        if ($division->main) {
          $main = TRUE;
          if ($player) {
            $this->main = TRUE;
          }
        }
        if ($division->classics) {
          $classics = TRUE;
          if ($player) {
            $this->classics = TRUE;
          }
        }
        if ($division->eighties) {
          $eighties = TRUE;
          if ($player) {
            $this->eighties = TRUE;
          }
        }
      }
      $genders = genders('all');
      $cities = cities('all');
      $regions = regions('all');
      $countries = countries('all');
      $continents = continents('all');
      
      $content = '
        <div id="editDiv">
        	<h2 class="entry-title">'.$title.'</h2>
          <p class="italic">Note: All changes below are INSTANT!</p>
          '.page::getInput((($this->firstName) ? $this->firstName : 'Enter first name'), 'firstName', 'edit', 'text', 'First name').'
          '.page::getInput((($this->lastName) ? $this->lastName : 'Enter last name'), 'lastName', 'edit', 'text', 'Last name').'
          '.page::getInput((($this->shortName) ? $this->shortName : 'Enter tag'), 'shortName', 'edit', 'text', 'Tag').'
          '.$genders->getSelect('gender_id', 'combobox', 'Gender', $this->gender_id).'
          '.page::getInput((($this->streetAddress) ? $this->streetAddress : 'Enter address'), 'streetAddress', 'edit', 'text', 'Address').'
          '.page::getInput((($this->zipCode) ? $this->zipCode : 'Enter ZIP'), 'zipCode', 'edit', 'text', 'ZIP').'
          '.$cities->getSelect('city_id', 'combobox', 'City', $this->city_id).'
          '.$regions->getSelect('region_id', 'combobox', 'Region', $this->region_id).'
          '.$countries->getSelect('country_id', 'combobox', 'Country', $this->country_id).'
          '.$continents->getSelect('continent_id', 'combobox', 'Continent', $this->continent_id).'
          '.page::getInput((($this->telephoneNumber) ? $this->telephoneNumber : 'Enter phone'), 'telephoneNumber', 'edit', 'text', 'Phone').'
          '.page::getInput((($this->mobileNumber) ? $this->mobileNumber : 'Enter cell'), 'mobileNumber', 'edit', 'text', 'Cell phone').'
          '.page::getInput((($this->mailAddress) ? $this->mailAddress : 'Enter email'), 'mailAddress', 'edit', 'text', 'Email').'
          '.(($main) ? page::getInput($this->main, 'main', 'check', 'checkbox', 'Main') : '').'
          '.(($classics) ? page::getInput($this->classics, 'classics', 'check', 'checkbox', 'Classics') : '').'
          '.(($eighties) ? page::getInput($this->eighties, 'eighties', 'check', 'checkbox', '80s') : '').'
          '.page::getInput($this->birthDate, 'birthDate', 'date', 'date', 'Born').'
        </div>
      ';
      return $content;
    }
    
    public function setUsername($username) {
      return $this->setProp('username', $username);
    }

    public function getLink($type = 'object') {
      switch ($type) {
        case 'ifpa':
          if ($this->ifpa_id) {
            return '<a href="http://www.ifpapinball.com/player.php?player_id='.$this->ifpa_id.'" target="_new">'.(($this->ifpaRank && $this->ifpaRank != 0) ? $this->ifpaRank : 'Unranked').'</a>';
          } else {
            return 'Unranked';
          }
        break;
        default:
          return parent::getLink($type);
        break;
      }
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