<?php

  class team extends player {
        
    public static $instances;
    public static $arrClass = 'teams';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.initials as shortName,
        o.national as national,
        o.contactPlayer_id as contactPlayer_id,
        o.city_id as city_id,
        o.region_id as region_id,
        o.parentRegion_id as parentRegion_id,
        o.country_id as country_id,
        o.parentCountry_id as parentCountry_id,
        o.continent_id as continent_id,
        o.tournamentDivision_id as tournamentDivision_id,
        o.tournamentEdition_id as tournamentEdition_id,
        o.dateRegistered as dateRegistered,
        o.registerPerson_id as registerPerson_id,
        o.comment as comment
      from team o
    ';
    
    public static $parents = array(
      'contactPlayer' => 'player',
      'registerPerson_id' => 'person',
      'tournamentEdition' => 'tournament',
      'tournamentDivision' => 'division',
      'city' => 'city',
      'region' => 'region',
      'parentRegion' => 'region',
      'country' => 'country',
      'parentCountry' => 'country',
      'continent' => 'continent'
    );

    public static $children = array(
      'player' => array(
        'field' => 'team',
        'delete' => TRUE
      ),
      'teamPlayer' => array(
        'table' => 'teamPlayer',
        'field' => 'player',
        'delete' => TRUE
      )
    )
    
    public static $validators = array(
    );

    public function getMembers($division = NULL) {
      $division = getDivision($division);
      $query = person::$select.'
        left join teamPerson tp on tp.person_id = o.id
        left join team t on tp.team_id = t.id
        where tp.team_id = :id
          and t.tournamentDivision_id = :division
      ';
      $values[':id'] = $this->id;
      $values[':division'] = $division->id;
      $members = $this->db->select($query, $values, 'person');
      if (count($members) > 0) {
        return $members;
      }
      return false;
    }
    
    public static function validateName($name, $obj = FALSE) {
      if (!$name) {
        return validated(TRUE, 'Nothing to validate.', $obj);
      }
      if (!preg_match('/^[a-zA-ZåäöÅÄÖüÛïÎëÊÿŸçßéÉæøÆØáÁóÓàÀČčŁłĳŠšŮ0-9 \-_#\$]{3,32}$/', $name)) {
        return validated(FALSE, 'The name must be at least three character and can only include a-Z, A-Z, most of ÜÅÄÖ and similar, 0-9, spaces, #, $, dashes and underscores.', $obj);
      } else {
        $team = team('name', $name);
        if ($team) {
          $currentTeam = team('current');
          if ($team->id == $currentTeam->id) {
            return validated(TRUE, 'That name does already belong to your team, you didn\'t change it.', $obj);
          } else {
            return validated(FALSE, 'That name is already taken.', $obj);
          }
        }
      }
      return validated(TRUE, 'That name is up for grabs.', $obj);
    }

  }

?>