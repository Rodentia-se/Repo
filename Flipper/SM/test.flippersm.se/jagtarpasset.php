<?php


// open connection
    $db = MySQL_connect("localhost", "flippersm", "nf7JcYqJmYT8ymCE");
    MySQL_select_db("flippersm_test", $db);

$no = mysql_real_escape_string($_GET['person_no']);
$pass = mysql_real_escape_string($_GET['pass']);

    
// input data
    $sql = "UPDATE sm_2012_funkis SET Person_no = $no WHERE Funk_id = $pass";
    $sqlResult = MySQL_query($sql,$db);
    $intRows = MySQL_affected_rows();


		$sql = "SELECT Tag, Firstname, Lastname FROM sm_2012_anmalda WHERE No = $no";
		$result = mysql_query($sql, $db);
		while ($row = mysql_fetch_array($result)) {
			$tag = $row['Tag'];
			$fnamn = $row['Firstname'];
			$lnamn = $row['Lastname'];
			$mail = $row['Email'];
		}
    
    MySQL_close($db);
    
 //   $booAdminMail = mail("hans@hulabeck.se", "Funkisanm�lan - $tag: $pass", "Nu har ett funkispass f�tt sin funktion�r.", "From: SM 2012 <webb@flippersm.se>");

    $booPlayerMail = mail($mail, "Funktion�r p� flipper-SM 2012", "Tack!\n\nDu har nu anm�lt dig som funktion�r till Flipper-SM, 16-18 november 2012!\n\nDitt pass �r nummer $pass, som du kan se p� funkisschema-sidan p� www.flippersm.se", "From: Flipper-SM 2012 <hans@hulabeck.se>");
    
    // && $booAdminMail
    if($intRows == 1  && $booPlayerMail)
        {
        echo "<h1>Anm�lan - Funktion�r</h1>\n\n";
        

        echo "<div class=\"bred\">";
        echo "<h2>Tack f�r din insats p� Flipper-SM 2012</h2>\n\n";
        
        echo "<p>Ett mail har nu skickats till $mail med ungef�r samma information som h�r. Titta g�rna p� funkis-schemat p� www.flippersm.se f�r att h�lla dig uppdaterad.";
        echo "<p>Kontaktperson vid fr&aring;gor om anm&auml;lan: <a href='mailto:hans@hulabeck.se'>Hans Andersson</a></p>\n";
        echo "</div>";
        }
    else
        {
        echo "<h1>Ett fel uppstod</h1>\n\n";
        

        echo "<div class=\"bred\">";
        echo "<p>N&aring;got gick fel n&auml;r anm&auml;lan skulle skickas.</p>\n
        <p>Troligtvis fungerade det inte att skicka ditt bekr&auml;ftelsemail, men din anm&auml;lan kan ha registrerats &auml;nd&aring;. F&ouml;r mer information om vad som st&aring;r i din anm&auml;lan kontakta <a href='mailto:hans@hulabeck.se'>Hans Andersson</a>. <strong>Felkod: 1</strong></p>\n\n";
        echo "</div>";

        }

echo "<a href = 'http://www.flippersm.se'>G� tillbaka till flipper-SM-sajten.</a>";
    

?>
