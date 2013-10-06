<?php
  define('__ROOT__', dirname(__FILE__));
  require_once(__ROOT__.'/functions/general.php');

$loggedIn = checkLogin($dbh, $ulogin, false);

$s = isset($_GET['s']) ? $_GET['s'] : 'start';

function undermenu($dbh, $ulogin, $page, $m)
  {

  $player = getCurrentPlayer($dbh, $ulogin);
  $team = ($player) ? $player->getTeam($dbh) : false;

  $registrationLink = ($player->mainPlayerId) ? '<a href="?s=object&obj=player&id=self">Dina sidor</a>' : '<a href="?s=anmal">Anmälan</a>';

  switch ($page) {
    case 'anmal':
    case 'object':
    case 'edit':
    case 'editdubbel':
    case 'tshirt':
    case 'kvalval':
    case 'funktionar':
    case 'instruktioner':
    case 'betala':
    case 'funktionarsval':
      echo $registrationLink." <a href=\"?s=object&obj=player&m=anmal\">Anmälda spelare</a> <a href=\"?s=funktionar\">Funktionärer</a> <a href=\"?s=object&obj=game&m=anmal\">Spel</a>";
    break;
    case 'om':
    case 'regler':
    case 'schema':
    case 'press':
    case 'social':
    case 'video':
    case 'kontakt':
      echo "<a href=\"?s=om\">Om Flipper-SM</a> <a href=\"?s=2012&m=om\">Tidigare SM</a> <a href=\"?s=regler\">Regler</a> <a href=\"?s=press\">Press</a> <a href=\"?s=video\">Video</a> <a href=\"?s=kontakt\">Kontakt</a>";
    break;
    case '2012':
    case '2011':
    case '2010':
    case '2009':
    case '2008':
    case '2007':
    case '2006':
    case '2005':
    case '2004':
    case '2003':
    case '90tal':
      if ($m == 'om') {
       echo "<a href=\"?s=om\">Om Flipper-SM</a> <a href=\"?s=2012&m=om\">Tidigare SM</a> <a href=\"?s=regler\">Regler</a> <a href=\"?s=press\">Press</a> <a href=\"?s=video\">Video</a> <a href=\"?s=kontakt\">Kontakt</a>";
      } else {
        echo "<a href=\"?s=kvalresultat\">Kvalresultat</a> <a href=\"?s=slutspel&type=main\">Slutspel</a> <a href=\"?s=sidoresultat\">Sidotävlingar</a> <a href=\"?s=2012&m=resultat\">Tidigare SM</a>";
      }
    break;
    case 'kvalresultat':
    case 'slutspel':
    case 'sidoresultat':
      echo "<a href=\"?s=kvalresultat\">Kvalresultat</a> <a href=\"?s=slutspel&type=main\">Slutspel</a> <a href=\"?s=sidoresultat\">Sidotävlingar</a> <a href=\"?s=2012&m=resultat\">Tidigare SM</a>";
    break;
  }
}


function submenu2($dbh, $ulogin, $category, $echo = true, $obj = null)
  {

  $m = isset($_GET['m']) ? '&m='.$_GET['m'] : null;

  $player = getCurrentPlayer($dbh, $ulogin);
  $team = ($player) ? $player->getTeam($dbh) : false;

  $content = "<p class=\"submenu2\">";

  if($category == "anmalda")
    {
      if (($player && $obj->class == 'player' && $obj->id == $player->id) || ($team && $obj->class == 'team' && $obj->id = $team->id)) {
        $content .= "<a href=\"?s=object&obj=player&id=self".$m."\">Du</a> <a href=\"?s=edit\">Ändra uppgifter</a> <a href=\"?s=object&obj=team&id=self".$m."\">Ditt lag</a> <a href=\"?s=editdubbel".$m."\">Ändra lag</a> <a href=\"?s=tshirt".$m."\">Tröjor</a> <a href=\"?s=kvalval".$m."\">Välj kvaltider</a> <a href=\"?s=funktionarsval".$m."&m2=anmalda\">Bli funktionär!</a> <a href=\"?s=betala".$m."\">Betala</a>";
      } else {
        $content .= "<a href=\"?s=anmalda".$m."\">Anmälda spelare</a> <a href=\"?s=kvalgrupper".$m."\">Kvalgrupper</a> <a href=\"?s=object&obj=team".$m."\">Dubbellag</a>";
      }
    }

  if($category == "funktionar")
    {
      $content .= "<a href = '?s=funktionar".$m."'>Funktionärer</a> <a href = '?s=instruktioner".$m."'>Instruktioner</a>";
      if ($player->mainPlayerId) {
        $content .= ' <a href="?s=funktionarsval'.$m.'&m2=funktionar">Bli funktionär!</a>';
      }
    }

     
  if($category == "tidigare")
    {
      $content .= "<a href = '?s=2012".$m."'>2012</a> <a href = '?s=2011".$m."'>2011</a>  <a href=\"?s=2010".$m."\">2010</a> <a href=\"?s=2009".$m."\">2009</a> <a href=\"?s=2008".$m."\">2008</a> <a href=\"?s=2007".$m."\">2007</a> <a href=\"?s=2006".$m."\">2006</a> <a href=\"?s=2005".$m."\">2005</a> <a href=\"?s=2004".$m."\">2004</a> <a href=\"?s=2003".$m."\">2003</a> <a href=\"?s=90tal".$m."\">90-talet</a>";
    }
     
  if($category == "regler")
    {
      $content .= "<a href = '?s=regler".$m."'>Regler</a> <a href=\"?s=system".$m."\">Tävlingssystem</a> <a href=\"?s=finalsystem".$m."\">Slutspelssystem</a> <a href=\"?s=dubbel".$m."\">Dubbelregler</a> <a href=\"?s=sido".$m."\">Sidotävlingar</a>";
    }
     
     
  $content .= "</p>";
  if ($echo) {
    echo $content;
  } else {
    return $content;
  }
 }


include("huvud.fil");

function print_payment_info()
	{
        echo "<h3>Kontouppgifter</h3>\n";
	    echo "<p>SEB: 5001 03768 01<br/>\n";
        echo "Kontoinnehavare: Hans Andersson<br/>\n";
        echo "<p>Spar en kopia p&aring; din inbetalning. Ange <b>enbart</b> TAG p&aring; avs&auml;ndare. Vet du med dig att du har samma TAG som en annan deltagare kan du skicka ett mail till <a href=\"mailto:hans@hulabeck.se\">hans@hulabeck.se</a> n&auml;r du gjort din inbetalning s&aring; att vi bockar f&ouml;r r&auml;tt person som betalande.</p>\n\n";
        
        echo "<p><b>OBS!</b> Det är de första 210 personerna som BÅDE anmäler sig och betalar som får plats, och det är de 42 första per pass som både anmäler sig och betalar som får sin kvalönskan igenom! För att se om din betalning registrerats, kan du kolla på <a href='?s=anmalda'>anmälda-sidan</a>. Eftersom detta sker manuellt, kan det ta ett par dagar innan din betalning syns på hemsidan.</p>\n\n";
        
        echo "<p>Kontaktperson vid fr&aring;gor om anm&auml;lan: <a href='mailto:hans@hulabeck.se'>Hans Andersson</a></p>\n";
	}


   if($s == "")
     {
       include("start.fil");
     }
   else
     {
       $filnamn = str_replace(":", "", $s);
       include('pages/'.$filnamn.".fil");
     }
      
include("fot.fil");

?>
