<?php

function customTemplate($page) {
	switch($page) {
		case 'you':
		case 'you-2':
		case 'your-pages':
      header('Location: /registration/players/?obj=player&id=self');
      die();
    break;
		case 'players':
		case 'player':
		case 'other-players':
	    include($_SERVER['DOCUMENT_ROOT'].'/player.php');
    break;
		case 'team-registration':
	    include($_SERVER['DOCUMENT_ROOT'].'/teamReg.php');
    break;
		case 'qualification-groups':
	    include($_SERVER['DOCUMENT_ROOT'].'/chooseQual.php');
    break;
		case 'qualgroups':
	    include($_SERVER['DOCUMENT_ROOT'].'/qualGroups.php');
    break;
		case 'standings':
	    include($_SERVER['DOCUMENT_ROOT'].'/standings.php');
    break;
		case 'classics-finals':
	    include($_SERVER['DOCUMENT_ROOT'].'/classicsFinals.php');
    break;
		case 'main-finals':
	    include($_SERVER['DOCUMENT_ROOT'].'/mainFinals.php');
    break;
		case 'b-finals':
	    include($_SERVER['DOCUMENT_ROOT'].'/bFinals.php');
    break;
		case 'bracket':
	    include($_SERVER['DOCUMENT_ROOT'].'/bracket.php');
    break;
		case 'your-team':
      header('Location: /registration/teams/?obj=team&id=self');
      die();
    break;
		case 'teams':
		case 'team':
      include($_SERVER['DOCUMENT_ROOT'].'/team.php');
    break;
		case 'national-teams':
		case 'national-team':
      include($_SERVER['DOCUMENT_ROOT'].'/nationalTeam.php');
    break;
		case 'volunteer':
		case 'volunteer-registration':
		case 'volunteers':
	    include($_SERVER['DOCUMENT_ROOT'].'/volunteer.php');
    break;
		case 'volunteer-schedule':
	    include($_SERVER['DOCUMENT_ROOT'].'/volunteerSchedule.php');
    break;
		case 'admin-tools':
	    include($_SERVER['DOCUMENT_ROOT'].'/tournament.php');
    break;
		case 'password-reset':
	    include($_SERVER['DOCUMENT_ROOT'].'/forgotPassword.php');
    break;
		case 'payment-options':
	    include($_SERVER['DOCUMENT_ROOT'].'/payment.php');
    break;
		case 'country':
		case 'countries':
	    include($_SERVER['DOCUMENT_ROOT'].'/country.php');
    break;
		case 'city':
		case 'cities':
	    include($_SERVER['DOCUMENT_ROOT'].'/city.php');
    break;
		case 'region':
		case 'regions':
	    include($_SERVER['DOCUMENT_ROOT'].'/region.php');
    break;
		case 'continent':
		case 'continents':
	    include($_SERVER['DOCUMENT_ROOT'].'/continent.php');
    break;
		case 'manufacturer':
		case 'manufacturers':
      include($_SERVER['DOCUMENT_ROOT'].'/manufacturer.php');
    break;
		case 'machine':
		case 'machines':
		case 'game':
		case 'games':
	    include($_SERVER['DOCUMENT_ROOT'].'/game.php');
    break;
		case 'gender':
		case 'genders':
	    include($_SERVER['DOCUMENT_ROOT'].'/gender.php');
    break;
		case 'object':
			include($_SERVER['DOCUMENT_ROOT'].'/object.php');
	  break;
		case 't-shirts':
		case 'tshirts':
		case 't-shirt':
		case 'tshirt':
			include($_SERVER['DOCUMENT_ROOT'].'/tshirts.php');
	  break;
		case 'arrive':
	    include($_SERVER['DOCUMENT_ROOT'].'/arrive.php');
    break;
		case 'editplayer':
			include($_SERVER['DOCUMENT_ROOT'].'/edit.php');
	  break;
		case 'statistics':
			include($_SERVER['DOCUMENT_ROOT'].'/statistics.php');
	  break;
		case 'registration':
			include($_SERVER['DOCUMENT_ROOT'].'/register.php');
	  break;
		case 'change-credentials':
			include($_SERVER['DOCUMENT_ROOT'].'/changeCredentials.php');
	  break;
		case 'login':
			include($_SERVER['DOCUMENT_ROOT'].'/login.php');
	  break;
		case 'logout':
			include($_SERVER['DOCUMENT_ROOT'].'/logout.php');
	  break;
	}
}

?>
