<?php
  require_once('../functions/general.php');
  require_once('mobile.php');

    $oHTTPContext = new HTTPContext();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EPC Standings">
    <meta name="author" content="Andreas Thorsen">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">

    <title>EPC 2013 standings</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="bootstrap/css/standings.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="https://test.europinball.org/mobile/standings.php">EPC 2013</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="https://test.europinball.org/mobile/standings.php">Main</a></li>
            <li><a href="https://test.europinball.org/mobile/standings.php?classics=1">Classics</a></li>
            <li><a href="https://test.europinball.org/mobile/standings.php?games=1">Games</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

      <div class="starter-template">

<?php

$oEntry = new Entry();

$games = $oHTTPContext->getInt("games");

if ($games != null && $games == 1) {

	echo '<h1>Games - ' . $divstr . '</h1>';

	echo '<table class="table table-striped table-hover">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Place</th><th>Name</th><th>Points</th>';
	echo '</tr>';
	echo '</thead>';
    echo '<tbody>';

	$gamescores = $oEntry->getStandingsScores($division);

	foreach ($gamescores as $game)
	{
		echo '<tr><td>' . $game->place . '</td><td>' . $game->firstName . ' ' . $game->lastName . '</td><td>' . $game->points . '</td></tr>';
	}

	echo '</tbody>';
	echo '</table>';

} else {

	$classics = $oHTTPContext->getInt("classics");
	$division = 1;
	$divstr = 'Main';
	if ($classics != null)
	{
		$division = 2;
		$divstr = 'Classics';
	}
	echo '<h1>Standings - ' . $divstr . '</h1>';

	echo '<table class="table table-striped table-hover">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Place</th><th>Name</th><th>Points</th>';
	echo '</tr>';
	echo '</thead>';
    echo '<tbody>';

	$entries = $oEntry->getStandings($division);

	foreach ($entries as $entry)
	{
		echo '<tr><td>' . $entry->place . '</td><td>' . $entry->firstName . ' ' . $entry->lastName . '</td><td>' . $entry->points . '</td></tr>';
	}

	echo '</tbody>';
	echo '</table>';
}

?>
	</div>

	</div><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
  </body>
</html>
