<?php
	require_once('../functions/general.php');
	require_once('mobile.php');

	echo "<html><body>";

	$oHTTPContext = new HTTPContext();
	$bAutoPrint = $oHTTPContext->getString("autoPrint"); //adminPlayersEdit

	$id = $oHTTPContext->getInt("playerId");

	$player = player($id);

	echo "<div>";
	echo "<table style=\"table-layout: fixed;word-wrap:break-word;\" width=\"288pt\"><tr><td width=\"50%\">";
	echo "<center>".$player->name."<br/><font size=\"6\"><b>".$player->initials."</font></b>";
	echo "<br/><font size=\"7\">".$player->id."</font><br/>".((isCountry($player->country)) ? $player->country->name : '');
//	echo "</center></td><td><img src=\"".$player->getPhoto()."\"/><br/>";
	echo "</td></tr></table>";
	echo "</div>";
		
	if($bAutoPrint != null && $bAutoPrint == "true"){
		echo "<script>window.print()</script>";
	}
	echo "</body></html>";

?>
