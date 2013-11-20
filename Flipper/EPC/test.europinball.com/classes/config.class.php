<?php

  class config {

    public static $dbhost = 'localhost';
    public static $dbname = 'epc_test';
    public static $dbuser = 'epc';
    public static $dbpass = 'vLdqLYyvxSZermEv';
    public static $charset = 'utf8';
    public static $dbconfig = FALSE; // Set to TRUE to keep the rest of the config in the database
    
    // The below will not be used if $dbconfig is set to TRUE
    public static $parentDepth = 7;  // The depth for automatic population of parent objects.
    public static $activeTournament = 1;
 
    public static $main = TRUE;
    public static $mainDivision = 1;
    public static $mainQualLimit = 50;
 
    public static $classics = TRUE;
    public static $classicsDivision = 2;
    public static $classicsQualLimit = 32;
 
    public static $eighties = FALSE;
    public static $eightiesDivision = FALSE;
    public static $eightiesQualLimit = FALSE;
 
    public static $team = TRUE;
    public static $teamDivision = 3;
    public static $teamQualLimit = FALSE;
 
    public static $nationalTeam = TRUE;
    public static $nationalTeamDivision = 12;
    public static $nationalTeamQualLimit = FALSE;

    public static $baseHref = 'https://test.europinball.org/';
    
    
    
    
    // Do not change anything below this line!
    public static $currentTournament = 1;
    
    
    
  }

?>