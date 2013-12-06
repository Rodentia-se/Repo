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
    
    public static $validators = array(
      'telephoneNumber' => '/^[0-9 \-\+\(\)]{6,}$/',
      'mobileNumber' => '/^[0-9 \-\+\(\)]{6,}$/',
      'birthDate' => 'validateDate',
      'dateRegistered' => 'validateDate',
      'shortName' => '/^[a-zA-Z0-9 \-]{1,3}$/',
      'initials' => '/^[a-zA-Z0-9 \-]{1,3}$/',
      'tag' => '/^[a-zA-Z0-9 \-]{1,3}$/'
    );
    
    public function getPlayer($division = NULL) {
      $division = getDivision($division);
      return player(array(
        'person_id' => $this->id,
        'tournamentDivision_id' => $division->id
      ), TRUE);
    }

    public function addPlayer($division = NULL) {
      $division = getDivision($division);
      $player = player($this->getFlat(), NULL, 0);
      unset($player->id);
      $player->tournamentDivision_id = $division->id;
      $player->tournamentEdition_id = $division->tournamentEdition_id;
      $player->person_id = $this->id;
      $player->dateRegistered = date('Y-m-d');
      $id = $player->save();
    }
    
    public function getEdit($title = 'Edit profile', $tournament = NULL) {
      $tournament = getTournament($tournament);
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
      return '
        <div id="editDiv">
        	<h2 class="entry-title">'.$title.'</h2>
          <p class="italic">Note: All changes below are INSTANT when you press enter or move away from the field.</p>
          <div>'.page::getInput($this->firstName, 'firstName', 'edit', 'text', 'First name').'</div>
          <div>'.page::getInput($this->lastName, 'lastName', 'edit', 'text', 'Last name').'</div>
          <div>'.page::getInput($this->shortName, 'shortName', 'edit', 'text', 'Tag').'</div>
          <div>'.$genders->getSelect('gender_id', 'combobox', 'Gender', $this->gender_id).'</div>
          <div>'.page::getInput($this->streetAddress, 'streetAddress', 'edit', 'text', 'Address').'</div>
          <div>'.page::getInput($this->zipCode, 'zipCode', 'edit', 'text', 'ZIP').'</div>
          <div id="cityDiv">'.page::getInput(NULL, 'city', 'edit', 'text', 'New city', TRUE).'</div>
          <div id="city_idDiv">'.$cities->getSelect('city_id', 'combobox', 'City', $this->city_id, TRUE).'</div>
          <div id="regionDiv">'.page::getInput(NULL, 'region', 'edit', 'text', 'New region', TRUE).'</div>
          <div id="region_idDiv">'.$regions->getSelect('region_id', 'combobox', 'Region', $this->region_id, TRUE).'</div>
          <div>'.$countries->getSelect('country_id', 'combobox', 'Country', $this->country_id).'</div>
          <div>'.$continents->getSelect('continent_id', 'combobox', 'Continent', $this->continent_id).'</div>
          <div>'.page::getInput($this->telephoneNumber, 'telephoneNumber', 'edit', 'text', 'Phone').'</div>
          <div>'.page::getInput($this->mobileNumber, 'mobileNumber', 'edit', 'text', 'Cell phone').'</div>
          <div>'.page::getInput($this->mailAddress, 'mailAddress', 'edit', 'text', 'Email').'</div>
          <div>'.page::getLabelStart('partLabel','label').'Divisions'.page::getLabelEnd().'
            '.(($main) ? page::getInput($this->main, 'main', 'check', 'checkbox', 'Main') : '').'
            '.(($classics) ? page::getInput($this->classics, 'classics', 'check', 'checkbox', 'Classics') : '').'
            '.(($eighties) ? page::getInput($this->eighties, 'eighties', 'check', 'checkbox', '80s') : '').'
          </div>
          <div>'.page::getInput($this->birthDate, 'birthDate', 'edit date', 'text', 'Born').'</div>
        </div>
      ';
    }

    public function setUsername($username) {
      return $this->setProp('username', $username);
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

    public static function validateEmail($email, $obj = FALSE) {
      $atIndex = strrpos($email, "@");
      if (is_bool($atIndex) && !$atIndex) {
        return validated(FALSE, 'There is no @ sign in the address.', $obj);
      } else {
        $domain = substr($email, $atIndex+1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64) {
          return validated(FALSE, 'The local part of the address is too long.', $obj);
        } else if ($domainLen < 1 || $domainLen > 255) {
          return validated(FALSE, 'The domain part of the address is too long.', $obj);
        } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
          return validated(FALSE, 'The local part of the address can\'t start or end with a dot.', $obj);
        } else if (preg_match('/\\.\\./', $local)) {
          return validated(FALSE, 'The local part of the address has two dots in a row.', $obj);
        } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
          return validated(FALSE, 'The domain part of the address contains invalid characters.', $obj);
        } else if (preg_match('/\\.\\./', $domain)) {
          return validated(FALSE, 'The domain part of the address has two dots in a row.', $obj);
        } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
          if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
            return validated(FALSE, 'The local part of the address contains invalid characters.', $obj);
          }
        }
        if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
          return validated(FALSE, 'It seems that the domain doesn\'t exist.', $obj);
        }
      }
      return validated(TRUE, 'The address has been validated.', $obj);
    }

    public static function validateUsername($username, $obj = FALSE) {
      if (!preg_match('/^[a-zA-Z0-9\-_]{3,32}$/', $username)) {
        return validated(FALSE, 'Username must be at least three characters and can only include a-Z, A-Z, 0-9, dashes and underscores.', $obj);
      } else {
        $person = person('username', $username);
        $currentPerson = getCurrentPerson();
        if ($person && $currentPerson && $currentPerson->id == $person->id) {
          return validated(TRUE, 'Username is already yours, you didn\'t change it.', $obj);
        } else if ($person) {
          return validated(FALSE, 'Username is already taken.', $obj);
        } else {
          return validated(TRUE, 'Username is up for grabs.', $obj);
        }
      }
    }
    
    public static function validatePassword($password, $obj = FALSE) {
      if (preg_match('/^(?=.*\d)(?=.*[A-Za-z])(?=.*[!@#$])[0-9A-Za-z!@#$]{6,50}$/', $password)) {
        return validated(TRUE, 'Password is valid', $obj);
      } else {
        return validated(FALSE, 'Password is required to be at least 6 characters, including a number, a letter and one of !@#$', $obj);
      }
    }
    
    public function getTeam($division = NULL) {
      $division = getDivision($division);
      $query = team::$select.'
        left join teamPerson tp on tp.team_id = o.id
        where tp.person_id = :id
          and o.tournamentDivision_id = :division
      ';
      $values[':id'] = $this->id;
      $values[':division'] = $division->id;
      return $this->db->select($query, $values, 'team');
    }

  }

?>