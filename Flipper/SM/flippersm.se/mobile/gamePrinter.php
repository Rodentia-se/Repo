<?php
	require_once('../functions/general.php');
	require_once('mobile.php');

	echo "<html><body>\n";

	$oHTTPContext = new HTTPContext();
	$bAutoPrint = $oHTTPContext->getString("autoPrint");

	$iIDGame = $oHTTPContext->getInt("gameId");
	$info = $oHTTPContext->getInt("info");
	$bigLabel = false;
	if ($info == 1)
	{
		$bigLabel = true;
	}

	$oLabel = new GameLabel();
	$size = 4;
	if ($bigLabel)
	{
		$size = 16;
	}
	$oLabel->FromGame($iIDGame, $size);

	if (!$bigLabel)	
	{
		echo "<div>";
		echo "<table  width=\"288pt\" style=\"table-layout: fixed;word-wrap:break-word;\" ><tr><td width=\"50%\">";
			echo "<center><b>" . $oLabel->name() . "</b><br/>(ID:" . $iIDGame . ")</center></td><td>";
			echo "<img src=\"" . $oLabel->image() . "\" /><br/>";
		echo "</td></tr></table>";
		echo "</div>";		
	}
	else
	{
		$gameNumber = 10;
		$gameAcronym = "AFM";
		$gameDivision = "Main";
		$gameComment = "SOL always gives 50.000.000";

		echo '<head><link rel="stylesheet" type="text/css" href="../css/gameinfo.css"><script type="text/javascript" src="https://www.flippersm.se/js/contrib/jquery.js"></script></head>';
		echo $oLabel->getInfo($iIDGame);
		/*
		echo '<div id="gameNumber">' . $gameNumber . "</div>\n";
		echo '<div id="gameAcronum">' . $gameAcronym . "</div>\n";
		echo '<div id="gameDivision">' . $gameDivision . "</div>\n";
		echo '<div id="gameComment">' . $gameComment . "</div>\n";
		*/
		echo '<div class="allComments"></div>\n';
		echo '<div id="gameScan"><img id="scanImage" src="' . $oLabel->image() . "\" /></div>";
		echo '<img src="../bilder/loggor/svartvit.png" id="logo" />';

	}

	if($bAutoPrint != null && $bAutoPrint == "true"){
		echo '<script>
		$("#gameInfoBalls").appendTo("#allComments");
		$("#gameInfoExtraBalls").appendTo("#allComments");
		$("#gameInfoOnePlayerAllowed").appendTo("#allComments");
		$("#gameInfoComment").appendTo("#allComments");
		window.print()</script>';
	}
	echo "</body></html>";

?>
