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
    
    public static $paymentOptions = array('Credit card', 'International bank transfer');
    public static $payPalAccount = 'alessio.Crisantemi@gmail.com';
    public static $payPalItem = 'EPC Entrance Fee';
    public static $payPalPageStyle= 'EPC';
    public static $swiftAddress = 'UNCRITM1537';
    public static $ibanAccount = 'IT 15 T 02008 14412 000102922259';
    public static $bank = 'Unicredit Banca di Roma';
    public static $paymentReciever = 'IFPA ITALIA ASSOCIAZIONE SPORTIVA DILETTANTISTICA';
    public static $defaultCurrency = 'EUR';
    public static $acceptedCurrencies = array('EUR', 'USD', 'GBP', 'CHF', 'SEK', 'DKK');
    public static $currencies = array(
      'EUR' => array(
        'name' => 'euro',
        'plural' => 'euro',
        'shortName' => 'EUR',
        'symbol' => '€',
        'format' => '€ §',
        'rate' => 1
      ), 
      'USD' => array(
        'name' => 'dollar',
        'plural' => 'dollar',
        'shortName' => 'USD',
        'symbol' => '$',
        'format' => '$ §',
        'rate' => 1.5
      ),
      'GBP' => array(
        'name' => 'pund',
        'plural' => 'pounds',
        'shortName' => 'GBP',
        'symbol' => '£',
        'format' => '£ §',
        'rate' => 0.9
      ),
      'CHF' => array(
        'name' => 'franc',
        'plural' => 'francs',
        'shortName' => 'CHF',
        'symbol' => 'Fr',
        'format' => '§ Fr',
        'rate' => 1.3
      ),
      'SEK' => array(
        'name' => 'krona',
        'plural' => 'kronor',
        'shortName' => 'SEK',
        'symbol' => 'kr',
        'format' => '§ kr',
        'rate' => 10
      ),
      'DKK' => array(
        'name' => 'krone',
        'plural' => 'kroner',
        'shortName' => 'DKK',
        'symbol' => 'kr',
        'format' => '§ kr',
        'rate' => 7.6
      )
    );
    
    public static $participationLimit = array(
      'main' => 176
    );
    public static $tshirts = TRUE;
    public static $tshirtCost = 15;
    public static $qualGroups = TRUE;
    
    public static $editSections = array(
      'profile',
      'photo',
      'security',
      't-shirts',
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
    public static $eightiesCost = 0;
 
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
    public static $supportEmail = 'support@europinball.org';
    
    public static $debug = TRUE;
    public static $showErrors = TRUE;
    public static $showWarnings = TRUE;
    
    public static $photoExts = array('png', 'jpg', 'jpeg', 'gif', 'PNG', 'JPG', 'JPEG', 'GIF');
    public static $addables = array('city', 'region');
    public static $divisions = array('main', 'classics', 'eighties', 'team', 'nationalTeam');
    public static $singleDivisions = array('main', 'classics', 'eighties');
    public static $teamDivisions = array('team', 'nationalTeam');

    const NOSEARCH = 'noSearchCriteriaProvided';

    public static $pageType = 'normal'; // Default - will be changed by AJAX calls and similar
    public static $embedded = TRUE;

  }

?>