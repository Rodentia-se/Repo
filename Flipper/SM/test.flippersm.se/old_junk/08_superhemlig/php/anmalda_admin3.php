<html>
<head>
<title></title>
<script Language="JavaScript"> 
<!-- 
function popup(url) 
{ 
settings="toolbar=no,location=no,directories=no,"+ 
"status=no,menubar=no,scrollbars=no,"+ 
"resizable=no,width=300,height=500,top=150,left=150";
window.open(url,"popuppop",settings); 
}

function popup2(url) 
{ 
settings="toolbar=no,location=no,directories=no,"+ 
"status=no,menubar=no,scrollbars=no,"+ 
"resizable=no,width=300,height=600,top=150,left=150";
window.open(url,"popuppop",settings); 
}  

//--> 
</script> 
</head>
<body
bgcolor="ffffff"
link="000000"
vlink="000000">

<font face="verdana">
<font color="000000">
<font size="1">

<font size="2">
<b>Anm�lda spelare till Flipper-SM 2004</b>
<br>
<br>
L�ngst ner p� sidan finns alla mailadresser uppradade p� ett lite l�mpligare s�tt...
<br>
<br>
<font size="1">
Dessa Spelare �r anm�lda till Flipper-SM 2004.<br><br> Du kan �ndra spelarens "betalatstatus" i checkboxen i "Betalat" kolumnen: bocka f�r de som du vill markera som "betalat", klicka sedan p� "Uppdatera" l�ngst ner p� sidan.<br><br>Om spelaren l�mnat ett meddelande kan du l�sa detta om du klickar p� "Ja" i "Meddelande" kolumnen.
<br><br>
<?
require("dbas/dbas.php");
$sortera = $_GET['sortera'];
$nysort = $_GET['nysort'];

$query = "select *
	 from sm_anmalningar
 	 order by datum desc
	 ";
	 
$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error()); 
$antalspelare = mysql_num_rows($result);

echo "<font size='1'><b>Antal anm�lda spelare:</b> $antalspelare<br>";


$query = "select *
	 from sm_anmalningar
	 where fefter = 'on'
	 ";
	 
$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error()); 
$num_results = mysql_num_rows($result);

echo "<font size='1'><br>Antal spelare som kan kvala...";

echo "<table>";
echo "<tr>";
echo "<td>";
echo "<font size='1'><b>Fredag Eftermiddag:</b>";
echo "</td>";
echo "<td>";
$procent = round($num_results / $antalspelare * 100);
echo "<font size='1'>$num_results ($procent%)";
echo "</td>";
echo "</tr>";

$query = "select *
	 from sm_anmalningar
	 where fkvall = 'on'
	 ";
	 
$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error()); 
$num_results = mysql_num_rows($result);

echo "<tr>";
echo "<td>";
echo "<font size='1'><b>Fredag Kv�ll:</b>";
echo "</td>";
echo "<td>";
$procent = round($num_results / $antalspelare * 100);
echo "<font size='1'>$num_results ($procent%)";
echo "</td>";
echo "</tr>";

$query = "select *
	 from sm_anmalningar
	 where lform = 'on'
	 ";
	 
$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error()); 
$num_results = mysql_num_rows($result);

echo "<tr>";
echo "<td>";
echo "<font size='1'><b>L�rdag F�rmiddag:</b>";
echo "</td>";
echo "<td>";
$procent = round($num_results / $antalspelare * 100);
echo "<font size='1'>$num_results ($procent%)";
echo "</td>";
echo "</tr>";

$query = "select *
	 from sm_anmalningar
	 where lefter = 'on'
	 ";
	 
$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error()); 
$num_results = mysql_num_rows($result);

echo "<tr>";
echo "<td>";
echo "<font size='1'><b>L�rdag Eftermiddag:</b>";
echo "</td>";
echo "<td>";
$procent = round($num_results / $antalspelare * 100);
echo "<font size='1'>$num_results ($procent%)";
echo "</td>";
echo "</tr>";

$query = "select *
	 from sm_anmalningar
	 where lkvall = 'on'
	 ";
	 
$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error()); 
$num_results = mysql_num_rows($result);

