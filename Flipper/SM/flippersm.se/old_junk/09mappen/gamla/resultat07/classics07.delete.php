<html>
<head>
<link href="classics07.css" rel="stylesheet" type="text/css">
<body>


<h2>V�CKTAGNING I CLASSICS07</h2>

<?php
include("classics07.header.php");

$strTry_id = $_GET['try_id'];


// open connection
$db = MySQL_connect("localhost", "sm", "slamtilt");
MySQL_select_db("classics07", $db);


// save post for double check
$sqlChk = "SELECT * FROM Qual WHERE Try_id = '$strTry_id'";
$sqlResultChk = MySQL_query($sqlChk,$db);

// delete post
$sqlDel = "DELETE FROM Qual WHERE Try_id = '$strTry_id'";
$sqlResultDel = MySQL_query($sqlDel,$db);


if($sqlResultDel)
    {
    echo "<p>Posten med f�rs�ksid:t $strTry_id togs v�ck. Den s�g ut s�h�r:</p>\n";
    echo "<table border='0' cellpadding='4'>";
    printf("  <tr><td align='right'><i> Try_id: </td><td> <span class='nytext'>%d</span></i> </td></tr>\n",  MySQL_result($sqlResultChk,0,"Try_id"));
    printf("  <tr><td align='right'><i> Spelarnummer: </td><td> <span class='nytext'>%d</span> </td></tr>\n", MySQL_result($sqlResultChk,0,"Player_no"));
    printf("  <tr><td align='right'><i> Tag: </td><td> <span class='nytext'>%s</span> </td></tr>\n", MySQL_result($sqlResultChk,0,"Tag"));
    printf("  <tr><td align='right'><i> Spelnummer: </td><td> <span class='nytext'>%d</span> </td></tr>\n", MySQL_result($sqlResultChk,0,"Game_id"));
    printf("  <tr><td align='right'><i> Spelpo�ng: </td><td> <span class='nytext'>%s</span></i> </td></tr>\n", number_format(MySQL_result($sqlResultChk,0,"Game_points"), 0, ',', ' '));
    echo "</table><br>\n";
    }
else
    {
    echo "<p>�h, det bidde knas. Kalla p� CAB!</p>\n";
    }


MySQL_close($db);
php?>


<hr><br>

A Calle Be production.<br>

</body></html>