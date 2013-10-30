	<?php
	require_once('../functions/general.php');
	require_once('mobile.php');

	echo "<html><body>";

	$oHTTPContext = new MHTTPContext();
	$bAutoPrint = $oHTTPContext->getString("autoPrint"); //adminPlayersEdit

	$iIDTeam = $oHTTPContext->getInt("teamId");

	$oLabel = new MTeamLabel();
	$oLabel->FromTeam($iIDTeam);

	echo "<div>";
	echo "<table style=\"table-layout: fixed;word-wrap:break-word;\" width=\"288pt\"><tr><td width=\"50%\">";
	echo "<center> <br/><font size=\"6\"><b>" . $oLabel->initials() . "</font></b> ";
	echo "<br/><font size=\"7\">" . $iIDTeam . "</font><br/> " . $oLabel->country();
	echo "</center></td><td><img src=\"" . $oLabel->image() . "\"/><br/>";
	echo "</td></tr></table>";
	echo "</div>";
		
	if($bAutoPrint != null && $bAutoPrint == "true"){
		echo "<script>window.print()</script>";
	}
	echo "</body></html>";

?>