echo "<tr>";
echo "<td>";
echo "<font size='1'><b>L�rdag Kv�ll:</b>";
echo "</td>";
echo "<td>";
$procent = round($num_results / $antalspelare * 100);
echo "<font size='1'>$num_results ($procent%)";
echo "</td>";
echo "</tr>";

$query = "select *
	 from sm_anmalningar
	 where fefter != 'on'
	 and fkvall != 'on'
	 and lform != 'on'
	 and lefter != 'on'
	 and lkvall != 'on'
	 ";
	 
$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error()); 
$num_results = mysql_num_rows($result);

echo "<tr>";
echo "<td>";
echo "<font size='1'><b>Inget val:</b>";
echo "</td>";
echo "<td>";
$procent = round($num_results / $antalspelare * 100);
echo "<font size='1'>$num_results ($procent%)";
echo "</td>";
echo "</tr>";



echo "</table>";

$datum = date("y.m.d");                         

echo "<font size='1'><br>Du kan sortera p� de olika rubrikerna (Tag, Namn, Hemort etc) klicka p� dem f�r att sortera Spelarna<br>";

echo "<br>";

require("dbas/dbas.php");
require("datumform.php");

if ($nysort != 'yadayadaa')
{

$query = "select *
	 from sm_anmalningar
 	 order by datum desc
	 ";
  		
echo "<table style='border-collapse: collapse;'>";
echo "<tr>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=tag&nysort=yadayadaa'><font size='1'><b>Tag:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=namn&nysort=yadayadaa'><font size='1'><b>Namn:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=hemort&nysort=yadayadaa'><font size='1'><b>Hemort:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=sedan&nysort=yadayadaa'><font size='1'><b>Anm�ld:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=betalat&nysort=yadayadaa'><font size='1'><b>Betalat:</b></td>";
echo "<td class='egen2'><font size='1'><b>Kan Kvala -></b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frem&nysort=yadayadaa'><font size='1'><b>fr em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frekv&nysort=yadayadaa'><font size='1'><b>fr kv:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lofm&nysort=yadayadaa'><font size='1'><b>l� fm:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=loem&nysort=yadayadaa'><font size='1'><b>l� em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lokv&nysort=yadayadaa'><font size='1'><b>l� kv:</b></td>";
echo "<td class='egen2'><font size='1'><b>Meddelande:</b></td>";
echo "<td class='egen2'><font size='1'><b>Epost:</b></td>";
echo "<td class='egen2'><font size='1'><b>Telefon:</b></td>";
echo "</tr>";
}
else
{

if ($sortera=='tag' & $nysort=='yadayadaa')
{	
	
	$query = "select *
	 from sm_anmalningar
 	 order by tag asc
 	 ";
 	 
echo "<table style='border-collapse: collapse;'>";

echo "<tr>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=tag&nysort=yadayadaa'><font size='1'><b>Tag:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=namn&nysort=yadayadaa'><font size='1'><b>Namn:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=hemort&nysort=yadayadaa'><font size='1'><b>Hemort:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=sedan&nysort=yadayadaa'><font size='1'><b>Anm�ld:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=betalat&nysort=yadayadaa'><font size='1'><b>Betalat:</b></td>";
echo "<td class='egen2'><font size='1'><b>Kan Kvala -></b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frem&nysort=yadayadaa'><font size='1'><b>fr em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frekv&nysort=yadayadaa'><font size='1'><b>fr kv:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lofm&nysort=yadayadaa'><font size='1'><b>l� fm:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=loem&nysort=yadayadaa'><font size='1'><b>l� em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lokv&nysort=yadayadaa'><font size='1'><b>l� kv:</b></td>";
echo "<td class='egen2'><font size='1'><b>Meddelande:</b></td>";
echo "<td class='egen2'><font size='1'><b>Epost:</b></td>";
echo "<td class='egen2'><font size='1'><b>Telefon:</b></td>";
echo "</tr>";
}

if ($sortera=='namn' & $nysort=='yadayadaa')
{
	 $query = "select *
	 from sm_anmalningar
 	 order by namn asc
 	 ";
		
echo "<table style='border-collapse: collapse;'>";

echo "<tr>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=tag&nysort=yadayadaa'><font size='1'><b>Tag:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=namn&nysort=yadayadaa'><font size='1'><b>Namn:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=hemort&nysort=yadayadaa'><font size='1'><b>Hemort:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=sedan&nysort=yadayadaa'><font size='1'><b>Anm�ld:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=betalat&nysort=yadayadaa'><font size='1'><b>Betalat:</b></td>";
echo "<td class='egen2'><font size='1'><b>Kan Kvala -></b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frem&nysort=yadayadaa'><font size='1'><b>fr em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frekv&nysort=yadayadaa'><font size='1'><b>fr kv:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lofm&nysort=yadayadaa'><font size='1'><b>l� fm:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=loem&nysort=yadayadaa'><font size='1'><b>l� em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lokv&nysort=yadayadaa'><font size='1'><b>l� kv:</b></td>";
echo "<td class='egen2'><font size='1'><b>Meddelande:</b></td>";
echo "<td class='egen2'><font size='1'><b>Epost:</b></td>";
echo "<td class='egen2'><font size='1'><b>Telefon:</b></td>";
echo "</tr>";
}

if ($sortera=='hemort' & $nysort=='yadayadaa')
{	
	 $query = "select *
	 from sm_anmalningar
 	 order by stad asc
 	 ";
	
echo "<table style='border-collapse: collapse;'>";

echo "<tr>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=tag&nysort=yadayadaa'><font size='1'><b>Tag:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=namn&nysort=yadayadaa'><font size='1'><b>Namn:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=hemort&nysort=yadayadaa'><font size='1'><b>Hemort:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=sedan&nysort=yadayadaa'><font size='1'><b>Anm�ld:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=betalat&nysort=yadayadaa'><font size='1'><b>Betalat:</b></td>";
echo "<td class='egen2'><font size='1'><b>Kan Kvala -></b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frem&nysort=yadayadaa'><font size='1'><b>fr em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frekv&nysort=yadayadaa'><font size='1'><b>fr kv:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lofm&nysort=yadayadaa'><font size='1'><b>l� fm:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=loem&nysort=yadayadaa'><font size='1'><b>l� em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lokv&nysort=yadayadaa'><font size='1'><b>l� kv:</b></td>";
echo "<td class='egen2'><font size='1'><b>Meddelande:</b></td>";
echo "<td class='egen2'><font size='1'><b>Epost:</b></td>";
echo "<td class='egen2'><font size='1'><b>Telefon:</b></td>";
echo "</tr>";
}

if ($sortera=='sedan' & $nysort=='yadayadaa')
{	
	
	 $query = "select *
	 from sm_anmalningar
 	 order by datum desc
 	 ";
	
echo "<table style='border-collapse: collapse;'>";

echo "<tr>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=tag&nysort=yadayadaa'><font size='1'><b>Tag:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=namn&nysort=yadayadaa'><font size='1'><b>Namn:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=hemort&nysort=yadayadaa'><font size='1'><b>Hemort:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=sedan&nysort=yadayadaa'><font size='1'><b>Anm�ld:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=betalat&nysort=yadayadaa'><font size='1'><b>Betalat:</b></td>";
echo "<td class='egen2'><font size='1'><b>Kan Kvala -></b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frem&nysort=yadayadaa'><font size='1'><b>fr em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frekv&nysort=yadayadaa'><font size='1'><b>fr kv:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lofm&nysort=yadayadaa'><font size='1'><b>l� fm:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=loem&nysort=yadayadaa'><font size='1'><b>l� em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lokv&nysort=yadayadaa'><font size='1'><b>l� kv:</b></td>";
echo "<td class='egen2'><font size='1'><b>Meddelande:</b></td>";
echo "<td class='egen2'><font size='1'><b>Epost:</b></td>";
echo "<td class='egen2'><font size='1'><b>Telefon:</b></td>";
echo "</tr>";	
}

if ($sortera=='betalat' & $nysort=='yadayadaa')
{	
	
	 $query = "select *
	 from sm_anmalningar
 	 order by betalat desc
 	 ";
	
echo "<table style='border-collapse: collapse;'>";

echo "<tr>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=tag&nysort=yadayadaa'><font size='1'><b>Tag:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=namn&nysort=yadayadaa'><font size='1'><b>Namn:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=hemort&nysort=yadayadaa'><font size='1'><b>Hemort:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=sedan&nysort=yadayadaa'><font size='1'><b>Anm�ld:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=betalat&nysort=yadayadaa'><font size='1'><b>Betalat:</b></td>";
echo "<td class='egen2'><font size='1'><b>Kan Kvala -></b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frem&nysort=yadayadaa'><font size='1'><b>fr em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frekv&nysort=yadayadaa'><font size='1'><b>fr kv:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lofm&nysort=yadayadaa'><font size='1'><b>l� fm:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=loem&nysort=yadayadaa'><font size='1'><b>l� em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lokv&nysort=yadayadaa'><font size='1'><b>l� kv:</b></td>";
echo "<td class='egen2'><font size='1'><b>Meddelande:</b></td>";
echo "<td class='egen2'><font size='1'><b>Epost:</b></td>";
echo "<td class='egen2'><font size='1'><b>Telefon:</b></td>";
echo "</tr>";	
}

if ($sortera=='frem' & $nysort=='yadayadaa')
{	
	
	 $query = "select *
	 from sm_anmalningar
 	 order by fefter desc
 	 ";
	
echo "<table style='border-collapse: collapse;'>";

echo "<tr>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=tag&nysort=yadayadaa'><font size='1'><b>Tag:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=namn&nysort=yadayadaa'><font size='1'><b>Namn:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=hemort&nysort=yadayadaa'><font size='1'><b>Hemort:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=sedan&nysort=yadayadaa'><font size='1'><b>Anm�ld:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=betalat&nysort=yadayadaa'><font size='1'><b>Betalat:</b></td>";
echo "<td class='egen2'><font size='1'><b>Kan Kvala -></b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frem&nysort=yadayadaa'><font size='1'><b>fr em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frekv&nysort=yadayadaa'><font size='1'><b>fr kv:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lofm&nysort=yadayadaa'><font size='1'><b>l� fm:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=loem&nysort=yadayadaa'><font size='1'><b>l� em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lokv&nysort=yadayadaa'><font size='1'><b>l� kv:</b></td>";
echo "<td class='egen2'><font size='1'><b>Meddelande:</b></td>";
echo "<td class='egen2'><font size='1'><b>Epost:</b></td>";
echo "<td class='egen2'><font size='1'><b>Telefon:</b></td>";
echo "</tr>";	
}

if ($sortera=='frekv' & $nysort=='yadayadaa')
{	
	
	 $query = "select *
	 from sm_anmalningar
 	 order by fkvall desc
 	 ";
	
echo "<table style='border-collapse: collapse;'>";

echo "<tr>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=tag&nysort=yadayadaa'><font size='1'><b>Tag:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=namn&nysort=yadayadaa'><font size='1'><b>Namn:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=hemort&nysort=yadayadaa'><font size='1'><b>Hemort:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=sedan&nysort=yadayadaa'><font size='1'><b>Anm�ld:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=betalat&nysort=yadayadaa'><font size='1'><b>Betalat:</b></td>";
echo "<td class='egen2'><font size='1'><b>Kan Kvala -></b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frem&nysort=yadayadaa'><font size='1'><b>fr em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frekv&nysort=yadayadaa'><font size='1'><b>fr kv:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lofm&nysort=yadayadaa'><font size='1'><b>l� fm:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=loem&nysort=yadayadaa'><font size='1'><b>l� em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lokv&nysort=yadayadaa'><font size='1'><b>l� kv:</b></td>";
echo "<td class='egen2'><font size='1'><b>Meddelande:</b></td>";
echo "<td class='egen2'><font size='1'><b>Epost:</b></td>";
echo "<td class='egen2'><font size='1'><b>Telefon:</b></td>";
echo "</tr>";	
}

if ($sortera=='lofm' & $nysort=='yadayadaa')
{	
	
	 $query = "select *
	 from sm_anmalningar
 	 order by lform desc
 	 ";
	
echo "<table style='border-collapse: collapse;'>";

echo "<tr>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=tag&nysort=yadayadaa'><font size='1'><b>Tag:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=namn&nysort=yadayadaa'><font size='1'><b>Namn:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=hemort&nysort=yadayadaa'><font size='1'><b>Hemort:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=sedan&nysort=yadayadaa'><font size='1'><b>Anm�ld:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=betalat&nysort=yadayadaa'><font size='1'><b>Betalat:</b></td>";
echo "<td class='egen2'><font size='1'><b>Kan Kvala -></b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frem&nysort=yadayadaa'><font size='1'><b>fr em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frekv&nysort=yadayadaa'><font size='1'><b>fr kv:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lofm&nysort=yadayadaa'><font size='1'><b>l� fm:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=loem&nysort=yadayadaa'><font size='1'><b>l� em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lokv&nysort=yadayadaa'><font size='1'><b>l� kv:</b></td>";
echo "<td class='egen2'><font size='1'><b>Meddelande:</b></td>";
echo "<td class='egen2'><font size='1'><b>Epost:</b></td>";
echo "<td class='egen2'><font size='1'><b>Telefon:</b></td>";
echo "</tr>";	
}

if ($sortera=='loem' & $nysort=='yadayadaa')
{	
	
	 $query = "select *
	 from sm_anmalningar
 	 order by lefter desc
 	 ";
	
echo "<table style='border-collapse: collapse;'>";

echo "<tr>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=tag&nysort=yadayadaa'><font size='1'><b>Tag:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=namn&nysort=yadayadaa'><font size='1'><b>Namn:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=hemort&nysort=yadayadaa'><font size='1'><b>Hemort:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=sedan&nysort=yadayadaa'><font size='1'><b>Anm�ld:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=betalat&nysort=yadayadaa'><font size='1'><b>Betalat:</b></td>";
echo "<td class='egen2'><font size='1'><b>Kan Kvala -></b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frem&nysort=yadayadaa'><font size='1'><b>fr em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frekv&nysort=yadayadaa'><font size='1'><b>fr kv:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lofm&nysort=yadayadaa'><font size='1'><b>l� fm:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=loem&nysort=yadayadaa'><font size='1'><b>l� em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lokv&nysort=yadayadaa'><font size='1'><b>l� kv:</b></td>";
echo "<td class='egen2'><font size='1'><b>Meddelande:</b></td>";
echo "<td class='egen2'><font size='1'><b>Epost:</b></td>";
echo "<td class='egen2'><font size='1'><b>Telefon:</b></td>";
echo "</tr>";	
}

if ($sortera=='lokv' & $nysort=='yadayadaa')
{	
	
	 $query = "select *
	 from sm_anmalningar
 	 order by lkvall desc
 	 ";
	
echo "<table style='border-collapse: collapse;'>";

echo "<tr>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=tag&nysort=yadayadaa'><font size='1'><b>Tag:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=namn&nysort=yadayadaa'><font size='1'><b>Namn:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=hemort&nysort=yadayadaa'><font size='1'><b>Hemort:</b></td>";
echo "<td class='egen'><a href='anmalda_admin.php?sortera=sedan&nysort=yadayadaa'><font size='1'><b>Anm�ld:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=betalat&nysort=yadayadaa'><font size='1'><b>Betalat:</b></td>";
echo "<td class='egen2'><font size='1'><b>Kan Kvala -></b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frem&nysort=yadayadaa'><font size='1'><b>fr em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=frekv&nysort=yadayadaa'><font size='1'><b>fr kv:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lofm&nysort=yadayadaa'><font size='1'><b>l� fm:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=loem&nysort=yadayadaa'><font size='1'><b>l� em:</b></td>";
echo "<td class='egen2'><a href='anmalda_admin.php?sortera=lokv&nysort=yadayadaa'><font size='1'><b>l� kv:</b></td>";
echo "<td class='egen2'><font size='1'><b>Meddelande:</b></td>";
echo "<td class='egen2'><font size='1'><b>Epost:</b></td>";
echo "<td class='egen2'><font size='1'><b>Telefon:</b></td>";
echo "</tr>";	
}

}
$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error()); 
$num_results = mysql_num_rows($result);

