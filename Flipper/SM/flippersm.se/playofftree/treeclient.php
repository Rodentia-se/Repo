<?php
//treeclient.php

session_start();


include_once "class/tournament.php";
include "class/uppkoppling.php";
include "class/setupTree.php";
include "class/drawTree.php";

// Sätt all felrapportering PÅ.
   ini_set('display_errors',1);
   ini_set('display_startup_errors',1);
   error_reporting(-1);


?>

<html>
<head>
	<title>Tree-testing</title>
	<meta charset = "UTF-8">
	<link rel = 'stylesheet' type = 'text/css' href = 'css/treestyle.css'>
	<link rel = 'stylesheet' type = 'text/css' href = 'css/mi.css'>
</head>

<body>
<?php


	// Setup tree for main-slutspelet i flipper-SM

	$tree = new tournament();
	$tree->setNoc(64);
	$tree->setCreamfiles(2);

	$_SESSION['noc'] = $tree->getNoc();
	$_SESSION['sim'] = tournament::$numberOfSets;
	$_SESSION['creamfiles'] = tournament::$creamfiles; 

	// Uncomment these two rows, save and run this file once and then comment them again.
	// Crappy solution, but for now.....

	#$setupTree = new setupTree();
	#$setupTree->insertSets();



	$sparris = $tree->getSetArray();

	// Draw the tree
	$drawer = new drawTree($sparris);	


?>


</body>
</html>
