<?php

  class config {
    
    public static $login;
    public static $msg;
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
    public static $defaultCurrency = "EUR";
    public static $acceptedCurrencies = array("EUR", "USD");
    public static $currencies = array(
      'EUR' => array(
        'name' => 'euro',
        'plural' => 'euro',
        'shortName' => 'EUR',
        'symbol' => '€',
        'format' => '€ §',
        'value' => 1
      ), 
      'USD' => array(
        'name' => 'dollar',
        'plural' => 'dollar',
        'shortName' => 'USD',
        'symbol' => '$',
        'format' => '$ §',
        'value' => 0.6
      ),
      'GBP' => array(
        'name' => 'pund',
        'plural' => 'pounds',
        'shortName' => 'GBP',
        'symbol' => '£',
        'format' => '£ §',
        'value' => 1.1
      ),
      'SEK' => array(
        'name' => 'krona',
        'plural' => 'kronor',
        'shortName' => 'SEK',
        'symbol' => 'kr',
        'format' => '§ kr',
        'value' => 0.1
      )
    );
    
    public static $participationLimit = 128;
    public static $tshirts = TRUE;
    public static $tshirtCost = 15;
    public static $qualGroups = TRUE;
    
    public static $editSections = array(
      'profile',
      'photo',
      'security',
      't-shirts',
      'volunteer',
      'payment'
    );
    public static $editDivisions = array('eighties');
    
    public static $main = TRUE;
    public static $mainDivision = 15;
    public static $mainQualGroupLimit = FALSE;
    public static $mainCost = 30;
 
    public static $classics = FALSE;
    public static $classicsDivision = FALSE;
    public static $classicsQualGroupLimit = FALSE;
    public static $classicsCost = 0;
 
    public static $eighties = TRUE;
    public static $eightiesDivision = 16;
    public static $eightiesQualGroupLimit = FALSE;
    public static $eightiesCost = 10;
 
    public static $team = FALSE;
    public static $teamDivision = FALSE;
    public static $teamQualGroupLimit = FALSE;
    public static $teamCost = 0;

    public static $nationalTeam = TRUE;
    public static $nationalTeamDivision = 17;
    public static $nationalTeamQualGroupLimit = FALSE;
    public static $nationalTeamCost = 0;

    public static $baseHref = 'https://test.europinball.org/';
    public static $baseDir = '/www/test.europinball.com/';
    
    public static $debug = TRUE;
    public static $showErrors = TRUE;
    public static $showWarnings = TRUE;
    
    public static $photoExts = array('png', 'jpg', 'jpeg', 'gif', 'PNG', 'JPG', 'JPEG', 'GIF');
    public static $addables = array('city', 'region');
    public static $divisions = array('main', 'classics', 'eighties', 'team', 'nationalTeam');
    public static $singleDivisions = array('main', 'classics', 'eighties');
    public static $teamDivisions = array('team', 'nationalTeam');

    const NOSEARCH = 'noSearchCriteriaProvided';
  }

?>