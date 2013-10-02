<?php
  define('__ROOT__', dirname(__FILE__));
  require_once(__ROOT__.'/functions/general.php');

$s = isset($_GET['s']) ? $_GET['s'] : 'start';

function undermenu($page)
  {
  
  if($page == "anmal" or $page == "object")
     {echo "
     <a href=\"?s=anmal\">Anmälan</a> <a href=\"?s=anmalda\">Anmälda spelare</a><a href = '?s=funktionar'>Bli funktionär</a>";
	}

  if($page == "regler" or $page == "schema" or $page == "resultat" or $page == "narvar")
     {echo "
     <a href=\"?s=regler\">Regler</a> <a href=\"?s=schema\">Kvalschema</a><a href=\"?s=resultat\">Liveresultat</a><a href=\"?s=narvar\">Praktisk info</a>
     ";}

  if($page == "2012" or $page == "kontakt" or $page == "stories" or $page == "blandat" or $page == "spellista")
     {echo "
     <a href=\"?s=2012\">Tidigare SM</a> <a href=\"?s=kontakt\">Kontakt</a><a href=\"?s=stories\">Berättelser</a><a href=\"?s=stories\">Spellista</a>
     ";}

 }

function submenu2($category)
  {
  
  echo "<p class=\"submenu2\">";
  
  if($category == "anmalan")
     {echo "
     <a href=\"?s=anmal\">Anmälan ordinarie & U18</a> <a href=\"?s=anmal_small_family\">Liten familj</a> <a href=\"?s=anmal_big_family\">Stor familj</a>";
	}

  if($category == "anmalda")
     {echo "
      <a href=\"?s=anmalda\">Anmälda spelare</a><br /> <a href=\"?s=ko\">Kölista</a>
     ";}
     
  if($category == "funktionar")
	{echo "<a href = '?s=funktionar'>Bli funktionär</a> <a href = '?s=funkislista'>Dessa är funktionärer</a>";}

     
  if($category == "tidigare")
     {echo "<a href = '?s=2011'>2011</a>  <a href=\"?s=2010\">2010</a> <a href=\"?s=2009\">2009</a> <a href=\"?s=2008\">2008</a> <a href=\"?s=2007\">2007</a> <a href=\"?s=2006\">2006</a> <a href=\"?s=2005\">2005</a> <a href=\"?s=2004\">2004</a> <a href=\"?s=2003\">2003</a> <a href=\"?s=90tal\">90-talet</a>
     ";}
     
  if($category == "regler")
     {echo "<a href = '?s=regler'>Regler</a>  <a href=\"?s=system\">Tävlingssystem</a> <a href=\"?s=former\">Tävlingsformer</a>
     ";}


  echo "</p>";
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
