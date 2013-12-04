<?php

  class config {

    public static $dbhost = 'localhost';
    public static $dbname = 'epc_test';
    public static $dbuser = 'epc';
    public static $dbpass = 'vLdqLYyvxSZermEv';
    public static $charset = 'utf8';
    public static $dbconfig = FALSE; // Set to TRUE to keep the rest of the config in the database
    
    // The below will not be used if $dbconfig is set to TRUE
    public static $parentDepth = 1;  // The depth for automatic population of parent objects.
    public static $activeTournament = 5;
 
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
    
    public static $loginBackend = 'pdo';
    
    public static $debug = TRUE;
    
    public static $photoExts = array('png', 'jpg', 'jpeg', 'gif', 'PNG', 'JPG', 'JPEG', 'GIF');

  }

?>