<?
session_start();
if (!isset($_SESSION['ok_user']))
{
echo "Du �r inte inloggad...";
exit;
}
?>
<html>
<head>
<title></title>
</head>
<body
bgcolor="ffffff"
link="000000"
vlink="000000">

<font face="verdana">
<font color="000000">
<?
echo "<font size='2'>";
echo "<b>Anm�lningar</b>";
echo "<br><br>";
echo "<font size='1'>";
echo "Vill du se anm�lningarna eller uppdatera dem kan du g�ra det <b><a href='anmalda_admin.php'>h�r</b></a>.";
echo "<br><br>";
?>
<font size="1">
<font size="2">
<b>Nyheter</b>
<br>
<br>
<font size="1">
H�r kan man, minsann, l�gga in och ta bort Nyheter som visas p� Flipper-SMs startsida. N�r du l�gger in en nyhet laddas denna sida om och din nyhet hamnar nedan bland "Inlagda Nyheter" och...�h... och hamnar givetvis p� www.flippersm.se:s startsida. Gl�m inte att fylla i ditt namn i "L�ggs in av".
<br><br>
<?
echo "<table>";
echo "<tr>";
echo "<td><font size='2'><b>L�gg in en Nyhet:</td>";
echo "</tr>";
echo "</table>";
echo "<form action='ny_nyhet.php' name='inlagg' method='post'>";

echo "<table>";
echo "<tr>";
echo "<td><font size='1'><b>L�ggs in av:</b></td>";
echo "</tr>";
echo "<tr>";
echo "<td><input type=text name=av maxlength=100 size=30><br></td>";
echo "</tr>";

echo "<tr>";
echo "<td><font size='1'><b>Text:</b></td>";
echo "</tr>";

echo "<tr>";
echo "<td colspan='4'><textarea name='text' cols=40 rows=6></textarea>";
echo "</tr>";

echo "<td width='410'>";
echo "</td>";

echo "</tr>";

echo "<tr>";
echo "<td colspan=2><input type=submit class='egen' value='L�gg In'></td>";
echo "</tr>";
echo "</table>";
echo "</form>";
?>
<font size="2">
<b>Inlagda Nyheter:</b>
<br>
<br>
<font size="1">
Klicka p� "Radera" om du vill ta bort nyheten.
<br><br>
<?
require("dbas/dbas.php");
require("datumform2.php");
require("datumform.php");
require("tidform.php");

$query = "select *
	 from nyheter
 	 order by datum desc
	 ";
	 
$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error()); 
$num_results = mysql_num_rows($result);

echo "<table style='border-collapse: collapse;' width='400'>";

for ($i=0; $i < $num_results; $i++)
{
    $row = mysql_fetch_array($result);

    echo "<tr bgcolor='f1f1f1'>";
    $av = htmlspecialchars( stripslashes($row["av"]));
    echo "<td>";
    echo "<font size='1'><b>Inlagd av:</b> $av";
    echo "</td>";

    echo "<td align='right'>";
    $datum = htmlspecialchars( stripslashes($row["datum"]));
    $datum2 = datumform2($datum);
    $tid = tidform($datum);
        
    echo "<font size='1'><b>Datum:</b> $datum2";
    echo "</td>";
    echo "</tr>";
    $temp = stripslashes(nl2br($row['text'])); 
    $temp = wordwrap($temp, 40, "\n", 1);
 
    echo "<tr>";
    echo "<td colspan='2'>";
    echo "<font size='1'>$temp";
    echo "</td>";
    echo "</tr>";
    $id = htmlspecialchars( stripslashes($row["id"]));

    echo "<tr>";
    echo "<td bgcolor='f1f1f1' colspan='2' align='right'>";
    echo "<font size='1'><b><a href='tabort_nyhet.php?id=$id'>Radera";
    echo "</td>";
    echo "</tr>";
    
    echo "<tr>";
    echo "<td>";
    echo "<font size='1'><br>";
    echo "</td>";
    echo "</tr>";    

}

echo "</table>";

if (!$num_results)
{
echo "Inga gamla Nyheter hittades.";
}

?>
</body>
</html>