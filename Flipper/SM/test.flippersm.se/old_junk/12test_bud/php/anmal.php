<html>

<head>
<style>
<!--A:link, A:visited { text-decoration: none;}A:hover {  text-decoration: underline;color:"000033"}--></style>
</head>

<body
bgcolor="ffffff"
link="000000"
vlink="000000">

<font face="verdana">
<font color="000000">
<font size="1">

<font size="2">
<b>Anm�l dig till Flipper-SM 2004</b>
<font size="1">
<br>
<br>
Sista chansen!!!!!!!! I skrivande stund finns det fortfarande n�gra platser kvar i Flipper-SM 2004 d� det inte kommit in 200 betalda anm�lningsavgifter �nnu.
<br>
<br>
Ring Helena p� 0702-885111 f�r information om hur du snor �t dig en av de sista platserna.
<?
/*
<i><b>OBS: </b>Det g�r inte att anm�la sig till Flipper SM 2004 l�ngre. Den sista anm�lningsdagen var 31/10</i>
<br>
<br>
Om du har problem med anm�lningsformul�ret, skicka ett mail till: <a href='mailto:stefan@flippersm.se'><b>stefan@flippersm.se</b></a>.
<br>
<br>
<b>F�ranm�lan</b>
<br>
<br>
F�r att kunna delta i Flipper-SM 2004 m�ste du anm�la dig i f�rv�g. Du f�ranm�ler dig f�rst h�r p� webben f�r att sedan betala in din anm�lningsavgift (se nedan f�r info). Din anm�lan �r inte bindande f�rr�n du betalt in din anm�lningsavgift.
<br><br>
Eftersom Flipper-SM i dess nuvarande form och organisation har ett n�got begr�nsat antal deltagare vi kan ta emot �r detta 
antal satt till max 200 deltagare �r 2004. De 200 f�rst <i>betalande</i> deltagarna �r de som garanteras en plats i Flipper-SM 2004.
<br><br>
Vi kommer f�rmodligen inte kunna ta emot n�gra anm�lningar p� plats i och med att vi r�knar med att dessa 200 platser kommer att
 fyllas upp innan t�vlingen.
<br>
<br>
Sista anm�lningsdag �r 31/10.
<br>
<br>
<b>Anm�lningsavgiften</b>
<br>
<b>OBS:</b> Sista betalningsdagen �r <b>31/10</b>
<br>
<br>
Anm�lningsavgiften betalas in p�:
<br>
<br>
Postgiro: 23 84 76 -6. Mottagare �r Flipper-SM, c/o Helena Walter, Albiongatan 9 A, 802 55 G�vle
<br>
<br>
Spar en kopia p� din inbetalning. Om du v�ljer att betala via postkontor �r det viktigt att ni anger vilken person betalningen avser.
<br>
<br>
Om du betalar din anm�lningsavgift innan den 31/7 �r avgiften 100:-
<br>
Betalar du mellan <b>1/8</b> och <b>31/10</b> �r avgiften <b>150:-</b>.
<br>
<br>
N�r vi f�tt in din anm�lningsavgift kommer detta att markeras vid ditt namn bland <a href='anmalda.php'><b>Anm�lda Spelare</b></a> h�r p� hemsidan.
<br>
<br>
<b>OBS:</b> Det kan ta upp till en vecka f�r oss att hinna registrera din betalning.
<?/*
$tag = trim($_GET['tag']);
$namn = trim($_GET['namn']);
$gata = trim($_GET['gata']);
$postnummer = trim($_GET['postnummer']);
$stad = trim($_GET['stad']);
$telefon = trim($_GET['telefon']);
$epost = trim($_GET['epost']);
$meddelande = trim($_GET['meddelande']);
$fefter = trim($_GET['fefter']);
$fkvall = trim($_GET['fkvall']);
$lform = trim($_GET['lform']);
$lefter = trim($_GET['lefter']);
$lkvall = trim($_GET['lkvall']);

echo "<hr>";
echo "<table>";
echo "<tr>";
echo "<td>";
echo "<font size='1'><b>Anm�lan - Flipper-SM 2004</b><br><br>";
echo "F�lt markerade med <b>(*)</b> �r obligatoriska";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td>";
echo "<br>";
echo "</td>";
echo "</tr>";

echo "<form action='anmal2.php' method='post'>";

echo "<tr>";
echo "<td><font size='1'><b>TAG (*):</b></td>";
echo "</tr>";

echo "<tr>";
echo "<td><font size='1'>Endast TRE tecken. Dina initialer du anv�nder n�r/om du skriver in dig p� highscorelistor.<br><br>";
echo "</tr>";

echo "<tr>";

if($tag == null)
{
echo "<td><font size='1'><input type=text name='tag' value='$tag' maxlength=3 size=3 STYLE='background-color: #FFFFD0';><br></td>";
}
else
{
echo "<td><font size='1'><input type=text name='tag' value='$tag' maxlength=3 size=3'> <b>ok</b><br></td>";
}	
echo "</tr>";

echo "<tr>";
echo "<td><font size='1'><b>Namn (*):</b> (F�r och Efternamn)</td>";
echo "</tr>";
echo "<tr>";
if($namn == null)
{
echo "<td><font size='1'><input type=text name='namn' value='$namn' maxlength=100 size=40 STYLE='background-color: #FFFFD0';><br></td>";
}
else
{
echo "<td><font size='1'><input type=text name='namn' value='$namn' maxlength=100 size=40> <b>ok</b><br></td>";
}	
echo "</tr>";

echo "<tr>";
echo "<td><font size='1'><b>Adress (*):</b></td>";
echo "</tr>";
echo "<tr>";
if($adress == null)
{
echo "<td><font size='1'><input type=text name='adress' value='$adress' maxlength=100 size=40 STYLE='background-color: #FFFFD0';><br></td>";
}
else
{
echo "<td><font size='1'><input type=text name='adress' value='$adress' maxlength=100 size=40> <b>ok</b><br></td>";
}
echo "</tr>";

echo "<tr>";
echo "<td><font size='1'><b>Postnummer (*):</b></td>";
echo "</tr>";
echo "<tr>";
if($postnummer == null)
{
echo "<td><font size='1'><input type=text name='postnummer' value='$postnummer' maxlength=100 size=40 STYLE='background-color: #FFFFD0';><br></td>";
}
else
{
echo "<td><font size='1'><input type=text name='postnummer' value='$postnummer' maxlength=100 size=40'> <b>ok</b><br></td>";
}
echo "</tr>";

echo "<tr>";
echo "<td><font size='1'><b>Stad (*):</b></td>";
echo "</tr>";
echo "<tr>";
if($stad == null)
{
echo "<td><font size='1'><input type=text name='stad' value='$stad' maxlength=100 size=40 STYLE='background-color: #FFFFD0';><br></td>";
}
else
{
echo "<td><font size='1'><input type=text name='stad' value='$stad' maxlength=100 size=40> <b>ok</b><br></td>";
}
echo "</tr>";

echo "<tr>";
echo "<td><font size='1'><b>Telefon (*):</b></td>";
echo "</tr>";
echo "<tr>";
if($telefon == null)
{
echo "<td><font size='1'><input type=text name='telefon' value='$telefon' maxlength=100 size=40 STYLE='background-color: #FFFFD0';><br></td>";
}
else
{
echo "<td><font size='1'><input type=text name='telefon' value='$telefon' maxlength=100 size=40> <b>ok</b><br></td>";
}
echo "</tr>";

echo "<tr>";
echo "<td><font size='1'><b>Epost:</b></td>";
echo "</tr>";
echo "<tr>";
echo "<td><font size='1'><input type=text name='epost' value='$epost' maxlength=100 size=40><br></td>";
echo "</tr>";

echo "<tr>";
echo "<td><font size='1'><b>Kvaltider:</b></td>";
echo "</tr>";

echo "<tr>";
echo "<td><font size='1'>H�r ser vi g�rna att du anger vilken/vilka tider du har m�jlighet att kvala. Du kommer endast kvala under ett av tillf�llena, men ange g�rna alla de tider du har m�jlighet att kvala. Kryssar du inte f�r n�got av alternativen tolkar vi det som att du kan spela under vilken som helst av tiderna.<br \><br \><b>OBS:</b> vi garanterar inte att du f�r spela p� n�gon av tiderna du anger, men vi kommer att i mesta m�jliga m�n placera deltagarna p� de �nskade kvaltiderna.</td>";
echo "</tr>";

if($fefter == false)
{
echo "<tr><td><p><input type=checkbox name='fefter'><font size='1'>Fredag eftermiddag</p></td></tr>";
}
else
{
echo "<tr><td><p><input type=checkbox name='fefter' checked='true'><font size='1'>Fredag eftermiddag</p></td></tr>";
}

if($fkvall == false)	
{
echo "<tr><td><p><input type=checkbox name='fkvall'><font size='1'>Fredag kv�ll</p></td></tr>";
}
else
{
echo "<tr><td><p><input type=checkbox name='fkvall' checked='true'><font size='1'>Fredag kv�ll</p></td></tr>";
}

if($lform == false)
{
echo "<tr><td><p><input type=checkbox name='lform'><font size='1'>L�rdag f�rmiddag</p></td></tr>";
}
else
{
echo "<tr><td><p><input type=checkbox name='lform' checked='true'><font size='1'>L�rdag f�rmiddag</p></td></tr>";
}

if($lefter == false)
{
echo "<tr><td><p><input type=checkbox name='lefter'><font size='1'>L�rdag eftermiddag</p></td></tr>";
}
else
{
echo "<tr><td><p><input type=checkbox name='lefter' checked='true'><font size='1'>L�rdag eftermiddag</p></td></tr>";
}

if($lkvall == false)
{
echo "<tr><td><p><input type=checkbox name='lkvall'><font size='1'>L�rdag kv�ll</p></td></tr>";
}
else
{
echo "<tr><td><p><input type=checkbox name='lkvall' checked='true'><font size='1'>L�rdag kv�ll</p></td></tr>";
}

echo "<tr>";
echo "<td><font size='1'><b>Meddelande:</b></td>";
echo "</tr>";

echo "<tr>";
echo "<td><font size='1'>Om du vill l�mna n�got s�rskilt meddelande till arrang�rerna i samband med din anm�lan kan du skriva det h�r.<br><br>";
echo "</tr>";

echo "<tr>";
echo "<td>";
echo "<textarea name=meddelande wrap=physical cols=40 rows=6>$meddelande</textarea>";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td colspan=2><input type=submit class='egen' value='Anm�l dig'></td>";
echo "</tr>";
echo "</table>";
echo "</form>";*/
?>
