<?php
  $to = "hassehulabeck@yahoo.se,the@pal.pp.se";
  $subject = "Flipperboksbest�llning";
  $body = $_POST['fornamn'] . " (" . $_POST['tag'] . ") " . $_POST['efternamn'] . " har best�llt " . $_POST['antal'] . " b�cker!\n\nUppgifter: \nAdressrad 1: " . $_POST['adress1'] . "\nAdressrad 2: " . $_POST['adress2'] . "\nPostnummer: " . $_POST['postnummer'] . "\nOrt: " . $_POST['ort'] . "\nTelefon: " . $_POST['telefon'] . "\nE-post: " . $_POST['epost'] . "\nFrakt: " . $_POST['frakt'] . "\n";
  $headers = "From: " . $_POST['epost'] . "\r\nX-BOK: Yes";
  $to2 = $_POST['epost'];
  $subject2 = "Flipper�rsboken";
  $body2 = "Hej!\n\nDu har precis best�llt en bok som av framtida forskare kommer att n�mnas i samma andetag som \"Skattkammar�n\", \"Det bl�ser p� m�nen\" och \"Chrusjtjov minns\". Men �n �r den inte din. Vi �r n�mligen s� fr�cka att vi vill ha betalt ocks�.\n\nDu har best�llt " . $_POST['antal'] . " b�cker f�r sammanlagt " . $_POST['antal']*189 . " kr (eller " . $_POST['antal']*89 . " kr om du bidragit/bidrar till boken). Du kan v�lja mellan att betala via bank, till ett bankgiro, med PayPal eller med kreditkort.\n\nF�r betalning via bank, s�tt in pengarna p� SEB 5012-0018861 (kontoinnehavare Hans Andersson).\nGl�m inte att ange din TAG!\n\nF�r betalning till BG, s�tt in pengarna p� 5909-5182 (Stockholm Open / Patrik Bodin).\nGl�m inte att ange din TAG!\n\nF�r betalning via PayPal, skicka pengarna till the@pal.pp.se, eller klicka h�r: http://www.stockholmopen.nu/flipperbok/betala.php?antal=" . $_POST['antal'] . "\nGl�m inte att ange din TAG!\n\nF�r betalning med kreditkort, klicka h�r: http://www.stockholmopen.nu/flipperbok/betala.php?antal=" . $_POST['antal'] . "\nGl�m inte att ange din TAG!\n\nVi beh�ver f� in betalningen senast 2008-10-31.\n";
  $headers2 = "From: flipperboken@stockholmopen.nu";
  if (mail($to, $subject, $body, $headers)) {
    mail($to2, $subject2, $body2, $headers2);
    header("Location: betala.php?antal=" . $_POST['antal']);
  } else {
    mail($to2, $subject2, $body2, $headers2);
    header("Location: fuck.html");
  }
?>