echo "<form action=\"upp_betalning.php\" method=\"post\">";

for ($i=0; $i < $num_results; $i++)
{
    $row = mysql_fetch_array($result);
	
    if($i % 2)
    {
    echo "<tr>";
    }
    else
    {
    echo "<tr bgcolor='f1f1f1'>";     
    }
    
    echo "<td>";
    $tag = htmlspecialchars( stripslashes($row["tag"]));
    echo "<font size='1'>$tag";
    echo "</td>";

    echo "<td>";
    $namn = htmlspecialchars( stripslashes($row["namn"]));
    echo "<font size='1'>$namn";
    echo "</td>";
    
    echo "<td>";
    $temp = htmlspecialchars( stripslashes($row["stad"]));
    echo "<font size='1'>$temp";
    echo "</td>";

    echo "<td>";
    $temp = htmlspecialchars( stripslashes($row["datum"]));
    $tempdatum = datumform($temp);
    echo "<font size='1'>$tempdatum";
    echo "</td>";

    $betalat = htmlspecialchars( stripslashes($row["betalat"]));
    $id = htmlspecialchars( stripslashes($row["id"]));
    
    echo "<td align='center'>";
    if($betalat == 'ja')
    {
    echo "<input name='$id' type='checkbox' checked='true'>"; 
    }
    else
    {
    echo "<input name='$id' type='checkbox'>"; 
    }    
    echo "</td>";

    echo "<td>";
    echo "</td>";



    $fefter = htmlspecialchars( stripslashes($row["fefter"]));   
    $fkvall = htmlspecialchars( stripslashes($row["fkvall"]));
    $lform = htmlspecialchars( stripslashes($row["lform"]));
    $lefter = htmlspecialchars( stripslashes($row["lefter"]));
    $lkvall = htmlspecialchars( stripslashes($row["lkvall"]));

    if($fefter != null or $fkvall != null or $lform != null or $lefter != null or $lkvall != null)
    {
    echo "<td align='center'>";
    if($fefter != null)
    {
    echo "<font size='1'>X";
    }
    echo "</td>";

    echo "<td align='center'>";
    if($fkvall != null)
    {
    echo "<font size='1'>X";
    }
    echo "</td>";

    echo "<td align='center'>";
    if($lform != null)
    {
    echo "<font size='1'>X";
    }
    echo "</td>";

    echo "<td align='center'>";
    if($lefter != null)
    {
    echo "<font size='1'>X";
    }
    echo "</td>";

    echo "<td align='center'>";
    if($lkvall != null)
    {
    echo "<font size='1'>X";
    }
    echo "</td>";
    }
    else
    {
    echo "<td align='center' colspan='5'>";
    echo "<font size='1'><i>inget val</i>";
    echo "</td>";
    }        	
    $meddelande = htmlspecialchars( stripslashes($row["meddelande"]));

    if($meddelande != null)
    {
    echo "<td align='center'><font size='1'><b>";
    echo '<p><a href="#" onclick="popup2(\'meddelande.php?id='.$id.'\'); return false">Ja</b>';
    echo "</td>";
    }
    else
    {
    echo "<td>";
    echo "</td>";
    }

    $epost = htmlspecialchars( stripslashes($row["epost"]));
    echo "<td align='left'><font size='1'><b>";
    echo "<a href='mailto:$epost'>$epost</a>";
    echo "</td>";

    $telefon = htmlspecialchars( stripslashes($row["telefon"]));
    echo "<td align='left'><font size='1'>";
    echo "$telefon</a>";
    echo "</td>";
       
    echo "</tr>";
    
}

echo "<tr>";
echo "<td colspan='12' align='right'>";
echo "<input type=submit value=\"Uppdatera\"";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td colspan='2' align='right'>";
echo "<input type='hidden' name='sortera' value='$sortera' maxlength=30 size=30>";
echo "</td>";
echo "<td colspan='2' align='right'>";
echo "<input type='hidden' name='nysort' value='$nysort' maxlength=30 size=30>";
echo "</td>";
echo "</tr>";

echo "</table>";
echo "</form>";

if (!$num_results)
{
echo "Inga registrerade resultat hittade.";
}

echo "</table>";

$query = "select *
from sm_anmalningar
order by epost asc";
$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error()); 
$num_results = mysql_num_rows($result);

echo "<form action=\"upp_betalning.php\" method=\"post\">";

for ($i=0; $i < $num_results; $i++)
{
$row = mysql_fetch_array($result);
$epost = htmlspecialchars( stripslashes($row["epost"]));
	if($epost != null)
	{
	echo "$epost, ";
	}
}	 
?>
</body>
</html>                                                                      