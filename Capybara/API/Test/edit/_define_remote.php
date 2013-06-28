<?php
	require_once('/usr/home/johnnyjr/public_html/classes/baseClassList.php');
	function __autoload($class_name) {
		$classesPath='/usr/home/johnnyjr/public_html/classes/';
		require_once($classesPath . str_replace('_','/',$class_name) . '.php');
	}
	
	session_start();
	
	config_conf::getSingleton()->add('classesPath',$classesPath);
	config_conf::getSingleton()->add('publicClassesPath',"/beta/classes/");
	
	header('Content-Type: text/html; charset=UTF-8');
	if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) 
		ob_start("ob_gzhandler"); 
	else 
		ob_start();
 	date_default_timezone_set("Europe/Stockholm");

	//Get config singleton and read configuration from the .cnf file
	$conf=config_conf::getSingleton();
	$conf->standardDirectory=dirname(__FILE__) . '/config/';
	$conf->readDefinitions('sportstat.cnf');
	
	//Check for user preferences
	$sess=new data_session();
	if($sess->getLoggedInUserPrefsPath()) {
		$conf->readDefinitions(dirname(__FILE__) . '/config/'.$sess->getLoggedInUserPrefsPath());
	}	
	
	//Change language if requested by query string
	if(isset($_GET['lang'])) {
		$_SESSION['stats_lang']=$_GET['lang'];
	}
	//Create a new language object with the language stated in the config or in the session cookie
	if(!isset($_SESSION['stats_lang'])) {
		helper::debugPrint("Get lang from conf: ".$conf->get('lang'),"language");
		$lang=lang_lang::getSingleton($conf->get('lang'));
	}
	else {
		helper::debugPrint("Get lang from SESSION: ".$_SESSION['stats_lang'],"language");
		$lang=lang_lang::getSingleton($_SESSION['stats_lang']);
	}

	data_dataStore::clearStore();
		
	//Create data access objects
	$db = new data_readOnlyDatabase();
	$db->selectDB($conf->get('db_name'));
	$dr=new data_dataReader($db,$lang);
	
	//Get id and name of "my team" from config
	$myTeam=$conf->get('my_team');
	$myTeamName=$conf->get('my_team_name');

	$languageCodes=explode(',',$conf->get('available_langs'));
	$languages=array();
	foreach($languageCodes as $langCode) {
		$languages[$langCode]=$lang->getLanguageIdForCode($langCode);
	}
	
	//Cache objects with complex relations
	if(!$skipRelations)
		data_relationsManager::resolveRoleRelations();
	
?>