<?php
	require_once('../functions/general.php');
	require_once('mobile.php');

	echo "<html><body>";

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
	$oLabel->FromGame($iIDGame);

	if (!$bigLabel)	
	{
		echo "small label";
		echo "<div>";
		echo "<table  width=\"288pt\" style=\"table-layout: fixed;word-wrap:break-word;\" ><tr><td width=\"50%\">";
			echo "<center><b>" . $oLabel->name() . "</b><br/>(ID:" . $iIDGame . ")</center></td><td>";
			echo "<img src=\"" . $oLabel->image() . "\" /><br/>";
		echo "</td></tr></table>";
		echo "</div>";		
	}
	else
	{
		echo "big label";
	}

	if($bAutoPrint != null && $bAutoPrint == "true"){
		echo "<script>window.print()</script>";
	}
	echo "</body></html>";

?>
