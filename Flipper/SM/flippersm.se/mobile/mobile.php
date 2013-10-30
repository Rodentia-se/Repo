<?php
  require_once('../functions/general.php');
  require_once("phpqrcode/phpqrcode.php");

  define('MOB_SAVE_URL_PLAYER', __baseHref__ . "/images/objects/mobile/playerimg.png");
  define('MOB_LOAD_URL_PLAYER', __ROOT__."/images/objects/mobile/playerimg.png");
  define('MOB_SAVE_URL_TEAM', __baseHref__ . "/images/objects/mobile/teamimg.png");
  define('MOB_LOAD_URL_TEAM', __ROOT__."/images/objects/mobile/teamimg.png");
  define('MOB_SAVE_URL_GAME', __baseHref__ . "/images/objects/mobile/gameimg.png");
  define('MOB_LOAD_URL_GAME', __ROOT__."/images/objects/mobile/gameimg.png");

  define('MOB_LOG_FILE_NAME', __ROOT__."/../logs/mobile.log");

  class DataMapper 
  {
    public static $db;
    public static function init($dbh)
    {
      self::$db = $dbh;
    }
  }

  DataMapper::Init($dbh);

  class LoginMapper
  {
    public static $ulogin;
    public static function init($ulogin)
    {
      self::$ulogin = $ulogin;
    }
  }

  LoginMapper::Init($ulogin);

  class HTTPContext
  {
    public function getString($name) 
    {
      if(isset($_POST[$name]))
        return trim(strval($_POST[$name]));
      if(isset($_GET[$name]))
        return trim(strval($_GET[$name]));    
      return null;      
    }

    public function getInt($name) 
    {
      if(isset($_POST[$name]))
        return trim(intval($_POST[$name]));
      if(isset($_GET[$name]))
        return trim(intval($_GET[$name]));    
      return null;      
    }

    public function Log($statusCode)
    {
        $debug = false;
        if ($debug)
        {
          unlink(MOB_LOG_FILE_NAME);
        } else {
          $playerId = "player:".$this->getString("playerId");
          $gameId = "game:".$this->getString("gameId");
          $score = "score:".$this->getString("score");
          $user = "user:".$this->getString("user");
          $log_line = date('Y-m-d H:i:s') . "," . $playerId . "," . $gameId . "," . $score . "," . $user . "," . $statusCode . "\n";
          file_put_contents(MOB_LOG_FILE_NAME, $log_line, FILE_APPEND);
        }
    }
  }

  class Player
  {
    public function getPlayer($id)
    {
      return getPlayerById(DataMapper::$db, $id);
    }
  }

  class Team
  {
    public function getTeam($id)
    {
      return getTeamById(DataMapper::$db, $id);
    }
  }

  class User
  {
    private $adminLevel;

    public function logIn($userName, $passWord)
    {
      $result = false;
      $this->adminLevel = getPlayerAdminLevel(DataMapper::$db, $userName);
      if ($this->adminLevel > 0)
      {
        LoginMapper::$ulogin->Authenticate($userName, $passWord);
        $result = LoginMapper::$ulogin->IsAuthSuccess();
      }
      return $result;
    }
    public function getAdminLevel()
    {
      return $this->adminLevel;
    }
  }

  class Entry
  {
    private $entry = null;

    public function fromPlayerAndDivision($idPlayer, $idDivision)
    {
      $e = getEntry(DataMapper::$db, $idPlayer, $idDivision);
      return $e;
    }

    public function getScores($idEntry, $idMachine)
    {
      // return value
      $scores = array();
      $where = 'where qualEntry_id = ' . $idEntry . ' and machine_id = ' . $idMachine;
      $s = getScores(DataMapper::$db, $where);
      if ($s != false)
      {
        foreach ($s as $score)
        {
          $scores[] = $score;
        }
        return $scores;
      }
      return false;
    }

    public function isValidEntryID($idEntry)
    {
      $result = true;
      if ($idEntry == null || $idEntry == '' || $idEntry == false){
        $result = false;
      } else {
        if (getEntryById(DataMapper::$db, $idEntry) == false){
          $result = false;
        }
      }
      return $result;
    }

    public function updateScore($idScore, $iScore)
    {
      updateScore(DataMapper::$db, $idScore, $iScore);
    }

    public function getStandings($division)
    {
      $query = '
        select 
          qualEntry.id, 
          qualEntry.firstName, 
          qualEntry.lastName, 
          qualEntry.points, 
          qualEntry.place 
        from qualEntry 
          where qualEntry.tournamentDivision_id = ' . $division . '
        and 
          qualEntry.place != "NULL" 
        order by 
          qualEntry.place
      ';
      $sth = DataMapper::$db->query($query);
      while ($obj = $sth->fetchObject('entry')) {
        $objs[] = $obj;
      }
      return $objs;
    }
    /*
    public function getStandingsScores($division)
    {
      $query = '
        select 
          qualScore.id, 
          qualScore.firstName, 
          qualScore.lastName, 
          qualScore.points, 
          qualScore.place, 
          qualScore.game, 
          qualScore.score 
        from qualScore 
        inner join machine on 
          qualScore.machine_id=machine.id 
        where 
          machine.tournamentDivision_id=1 
        and 
          qualScore.place != "NULL" 
        order by 
          qualScore.game, qualScore.place
        ';
      $sth = DataMapper::$db->query($query);
      while ($obj = $sth->fetchObject('score')) {
        $objs[] = $obj;
      }
      return $objs;
    }
    }
    */
  }


  class Game
  {
    public function getDivision($idGame)
    {
      $m = getMachineById(DataMapper::$db, $idGame);
      return $m->tournamentDivision_id;
    }
  }

  class String
  {
    public function stripNonNumericChars($str)
    {
      $aAllowed = array("1","2","3","4","5","6","7","8","9","0");
      $iStrLength = strlen($str);
      $sRet = null;
      for($i = 0; $i < $iStrLength; $i++)
      {
        $cChar = substr($str, $i, 1);
        if(in_array($cChar, $aAllowed))
          $sRet .= $cChar;
      }
      return $sRet;
    }
  }

  class PlayerLabel
  {
    private $tag;
    private $fname;
    private $lname;
    private $country;
    private $publicImg = MOB_SAVE_URL_PLAYER;
    private $playerImg = MOB_LOAD_URL_PLAYER;
    public function FromPlayer($playerId)
    {
      $oPlayer = new Player();

      $aPlayer = $oPlayer->getPlayer($playerId);
      if ($aPlayer != false)
      {
        $this->tag  = ($aPlayer->initials) ? ($aPlayer->initials) : 'N/A';
        $this->fname = $aPlayer->firstName;
        $this->lname = $aPlayer->lastName;
        $this->country = $aPlayer->country;
      }
      $qrText = "pid=" . $playerId . "&tag=" .  $this->tag;
      QRcode::png($qrText, $this->playerImg, 0, 6, 0);
    }
    public function initials()
    {
      return $this->tag;
    }
    public function firstName()
    {
      return $this->fname;
    }
    public function lastName()
    {
      return $this->lname;
    }
    public function country()
    {
      return $this->country;
    }
    public function image()
    {
      return $this->publicImg;
    }
  }

  class TeamLabel
  {
    private $tag;
    private $fname;
    private $lname;
    private $country;
    private $publicImg = MOB_SAVE_URL_TEAM;
    private $teamImg = MOB_LOAD_URL_TEAM;
    public function FromTeam($teamId)
    {
      $oTeam = new Team();

      $aTeam = $oTeam->getTeam($teamId);

      if ($aTeam != false)
      {
        $this->tag  = ($aTeam->initials) ? ($aTeam->initials) : 'N/A';
        $this->fname = $aTeam->firstName;
        $this->lname = $aTeam->lastName;
        $this->country = $aTeam->country;
      }
      $qrText = "tid=" . $teamId . "&tag=" .  $this->tag;
      QRcode::png($qrText, $this->teamImg, 0, 6, 0);
    }
    public function initials()
    {
      return $this->tag;
    }
    public function firstName()
    {
      return $this->fname;
    }
    public function lastName()
    {
      return $this->lname;
    }
    public function country()
    {
      return $this->country;
    }
    public function image()
    {
      return $this->publicImg;
    }
  }

  class GameLabel
  {
    private $name;
    private $publicImg = MOB_SAVE_URL_GAME;
    private $gameImg = MOB_LOAD_URL_GAME;
    public function FromGame($gameId, $size = 4)
    {
      $aGame = getMachineById(DataMapper::$db, $gameId);

      if ($aGame != false)
      {
        $this->name  = $aGame->name;
      }

      $qrText = "gid=" . $gameId . "&game=" .  $this->name;
      QRcode::png($qrText, $this->gameImg, 0, $size, 1);
    }
    public function name()
    {
      return $this->name;
    }
    public function image()
    {
      return $this->publicImg;
    }
    public function getInfo($gameId)
    {
      $game = getMachineById(DataMapper::$db, $gameId);
      return $game->getPrintInfo(DataMapper::$db, 'div', 'gameInfo');
    }
  }

  class Validator
  {
      function positiveInt($inData)
      {
        if($inData == null)
          return false;
        
        if($inData == 0)
          return false; 
          
        if(preg_match('/^[0-9]+$/', $inData))
          return true;
        else
          return false;
      }
  }
?>
