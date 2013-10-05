<?php
  define('__ROOT__', dirname(__FILE__));
  require_once(__ROOT__.'/functions/general.php');

$s = isset($_GET['s']) ? $_GET['s'] : 'start';

function undermenu($page,$m)
  {
  
  if(getCurrentPlayerId($dbh, $ulogin)->mainPlayerId) //replace with check if logged in
  {
    echo '<a href="?s=?s=object&obj=player&id=self">Dina sidor</a> <a href="?s=object&obj=player">Anmälda spelare</a> <a href="?s=funktionar">Funktionärer</a> <a href="?s=object&obj=game">Spel</a>';
	}
  
  if($page == "anmal" or $page == "object" or $page == 'edit') // "object" and "edit" needs extra identifiers - PAL: going for a unified "object" and "edit" for now...
     {echo "
     <a href=\"?s=anmal\">Anmälan</a> <a href=\"?s=object&obj=player&m=anmal\">Anmälda spelare</a> <a href=\"?s=funktionar\">Funktionärer</a> <a href=\"?s=object&obj=game&m=anmal\">Spel</a>";
	}

  if($page == "om" or ($page == "2012" and $m == "om")  or $page == "regler" or $page == "schema" or $page == "press" or $page == "social" or $page == "video" or $page == "kontakt") // 2012 will need an extra identifier to know which menu it belongs to
     {echo "
     <a href=\"?s=om\">Om Flipper-SM</a> <a href=\"?s=2012&m=om\">Tidigare SM</a> <a href=\"?s=regler\">Regler</a> <a href=\"?s=press\">Press</a> <a href=\"?s=video\">Video</a> <a href=\"?s=kontakt\">Kontakt</a>
     ";}

  if($page == "kvalresultat" or $page == "slutspel" or $page == "sidoresultat" or ($page == "2012" and $m == "resultat") ) // 2012 will need an extra identifier to know which menu it belongs to
     {echo "
     <a href=\"?s=kvalresultat\">Kvalresultat</a> <a href=\"?s=slutspel&type=main\">Slutspel</a> <a href=\"?s=sidoresultat\">Sidotävlingar</a> <a href=\"?s=2012&m=resultat\">Tidigare SM</a>
     ";}

 }

function submenu2($category, $echo = true)
  {
  
  $content = "<p class=\"submenu2\">";
  
  if($category == "anmalda")
     {$content .= "
      <a href=\"?s=anmalda\">Anmälda spelare</a> <a href=\"?s=kvalgrupper\">Kvalgrupper</a> <a href=\"?s=object&obj=team\">Dubbellag</a>
     ";}
     
  if($category == "funktionar")
	{$content .= "<a href = '?s=funktionar'>Bli funktionär</a> <a href = '?s=instruktioner'>Instruktioner</a>";}

     
  if($category == "tidigare")
     {$content .= "<a href = '?s=2012'>2012</a> <a href = '?s=2011'>2011</a>  <a href=\"?s=2010\">2010</a> <a href=\"?s=2009\">2009</a> <a href=\"?s=2008\">2008</a> <a href=\"?s=2007\">2007</a> <a href=\"?s=2006\">2006</a> <a href=\"?s=2005\">2005</a> <a href=\"?s=2004\">2004</a> <a href=\"?s=2003\">2003</a> <a href=\"?s=90tal\">90-talet</a>
     ";}
     
  if($category == "regler")
     {$content .= "<a href = '?s=regler'>Regler</a> <a href=\"?s=system\">Tävlingssystem</a> <a href=\"?s=finalsystem\">Slutspelssystem</a> <a href=\"?s=dubbel\">Dubbelregler</a> <a href=\"?s=sido\">Sidotävlingar</a>
     ";}

  if($category == "slutspel")
     {$content .= "<a href = '?s=slutspel&type=main'>Huvudtävlingen</a>  <a href=\"?s=slutspel&type=b\">B-slutspel</a> <a href=\"?s=slutspel&type=classics\">Classics</a> <a href=\"?s=slutspel&type=dubbel\">Dubbel</a> <a href=\"?s=slutspel&type=u18\">U18</a>
     ";}
     
     
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
      @include("start.fil");
      }
   else
      {
      $filnamn = str_replace(":", "", $s);
      @include('pages/'.$filnamn.".fil");
      }
      
include("fot.fil");

?>
