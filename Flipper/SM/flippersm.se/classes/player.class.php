<?php
  
  class player extends base {
    
    public $firstName;
    public $lastName;
    public $username;
    public $password;
    public $passwordRequired;
    public $initials;
    public $isIfpa;
    public $ifpa_id;
    public $ifpaRank;
    public $isPerson;
    public $isPlayer;
    public $player_ids = array();
    public $gender_id;
    public $gender;
    public $here;
    public $hereMain;
    public $hereClassics;
    public $hereFinal;
    public $hereFinalMain;
    public $hereFinalClassics;
    public $hereVol;
    public $hoursDiff;
    public $type;
    public $streetAddress;
    public $zipCode;
    public $telephoneNumber;
    public $mobileNumber;
    public $mailAddress;
    public $tournamentDivision;
    public $tournamentDivision_id;
    public $tournamentEdition;
    public $tournamentEdition_id;
    public $city_id;
    public $city;
    public $region_id;
    public $region;
    public $country_id;
    public $country;
    public $continent_id;
    public $continent;
    public $dateRegistered;
    public $birthDate;
    public $classics;
    public $main;
    public $adminLevel;
    public $classicsPlayerId;
    public $mainPlayerId;
    public $qualPlace;
    public $place;
    public $wpprPlace;
    public $classicsQualPlace;
    public $classicsPlace;
    public $classicsWpprPlace;
    public $qualGroup_id;
    public $mainQualGroup_id;
    public $classicsQualGroup_id;
    public $qualChangeReq;
    public $classicsQualChangeReq;
    public $volunteer;
    public $volunteer_id;
    public $hours;
    public $u18;
    public $u7;
    public $alloc;
    public $taskId;
    public $periodId;
    public $qualGroups = array();
    public $qualGroup;
    public $class = 'player';
    public $paid;
    public $payDate;
    public $ownerPaid;
    public $costs = array();
    
    public function __construct($data = null, $type = 'array') {
      parent::__construct($data, $type);
      $this->name = ($this->name) ? $this->name : $this->firstName.' '.$this->lastName;
    }
    
    public function isMemberOfTeam($dbh, $tournament = 3) { 
      $team = $this->getTeam($dbh, $this->id, $tournament);
      if ($team) {
        return $team->id;
      } else {
        return false;
      }
    }
  
    public function getTeam($dbh, $tournament = 3) {
      $query = '
        select 
          tm.id as id,
          tm.name as name,
          "team" as class,
          tm.initials as initials,
          tm.contactPlayer_id as contactPlayer_id,
          tm.contactPlayer_name as contactPlayer_name,
          tm.country as country,
          tm.country_id as country_id,
          td.name as tournamentDivision,
          td.id as tournamentDivision_id,
          te.name as tournamentEdition,
          te.id as tournamentEdition_id
        from team tm
          left join teamPlayer tp on tp.team_id = tm.id
          left join player pl on tp.player_id = pl.id
          left join tournamentDivision td on tm.tournamentDivision_id = td.id
          left join tournamentEdition te on td.tournamentEdition_id = te.id
        where td.id = '.$tournament.' and pl.person_id = '.$this->id;
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('team')) {
        return $obj;
      }
      return false;
    }
    
    public function addTshirt($dbh, $tShirt = false, $number = null) {
      $query = '
        insert into personTShirt set 
          tournamentTShirt_id = :tShirtId,
          number = :number,
          person_id = :playerId,
          firstName = :firstName,
          lastName = :lastName,
          initials = :initials,
          streetAddress = :streetAddress,
          zipCode = :zipCode,
          telephoneNumber = :telephoneNumber,
          mobileNumber = :mobileNumber,
          mailAddress = :mailAddress,
          dateRegistered = :dateRegistered
      ';
      $insert[':tShirtId'] = ($tShirt) ? $tShirt->id : null;
      $insert[':number'] = $number;
      $insert[':playerId'] = $this->id;
      $insert[':firstName'] = $this->firstName;
      $insert[':lastName'] = $this->lastName;
      $insert[':initials'] = $this->initials;
      $insert[':streetAddress'] = $this->streetAddress;
      $insert[':zipCode'] = $this->zipCode;
      $insert[':telephoneNumber'] = $this->telephoneNumber;
      $insert[':mobileNumber'] = $this->mobileNumber;
      $insert[':mailAddress'] = $this->mailAddress;
      $insert[':dateRegistered'] = date('Y-m-d');
      $sth = $dbh->prepare($query);
      if ($sth->execute($insert)) {
        return $dbh->lastInsertId();
      } else {
        return false;
      }
    }

    public function getTshirts($dbh, $tournament = 1) {
      $query = '
        select 
          pt.id as id,
          ts.name as name,
          "tshirt" as class,
          pt.number as number,
          pt.number as number_id,
          pt.dateDelivered as dateDelivered,
          pt.person_id as player_id,
          pt.id as playerTshirt_id,
          tc.name as color,
          tc.id as color_id,
          tz.name as size,
          tz.id as size_id,
          tt.id as tournamentTshirt_id,
          te.name as tournamentEdition,
          te.id as tournamentEdition_id
        from personTShirt pt
          left join tournamentTShirt tt on pt.tournamentTShirt_id = tt.id
          left join tshirt ts on tt.tshirt_id = ts.id
          left join tournamentEdition te on tt.tournamentEdition_id = te.id
          left join color tc on ts.color_id = tc.id
          left join size tz on ts.size_id = tz.id
        where (te.id = '.$tournament.' or te.id is null) and pt.person_id = '.$this->id;
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('tshirt')) {
        $objs[] = $obj;
      }
      return $objs;
    }
    
    public function getNoOfTshirts($dbh, $tournament = 1) {
      $tShirts = $this->getTshirts($dbh, $tournament);
      $noOfTshirts = 0;
      if ($tShirts) {
        foreach($tShirts as $tShirt) {
          $noOfTshirts += $tShirt->number;
        }
      }
      return $noOfTshirts;
    }

    public function getShirtSize($dbh) {
      $query = '
        select
          v.size_id as id,
          v.size as size,
          v.size_id as size_id,
          v.size as name
        from volunteer v
        where v.person_id = '.$this->id;
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject()) {
        return $obj;
      }
      return false;
    }
  
    public function setShirtSize($dbh, $sizeId) {
      $size = getSizeById($dbh, $sizeId);
      if ($size) {
        $query = '
          update volunteer
            set size_id = :sizeId,
            size = :size
          where person_id = :id
        ';
        $update[':sizeId'] = $size->id;
        $update[':size'] = $size->name;
        $update[':id'] = $this->id;
        $sth = $dbh->prepare($query);
        if ($sth->execute($update)) {
          return true;
        } else {
          return false;
        }
      }
      return false;
    }

    public function getQualGroups($dbh, $tournament = 1, $prefered = false) {
      $query = getQualGroupSelect('q', 'pq.prefered as prefered').'
        left join playerQualGroups pq
          on pq.qualGroup_id=q.id
        left join player pl
          on pq.player_id=pl.id
        left join tournamentDivision td
          on q.tournamentDivision_id = td.id
        where (td.tournamentEdition_id = '.$tournament.' or td.tournamentEdition_id is null)
          and pl.person_id='.$this->id.(($prefered) ? ' and pq.prefered = 1' : '').'
        order by q.name
      ';
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('qualGroup')) {
        $objs[] = $obj;
      }
      return $objs;
    }
    
    public function getPreferedQualGroup($dbh, $tournament) {
      return $this->getQualGroups($dbh, $tournament, true);
    }
    
    public function assignQualGroup($dbh, $qualGroup) {
      $query = '
        update player set 
          qualGroup_id = :qualGroupId
        where person_id = :id
          and tournamentDivision_id = :divisionId
      ';
      $update[':qualGroupId'] = $qualGroup->id;
      $update[':id'] = $this->id;
      $update[':divisionId'] = $qualGroup->tournamentDivision_id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }
  
    public function addVolunteer($dbh, $tournament = 1, $method = 'insert into') {
      $query = addPlayerQuery($dbh, $this, 'volunteer', $tournament, $method);
      if ($method == 'update') {
        $query[0] .= ' where person_id = :pId';
        $query[1][':pId'] = $this->id;
      }
      $sth = $dbh->prepare($query[0]);
      if ($sth->execute($query[1])) {
        $this->volunteer_id = $dbh->lastInsertId();
        $this->volunteer = true;
        return $this->volunteer_id;
      } else {
        return false;
      }
    }
  
    public function getVolunteer($dbh, $tournament = 1) {
      $query = '
        select 
          id as volunteer_id,
          coalesce(v.hours, 0) as hours
        from volunteer v
        where v.person_id = '.$this->id.'
          and v.tournamentEdition_id = '.$tournament;  
      $sth = $dbh->query($query);
      if ($obj = $sth->fetchObject()) {
        $this->hours = $obj->hours;
        $this->volunteer_id = $obj->volunteer_id;
        return $this;
      } else {
        return false;
      }
    }
    
    public function removeVolunteer($dbh, $tournament = 1) {
      $this->removeVolunteerPeriods($dbh, 'all');
      $this->removeVolunteerTasks($dbh, 'all');
      $query = 'delete from volunteer where person_id = :person_id and tournamentEdition_id = :tournamentId';
      $delete[':tournamentId'] = $tournament;
      $delete[':person_id'] = $this->id;
      $sth = $dbh->prepare($query);
      if ($sth->execute($delete)) {
        return true;
      } else {
        return false;
      }    
    }
    
    function addVolunteerPeriods($dbh, $periods, $tournament = 1) {
      foreach ($periods as $period) {
        return $this->addVolunteerPeriod($dbh, $period, $tournament);
      }
    }

    function addVolunteerPeriod($dbh, $period, $tournament = 1) {
      return $this->addVolunteerItem($dbh, $period, $tournament);
    }

    public function removeVolunteerPeriods($dbh, $periods = 'all', $tournament = 1) {
      if ($this->volunteer) {
        if ($periods = 'all') {
          $query = 'delete from volunteerPeriod where volunteer_id = :volunteer_id';
          $delete[':volunteer_id'] = $this->volunteer_id;
          $sth = $dbh->prepare($query);
          if ($sth->execute($delete)) {
            return true;
          } else {
            return false;
          }    
        } else {
          foreach ($periods as $period) {
            $this->removeVolunteerPeriod($dbh, $period, $tournament);
          }
          return true;
        }
      }
      return false;
    }
    
    function removeVolunteerPeriod($dbh, $period, $tournament = 1) {
      return $this->removeVolunteerItem($dbh, $period, $tournament);
    }

    function addVolunteerTasks($dbh, $tasks, $tournament = 1) {
      foreach ($tasks as $task) {
        return $this->addVolunteerTask($dbh, $task, $tournament);
      }
    }

    function addVolunteerTask($dbh, $task, $tournament = 1) {
      return $this->addVolunteerItem($dbh, $task, $tournament);
    }

    function removeVolunteerTasks($dbh, $tasks = 'all', $tournament = 1) {
      if ($this->volunteer) {
        if ($tasks = 'all') {
          $query = 'delete from volunteerTask where volunteer_id = :volunteer_id';
          $delete[':volunteer_id'] = $this->volunteer_id;
          $sth = $dbh->prepare($query);
          if ($sth->execute($delete)) {
            return true;
          } else {
            return false;
          }    
        } else {
          foreach ($tasks as $task) {
            $this->removeVolunteerTask($dbh, $task, $tournament);
          }
          return true;
        }
      }
      return false;
    }
    
    function removeVolunteerTask($dbh, $task, $tournament = 1) {
      return $this->removeVolunteerItem($dbh, $task, $tournament);
    }

    function addVolunteerItem($dbh, $item, $tournament = 1) {
      if (!$this->volunteer) {
        $this->addVolunteer($dbh, $tournament);
      } 
      $query = '
        insert into 
          volunteer'.ucfirst($item->class).'
        set 
          volunteer_id = :volunteerId,
          '.$item->class.'_id = :'.$item->class.'Id,
          tournamentEdition_id = :tournamentId,
          name = :name
        on duplicate key update
          volunteer_id = :volunteerId,
          '.$item->class.'_id = :'.$item->class.'Id,
          tournamentEdition_id = :tournamentId,
          name = :name
      ';
      $update = array(
        ':volunteerId' => $this->volunteer_id,
        ':'.$item->class.'Id' => $item->id,
        ':tournamentId' => $tournament,
        ':name' => $this->lastName.': '.(($item->class == 'period') ? $item->fullName : $item->name)
      );
      $sth = $dbh->prepare($query);
      if ($sth->execute($update)) {
        $lastInsertId = $dbh->lastInsertId();
        return ($lastInsertId == 0) ? true : $lastInsertId;
      } else {
        return false;
      }    
    }

    public function removeVolunteerItems($dbh, $items = 'all', $tournament = 1) {
      if ($this->volunteer) {
        if ($items = 'all') {
          $query = 'delete from volunteerTask where volunteer_id = :volunteer_id';
          $delete[':volunteer_id'] = $this->volunteer_id;
          $sth = $dbh->prepare($query);
          $sth->execute($delete);
          $query = 'delete from volunteerPeriod where volunteer_id = :volunteer_id';
          $delete[':volunteer_id'] = $this->volunteer_id;
          $sth = $dbh->prepare($query);
          $sth->execute($delete);
          return true;
        } else {
          foreach ($items as $item) {
            $this->removeVolunteerItem($dbh, $item, $tournament);
          }
          return true;
        }
      }
      return false;
    }
    
    public function removeVolunteerItem($dbh, $item, $tournament = 1) {
      if ($this->volunteer) {
        $query = '
          delete 
            from volunteer'.ucfirst($item->class).'
          where
            volunteer_id = :volunteerId and
            '.$item->class.'_id = :'.$item->class.'Id and
            tournamentEdition_id = :tournamentId
        ';
        $delete = array (
          ':volunteerId' => $this->volunteer_id,
          ':'.$item->class.'Id' => $item->id,
          ':tournamentId' => $tournament
        );
        $sth = $dbh->prepare($query);
        if ($sth->execute($delete)) {
          return true;
        }
      }
      return false;
    }

    function getCosts($dbh, $type = 'all', $currency = false) {
      $currencies = array('SEK' => 1, 'EUR' => 9, 'GBP' => 10, 'USD' => 6);
      $currencies = ($currency) ? array($currency => $currencies[$currency]) : $currencies;
      $items = array(
        'main' => (($this->u7) ? 0 : (($this->u18) ? 100 : 300)),
        'classics' => (($this->u7) ? 0 : 200),
        'team' => (($this->u7) ? 0 : 100),
        'tShirt' => 100
      );
      foreach ($items as $item => $price) {
        $cost[$item]['price'] = $price;
        switch ($item) {
          case 'main':
            $output[$item]['num'] = ($this->mainPlayerId > 0) ? 1 : 0;
          break;
          case 'classics':
            $output[$item]['num'] = ($this->classicsPlayerId > 0) ? 1 : 0;
          break;
          case 'team':
            $team = $this->getTeam($dbh);
            $output[$item]['num'] = ($team) ? count($team) : 0;
            $team = $this->getTeam($dbh, 12);
            $output[$item]['num'] += ($team) ? count($team) : 0;
          break;
          case 'tShirt':
            $output[$item]['num'] = $this->getNoOfTshirts($dbh);
          break;
        }
        foreach($currencies as $cur => $rate) {
          $output[$item][$cur] = round($output[$item]['num'] * $price / $rate);
          $output['all'][$cur] += round($output[$item]['num'] * $price / $rate);
        }
      }
      $this->costs = $output;
      return $output;
    }
    
    function setNonce($dbh, $nonce) {
      $update[':id'] = $this->id;
      $update[':nonce'] = $nonce;
      $query = 'update person set nonce = :nonce where id = :id';
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }
  
    function getIfpaLink() {
      if ($this->ifpa_id) {
        return '<a href="http://www.ifpapinball.com/player.php?player_id='.$this->ifpa_id.'" target="_new">'.(($this->ifpaRank && $this->ifpaRank != 0) ? $this->ifpaRank : 'Unranked').'</a>';
      } else {
        return 'Unranked';
      }
    }
    
    function setPaid($dbh, $paid = false) {
      $paid = (preg_match('/^[0-9]+$/',$paid)) ? $paid : $this->paid;
      $paid = ($paid) ? $paid : 0;
      $query = '
        update player set 
          paid = :paid,
          payDate = coalesce(payDate, now()) 
        where id = :id
      ';
      $update[':paid'] = $paid;
      $update[':id'] = $this->mainPlayerId;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }
    
    function setQualChangeReq($dbh, $tournament = 1, $req = true) {
      $query = '
        update player set
          qualChangeReq = :req
        where person_id = :id
          and tournamentDivision_id = :tournament
      ';
      $update[':req'] = ($req) ? 1 : 0;
      $update[':id'] = $this->id;
      $update[':tournament'] = $tournament;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }
    
    function setHereFinal($dbh, $here = true) {
      return $this->setHere($dbh, $here, 'final');
    }
    
    function setHereVol($dbh, $here = true) {
      return $this->setHere($dbh, $here, 'vol');
    }

    function setHere($dbh, $here = true, $type = 'qual') {
      switch ($type) {
        case 'final':
          $hereField = 'hereFinal';
          $table = 'player';
        break;
        case 'vol':
          $hereField = 'here';
          $table = 'volunteer';
        break;
        default:
          $hereField = 'here';
          $table = 'player';
        break;
      }
      $query = '
        update '.$table.' set
          '.$hereField.' = :here
        where person_id = :id
          and tournamentEdition_id = :tournament
      ';
      $update[':here'] = ($here) ? 1 : 0;
      $update[':tournament'] = $this->tournamentEdition_id;
      $update[':id'] = $this->id;
      $sth = $dbh->prepare($query);
      $sth->execute($update);
      $player = getPlayerById($dbh, $this->id);
      switch ($type) {
        case 'final':
          return ((bool) $player->hereFinal ==  $here) ? true : false;
        break;
        case 'vol':
          return ((bool) $player->hereVol == $here) ? true : false;
        break;
        default:
          return ((bool) $player->here == $here) ? true : false;
        break;
      }
    }

    function setPhone($dbh, $number = null, $cell = false) {
      $phoneField = ($cell) ? 'mobileNumber' : 'telephoneNumber';
      $query = '
        update player set 
          '.$phoneField.' = :number
        where person_id = :id
      ';
      $update[':number'] = $number;
      $update[':id'] = $this->id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function setAdmin($dbh, $adminLevel = 1) {
      $query = '
        update player set 
          adminLevel = :adminLevel
        where id = :id
      ';
      $update[':adminLevel'] = $adminLevel;
      $update[':id'] = $this->mainPlayerId;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function getEntry($dbh, $tournament = 1, $division = 1) {
      return $this->getEntries($dbh, $tournament, $division);
    }

    function getQualEntry($dbh, $tournament = 1, $division = 1) {
      return $this->getEntries($dbh, $tournament, $division);
    }

    function getQualEntries($dbh, $tournament = 1, $division = 1) {
      return $this->getEntries($dbh, $tournament, $division);
    }

    function getEntries($dbh, $tournament = 1, $division = 1) {
      $query = getEntrySelect().'
        left join qualScore qs
          on qe.id = qs.qualEntry_id
        where qe.person_id = '.$this->id;
      $query .= ($tournament) ? ' and qe.tournamentEdition_id = '.$tournament : '';
      $query .= ($division) ? ' and qe.tournamentDivision_id = '.$division : '';
      $query .= ' group by qe.id';
      $query .= ' order by qe.points desc, qe.place asc';
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('entry')) {
        $objs[] = $obj;
      }
      return $objs;
    }

    function getQualScores($dbh, $tournament = 1, $division = 1) {
      return getScores($dbh, $tournament, $division);
    }

    function getScores($dbh, $tournament = 1, $division = 1, $groupBy = 'group by qs.machine_id', $orderBy = 'order by max(qs.points) desc, min(qs.place) asc') {
      $query = getScoreSelect(($groupBy) ? true : false).'
        where qs.person_id = '.$this->id;
      $query .= ($tournament) ? ' and qs.tournamentEdition_id = '.$tournament : '';
      $query .= ($division) ? ' and qs.tournamentDivision_id = '.$division : '';
      $query .= ' '.$groupBy.' '.$orderBy;
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('score')) {
        $objs[] = $obj;
      }
      return $objs;
    }

    function getAllEntries($dbh, $groupBy = false, $tournament = 1) {
      $query = '
        select
          qe.person_id as id,
          qe.person_id as person_id,
          qe.tournamentDivision_id as tournamentDivision_id,
          qs.id as qualScoreId,
          qe.id as qualEntryId,
          qs.place as place,
          qe.place as entryPlace,
          qs.points as points,
          qe.points as entryPoints,
          qs.score as score,
          qs.round as round,
          qe.realPlayer_id as playerId,
          qe.firstName as firstName,
          qe.lastName as lastName,
          qe.country_id as countryId,
          qe.country as country,
          qs.machine_id as machineId,
          qs.game_id as gameId,
          qs.gameAcronym as gameAcronym,
          ';
      $query .= ($groupBy) ? '
          max(qs.score) as maxScore,
          max(qs.points) as maxPoints,
          min(qs.place) as bestPlace,
      ' : '';
      $query .='
          qs.game as game
        from qualScore qs
          left join qualEntry qe
            on qs.qualEntry_id = qe.id
        where qe.person_id = '.$this->id.'
          and qe.tournamentDivision_id = '.$tournament.' ';
      $query .= ($groupBy) ? $groupBy : '';
      $query .= ($groupBy) ? ' order by bestPlace asc' : ' order by qs.place asc';
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('entry')) {
        $objs[] = $obj;
      }
      return $objs;
    }
    
    function createEntry($dbh, $games = null) {
      $query = '
        insert into qualEntry set
          name = :name,
          player_id = :playerId,
          person_id = :personId,
          firstName = :firstName,
          lastName = :lastName,
          initials = :initials,
          tournamentDivision_id = :division,
          tournamentEdition_id = :tournament,
          country_id = :countryId,
          country = :country,
          city_id = :cityId,
          city = :city
      ';
      $insert[':name'] = $this->firstName.' '.$this->lastName.', '.(($this->tournamentDivision_id == 1) ? 'Main' : 'Classics').', 2013';
      $insert[':playerId'] = ($this->tournamentDivision_id == 1) ? $this->mainPlayerId : $this->classicsPlayerId;
      $insert[':personId'] = $this->id;
      $insert[':firstName'] = $this->firstName;
      $insert[':lastName'] = $this->lastName;
      $insert[':initials'] = $this->initials;
      $insert[':countryId'] = $this->country_id;
      $insert[':country'] = $this->country;
      $insert[':cityId'] = $this->city_id;
      $insert[':city'] = $this->city;
      $insert[':division'] = $this->tournamentDivision_id;
      $insert[':tournament'] = $this->tournamentEdition_id;
      $sth = $dbh->prepare($query);
      if ($sth->execute($insert)) {
        $lastInsertId = $dbh->lastInsertId();
      } else {
        return false;
      }
      if ($games && is_array($games)) {
        $qualEntry = getEntryById($dbh, $lastInsertId);
        foreach ($games as $game) {
          if (!$qualEntry->createScore($dbh, $game)) {
            return false;
          }
        }
      }
      return $lastInsertId;
    }

    function checkIfVolFree($dbh, $period) {
      $query = '
        select count(*) from qualGroup q
          left join player pl
            on pl.qualGroup_id = q.id
          left join period p
            on p.id = '.$period->id.'
          where 
            q.date = "'.$period->date.'"
            and (
              (
                replace(replace(p.startTime, "24:00:00", "23:59:00"), "00:00:00", "23:59:00") > replace(replace(q.startTime, "24:00:00", "23:59:00"), "00:00:00", "23:59:00") 
                and replace(replace(p.startTime, "24:00:00", "23:59:00"), "00:00:00", "23:59:00") < replace(replace(q.endTime, "24:00:00", "23:59:00"), "00:00:00", "23:59:00")
              ) or (
                replace(replace(p.endTime, "24:00:00", "23:59:00"), "00:00:00", "23:59:00") > replace(replace(q.startTime, "24:00:00", "23:59:00"), "00:00:00", "23:59:00") 
                and replace(replace(p.endTime, "24:00:00", "23:59:00"), "00:00:00", "23:59:00") < replace(replace(q.endTime, "24:00:00", "23:59:00"), "00:00:00", "23:59:00")
              )
            )
            and pl.person_id = '.$this->id;
      $sth = $dbh->query($query);
      if ($sth->fetchColumn() > 0) {
        return false;
      } else {
        return true;
      }
    }
    
    function setPoints($dbh, $division = 1) {
      $entries = $this->getEntries($dbh);
      if ($entries) {
        foreach ($entries as $entry) {
          $entry->calcPoints($dbh);          
        }
        return true;
      } else {
        return false;
      }
    }
    
    function setPlace($dbh, $place = 0, $division = 1, $wppr = false) {
      $query = '
        update player set
          '.(($wppr) ? 'wpprP' : 'p').'lace = :place
        where
          person_id = :id
          and tournamentDivision_Id = :division
      ';
      $update[':place'] = $place;
      $update[':division'] = $division;
      $update[':id'] = $this->id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }
    
    function getResults($dbh) {
      $content = '
      <p class="submenu2 clearboth" id="tab_links" style="display: '.(($_REQUEST['active']) ? '' : '').';">
        <a href="#mainTable" style="display: '.(($_REQUEST['active']) ? '' : '').';">Main</a>
          '.(($this->classics) ? '<a href="#classicsTable">Classics</a>' : '').'
      ';
      $content .= $this->getResultsByDivision($dbh, 1);
      $content .= ($this->classics) ? $this->getResultsByDivision($dbh, 2) : '';
      $content .= '</div>'.getDataTables('.scores');
      return $content;
    }

    function getResultsByDivision($dbh, $division) {
      $entries = $this->getEntry($dbh, null, $division);
      if ($entries) {
        foreach ($entries as $entry) {
          $content .= '
            <div id="'.(($division == 1) ? 'main' : 'classics').'Table" class="section" style="display: '.(($_REQUEST['active']) ? '' : '').';">
            <p>'.$this->name.' har entry ID '.$entry->id.(($entry->points) ? ' med <span title="'.$entry->points.">'.round($entry->points).'</span> poäng' : '').(($entry->place) ? ' på plats '.$entry->place : '').'</p>
          ';
        }
      }
      $content .= '
          <table class="scores">
            <thead>
              <tr>
                <th>Place</th>
                <th>Game</th>
                <th>Score</th>
                <th>Points</th>
              </tr>
            </thead>
            <tbody>
      ';
      $scores = $this->getScores($dbh, null, $division, false, false);
      if ($scores) {
        foreach ($scores as $score) {
          $content .= '
              <tr>
                <td>'.$score->place.'</td>
                <td><a href="'.__baseHref__.'/?s=object&obj=game&id='.$score->gameId.'">'.$score->game.(($score->checkAlone($dbh)) ? ' (Ensam)' : '').'</a></td>
                <td>'.$score->score.'</td>
                <td><span title="'.$score->points.'">'.round($score->points).'</span></td>
              </tr>
          '; 
        }
      }
      $content .= '
            </tbody>
          </table>
        </div>
      ';
      return $content;
    }

    function getQR($link = true, $right = false) {
      $qr = '<img src="'.__baseHref__.'/images/qr.png" class="icon'.(($right) ? ' right' : '').'" alt="QR" title="Click for QR code">';
      return ($link) ? '<a href="'.__baseHref__.'/mobile/playerPrinter.php?playerId='.$this->id.'&autoPrint=true" target="_blank">'.$qr.'</a>' : $qr;
    }


  }
?>