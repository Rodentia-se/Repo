<?php
session_start();
$ip = $REMOTE_ADDR;
$tag = trim($_POST['tag']);
$namn = trim($_POST['namn']);;
$adress = trim($_POST['adress']);
$postnummer = trim($_POST['postnummer']);
$stad = trim($_POST['stad']);
$telefon = trim($_POST['telefon']);
$epost = trim($_POST['epost']);
$meddelande = trim($_POST['meddelande']);
$fefter = trim($_POST['fefter']);
$fkvall = trim($_POST['fkvall']);
$lform = trim($_POST['lform']);
$lefter = trim($_POST['lefter']);
$lkvall = trim($_POST['lkvall']);
$bestall = $_POST['txtOrder'];

$tag = strtoupper($tag);
$meltest1 = substr($id,0,1);
$meltest2 = substr($id,1,1);
$meltest3 = substr($id,2,1);
$skrapskydd = trim($_POST['skrapskydd']);

$totalpris = $_POST['txtPris'];


if (!$tag || !$namn || !$adress || !$postnummer || !$stad || !$telefon)
{
header("Location: index.php?sida=anmal&tag=$tag&namn=$namn&epost=$epost&telefon=$telefon&stad=$stad&postnummer=$postnummer&meddelande=$meddelande&adress=$adress&fefter=$fefter&fkvall=$fkvall&lform=$lform&lefter=$lefter&lkvall=$lkvall&txtPris=$totalpris&txtOrder=$bestall");
}
elseif($skrapskydd != $_SESSION[flippertal])
{
header("Location: index.php?sida=anmal&tag=$tag&namn=$namn&epost=$epost&telefon=$telefon&stad=$stad&postnummer=$postnummer&meddelande=$meddelande&adress=$adress&fefter=$fefter&fkvall=$fkvall&lform=$lform&lefter=$lefter&lkvall=$lkvall&txtPris=$totalpris&txtOrder=$bestall&robot=ja");
}
else
{
require("dbas/dbas.php");

$sql = "SELECT COUNT(*) FROM spelare WHERE id='$id'"; 
$result = mysql_query($sql); 

$datum = date("YmdHis");  

$query = "insert into sm_anmalningar (tag, namn, adress, postnummer, stad, telefon, epost, meddelande, fefter, fkvall, lform, lefter, lkvall, datum, ip) 
values ('$tag' ,'$namn' , '$adress', '$postnummer', '$stad', '$telefon', '$epost', '$meddelande', '$fefter', '$fkvall', '$lform', '$lefter', '$lkvall', '$datum', '$ip')"; 
   
$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error());



$query = "select id
from sm_anmalningar
order by datum desc";

$result = mysql_query($query) or die("<p>SQL: $query <br>".mysql_error()); 
$row = mysql_fetch_array($result);
$id = htmlspecialchars( stripslashes($row["id"]));

$_SESSION['id'] = $id;
$_SESSION['pris'] = $totalpris;


if(isset($_POST['txtOrder']))
  {
  $bestallning = $_POST['txtOrder'];

  $_SESSION['trojor'] = $bestallning;

  $trojquery = "insert into sm_trojor (id, tag, namn, bestallning, pris) 
values ('$id' ,'$tag' , '$namn', '$bestallning', '$totalpris')";
  
  $trojresult = mysql_query($trojquery) or die("<p>SQL: $trojquery <br>".mysql_error());
  
  
  $sendto = "christian.balac@gmail.com";
  $subject = "Tr�jbest�llning fr�n " . "$namn";
  $doman = "flippersm.se";
  $header = "From: " . "$doman" . "<noreply@" . "$doman" . ">" . "\n"
. "Return-Path: " . "webb@flippersm.se" . "\n";

  $med = "F�ljande best�llning har gjorts av $namn:
$bestallning
  
Kontaktinformation:
Adress: $namn, $adress, $postnummer $stad.
Tel: $telefon
E-post: $epost";
  
  mail($sendto, $subject, $med, $header);

  }

//send mail
$sendto = $epost;
$subject = "Anm�lan till Flipper-SM";
$doman = "flippersm.se";
$header = "From: " . "$doman" . "<noreply@" . "$doman" . ">" . " \n"
. "Return-Path: " . "webb@flippersm.se" . "\n";

$med = "Du �r nu anm�ld till Flipper SM 2006 den 10-12 november!

Du har angivit f�ljande i din best�llning:
Tag: $tag
Namn: $namn
Adress: $adress, $postnummer
Telefon: $telefon
Kan kvala: ";

	if($fefter == true)
	$med = "$med" . "Fredag eftermiddag ";

	if($fkvall == true)
	$med = "$med" . "Fredag kv�ll ";

	if($lform == true)
	$med = "$med" . "L�rdag f�rmiddag ";

	if($lefter == true)
	$med = "$med" . "L�rdag eftermiddag ";

	if($lkvall == true)
	$med = "$med" . "L�rdag kv�ll ";	

    if($bestall == true)
    $med = "$med" . "\n\nDu har ocks� gjort f�ljande tr�jbest�llning:\n$bestall";

$med = "$med" . "\n\nMer information om t�vlingstider och regler kommer att uppdateras p� www.flippersm.se.\n\nMed v�nliga h�lsningar\nFlipper-SM";

mail($sendto, $subject, $med, $header);



header("Location: index.php?sida=anmal3");
}
?>
