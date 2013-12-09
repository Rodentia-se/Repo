<?php

  class config {
    
    public function __contruct($tournament) {
      if (isId($_REQUEST['tournament']) || isId($_REQUEST['t'])) {
        $id =(isId($_REQUEST['tournament'])) ? $_REQUEST['tournament'] : $_REQUEST['t'];
        $tournament = tournament($id);
        if ($tournament) {
          self::$currentTournament = $id;
        }
      }
    }
    
    public static $currentTournament = 1;

    public static $dbhost = 'localhost';
    public static $dbname = 'epc_test';
    public static $dbuser = 'epc';
    public static $dbpass = 'vLdqLYyvxSZermEv';
    public static $charset = 'utf8';
    public static $dbconfig = FALSE; // Set to TRUE to keep the rest of the config in the database
    
    // The below will not be used if $dbconfig is set to TRUE
    public static $parentDepth = 1;  // The depth for automatic population of parent objects.
    public static $activeTournament = 5;
    public static $activeDivisions = array('main', 'eighties', 'nationalTeam');
    public static $activeSingleDivisions = array('main', 'eighties');
    public static $activeTeamDivisions = array('nationalTeam');
    
    public static $participationLimit = 128;
    public static $tshirts = TRUE;
    public static $qualGroups = TRUE;
    
    public static $editSections = array(
      'profile',
      'photo',
      'security',
      't-shirts',
      'volunteer'
    );
    public static $editDivisions = array('eighties');
    
    public static $main = TRUE;
    public static $mainDivision = 15;
    public static $mainQualLimit = FALSE;
 
    public static $classics = FALSE;
    public static $classicsDivision = FALSE;
    public static $classicsQualLimit = FALSE;
 
    public static $eighties = TRUE;
    public static $eightiesDivision = 16;
    public static $eightiesQualLimit = FALSE;
 
    public static $team = FALSE;
    public static $teamDivision = FALSE;
    public static $teamQualLimit = FALSE;
 
    public static $nationalTeam = TRUE;
    public static $nationalTeamDivision = 17;
    public static $nationalTeamQualLimit = FALSE;

    public static $baseHref = 'https://test.europinball.org/';
    public static $baseDir = '/www/test.europinball.com/';
    
    public static $debug = TRUE;
    
    public static $photoExts = array('png', 'jpg', 'jpeg', 'gif', 'PNG', 'JPG', 'JPEG', 'GIF');
    public static $addables = array('city', 'region');
    public static $divisions = array('main', 'classics', 'eighties', 'team', 'nationalTeam');
    public static $singleDivisions = array('main', 'classics', 'eighties');
    public static $teamDivisions = array('team', 'nationalTeam');

    const NOSEARCH = 'noSearchCriteriaProvided';
  }

?>