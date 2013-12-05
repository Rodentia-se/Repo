<?php

  function isAssoc(&$arr) {
    if (is_array($arr)) {
      for (reset($arr); is_int(key($arr)); next($arr));
      return !is_null(key($arr));
    } else {
      return false;
    }
  }
  
  function isId($id) {
    return preg_match('/^[0-9]+$/', $id);
  }

  function preDump($obj, $title = NULL) {
    echo '<pre>';
    echo ($title) ? $title.':' : '';
    var_dump($obj);
    echo '</pre>';
  }
  
  function warning($text) {
    preDump($text,'WARNING');
  }

  function error($text, $die = FALSE, $json = FALSE) {
    if ($json) {
      $error = (object) array('success' => false, 'reason' => $text);
      return json_encode($error);
    } else {
      preDump($text,'ERROR');
      if ($die) {
        die('Abort requested.');
      }
    }
  }

  function success($text = NULL, $props = NULL) {
    $json = (object) array('success' => true, 'reason' => $text);
    if ($props) {
      foreach ($props as $prop => $value) {
        $json->{$prop} = $value;
      }
    }
    return $json;
  }
  
  function debug($text, $die = FALSE) {
    if (config::$debug) {
      preDump($text,'DEBUG');
      if ($die) {
        die('Abort requested.');
      }
    }
  }

  function validate($valid = TRUE, $reason = NULL, $obj = FALSE) {
    if ($obj) {
      $return = object();
      $return->valid = $valid;
      if ($reason) {
        $return->reason = $reason;
      }
      return $return;
    } else {
      return $valid;
    }
  }
  
  function getCurrentPerson() {
    $login = new auth();
    return ($login->person) ? $login->person : NULL;
  }

  function getCurrentPlayer() {
    $person = getCurrentPerson();
    return ($person) ? $person->getPlayer() : NULL;
  }
  
  function getCurrentTeam() {
    $person = getCurrentPerson();
    return ($person) ? $person->getTeam() : NULL;
  }
  
  function getDivision($division) {
    if (get_class($division) == 'division' && $division->id) {
      $division_id = $division->id;
    } else if (is_object($division) && $division->id) {
      $division_id = $division->id;
    } else if (is_int($division)) {
      $division_id = $division;
    } else {
      switch ($division) {
        case 'national':
        case 'National':
        case 'nationalteam':
        case 'NationalTeam':
        case 'Nationalteam':
          $division_id = config::$nationalTeamDivision
        break;
        case 'team':
        case 'Team':
          $division_id = config::$teamDivision;
        break;
        case '80s':
        case 'eighties':
        case 'Eighties':
          $division_id = config::$eightiesDivision;
        break;
        case 'classics':
        case 'Classics':
          $division_id = config::$classicsDivision;
        break;
        case 'main':
        case 'Main':
        default:
          $division_id = config::$mainDivision;
        break;
      }
    }
    return division($division_id);
  }
  
  function getTournament($tournament) {
    if (get_class($tournament) == 'tournament' && $tournament->id) {
      $tournament_id = $tournament->id;
    } else if (is_object($tournament) && $tournament->id) {
      $tournament_id = $tournament->id;
    } else if (isId($tournament)) {
      $tournament_id = $tournament;
    } else {
      $tournament_id = config::$activeTournament;
    }
    return tournament($tournament_id);
  }
  
  function validateDate($date, $obj = FALSE) {
    if (!$date || checkdate(preg_replace('/00/','01',substr($date, 5,2)), preg_replace('/00/','01',substr($date, 8,2)), substr($date, 0,4))) {
      return validate(TRUE, 'The date is valid.', $obj);
    } else {
      return validate(FALSE, 'The date is invalid.', $obj);
    }
  }

?>