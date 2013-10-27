<?php
// treeAdmin.php

include "class/uppkoppling.php";

$adminLevel = 1;
$inserten = "";

if (isset($_POST['submitTournament'])) {

	if ($adminLevel == 1) {

		// Skapa en tournament
		$tournamentName = trim($_POST['tournamentName']);
		$noc = trim($_POST['noc']);
		$creamfiles = trim($_POST['numberOfCreamfiles']);

		if ((is_numeric($noc)) AND (is_numeric($creamfiles))) {

			// Lägg in i db.
			$connect = new uppkoppling();
			$pdo = $connect->conn();
			$STH = $pdo->prepare("INSERT INTO tournament (tourName, tourNoc, tourCreamfiles)
								  SELECT * FROM (
									SELECT '$tournamentName', $noc, $creamfiles) AS tmp
								  	WHERE NOT EXISTS (
    									SELECT tourName FROM tournament WHERE tourName = '$tournamentName'
									) 
								  LIMIT 1"); 
			try {
				$STH->execute();
			}
			catch (PDOException $e) {
				    echo 'ERROR: ' . $e->getMessage();
			}

			if ($STH->rowCount() == 0)
				$inserten = "Tävlingen (\"" . $tournamentName . "\") finns redan i databasen.";
			else
				$inserten = "Tävlingen lades in i databasen";	

		}
	}
}

?>
<html>
<head>
	<title>Playoff Tree Admin (PTA meeting)</title>
	<meta charset = "UTF-8">
	<link rel = 'stylesheet' type = 'text/css' href = 'css/adminStyle.css'>
</head>

<body>
<?php
	if ($inserten != "")
		echo "<p>" . $inserten;
?>

<div>
<h2>Skapa ny tävling</h2>
<form action = 'treeAdmin.php' method = 'POST'>
	<label for = 'tournamentName'>Tävlingsnamn</label>
		<input type = 'text' name = 'tournamentName'>
	<label for = 'noc'>Antal deltagare i slutspelet</label>
		<input type = 'text' class = 'short' name = 'noc'>
	<label for = 'numberOfCreamfiles'>Antal gräddfiler</label>
		<input type = 'text' class = 'short' name = 'numberOfCreamfiles'>
	<label for = 'submitTournament'> </label>
		<input type = 'submit' name = 'submitTournament' value = 'Skapa slutspelsträd'>
</form>
</div>

<hr />

<div>

<?php

	// Ta fram befintliga tävlingar.
	$connect = new uppkoppling();
	$pdo = $connect->conn();
	$STH = $pdo->prepare("SELECT * FROM tournament
						  LEFT JOIN sets ON sets.competitionID = tournament.tourID");
	$STH->execute();

	echo "<table><tr><th>ID<th>Namn<th>Antal spelare<th>Gräddfiler</tr>";

	while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {
		$tourName = iconv("ISO-8859-1", "UTF-8", $row['tourName']);
		echo "<td>" . $row['tourID'] . "<td>" . $tourName . "<td>" . $row['tourNoc'] . "<td>" . $row['tourCreamfiles'] . "</tr>";
	}
	echo "</table>";
?>
</div>
