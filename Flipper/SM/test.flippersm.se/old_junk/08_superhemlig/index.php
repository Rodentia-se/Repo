<?php

$s = $_GET['s'];

function undermenu($page)
  {
  
  echo "<p class=\"undermenu\">";
  
  if($page == "anmal")
     {echo "
     <a href=\"?s=anmal\">Anm�lan</a> &middot; <a href=\"?s=anmalda\">Anm�lda spelare</a> &middot; <a href=\"?s=anmaldaclassics\">Anm�lda classics</a>"; /*&middot; <a href=\"?s=funkis\">Funktion�r</a>
     ";*/}
     
  if($page == "regler")
     {echo "
     <a href=\"?s=regler\">Regler</a> &middot; <a href=\"?s=system\">T�vlingssystem</a>
     ";}
     
     
  if($page == "tidigare")
     {echo "
     <a href=\"?s=2007\">2007</a> &middot; <a href=\"?s=2006\">2006</a> &middot; <a href=\"?s=2005\">2005</a> &middot; <a href=\"?s=2004\">2004</a> &middot; <a href=\"?s=2003\">2003</a> &middot; <a href=\"?s=90tal\">90-talet</a>
     ";}
  
  echo "</p>";
  }
  
  
include("huvud.fil");





   if($s == "")
      {
      @include("start.fil");
      }
   else
      {
      $filnamn = str_replace(":", "", $s);
      @include("$filnamn".".fil");
      }
      
include("fot.fil");

?>