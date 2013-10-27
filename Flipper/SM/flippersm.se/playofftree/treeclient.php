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
	<link rel = 'stylesheet' type = 'text/css' href = 'css/treestyle_creamfile.css'>
	<link rel = 'stylesheet' type = 'text/css' href = 'css/mi.css'>
</head>

<body>
<?php


	// Setup tree with initial eight players

	$tree = new tournament();
	$tree->setNoc(64);
	$tree->setCreamfiles(2);

	$_SESSION['noc'] = $tree->getNoc();
	$_SESSION['sim'] = tournament::$numberOfSets;
	$_SESSION['creamfiles'] = tournament::$creamfiles; 

	#$setupTree = new setupTree();
	#$setupTree->insertSets();

	$sparris = $tree->getSetArray();

	// Draw the tree
	$drawer = new drawTree($sparris);	

	// var_dump($drawer->getSets(2));

	# $tree8 = new drawTree($comp, $filer, $rankarray, $tagarray);


?>


</body>
</html>
