<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');

  if ($page->checkLogin()) {
    $person = $page->login->person;
    $player = $person->getPlayer();
    if ($player) {
      debug($player, TRUE);
      header('Location: '.config::$baseHref.'/edit/');
    } else {
      if ($_REQUEST['register'] == 'yes') {
        $person->addPlayer();
        header('Location: '.config::$baseHref.'/edit/');
      } else {
        $tournament = tournament(config::$activeTournament);
        $page->addH2('Register player');
        $page->addParagraph('
          <form id="registerForm" action="'.$_SERVER['REQUEST_URI'].'" method="POST">
            You are logged in as '.$page->login->person->name.'. Press the button to register for '.$tournament->name.':
            <input type="hidden" name="register" value="yes">
            <input type="button" id="registerButton" value="Register">
          </form>');
        $page->addScript('
          $("#registerButton").click(function() {
            $("#registerForm").submit();
          });
        ');
        $page->focus('registerButton');
      }
    }
  } else {
    if ($_REQUEST['register'] == 'isMe') {
      $person_id = $_REQUEST['person_id'];
      if (isId($person_id)) {
        $person = person($person_id);
        if ($person) {
          if ($person->username) {
            $page->addH2('Register player');
            $page->addLogin('Hello '.$person->name.'! You are '.(($_REQUEST['action'] == 'newUser') ? 'now' : 'already').' registered as a user. Please login here:', TRUE);
          } else {
            $page->addH2('Register new user');
            if ($_REQUEST['action'] == 'newUser') {
              $page->addParagraph('Something went wrong. Please note any error messages above, and try again. If it still doesn\'t work, please <a href="'.config::$baseHref.'/misc/contact-us/">contact us</a>.');
            }
            $page->addParagraph('You have identified yourself as '.$person->name.' '.(($person->shortName) ? '('.$person->shortName.')' : '').' from '.(($person->cityName) ? $person->cityName.', ' : '').$person->countryName.'. Make sure this is correct, and then choose a username and password below.');
            $page->addNewUser('Register a new user', $person_id);
            $page->addScript('
              $("#newUserForm").append("<input type=\"hidden\" name=\"register\" value=\"isMe\">");
            ');
          }
        }
      }
      $page->submit();
      debug('huff', true);
      $person->addPlayer();
      header('Location: '.config::$baseHref.'/edit/');
    } else {
      $page->startDiv('login');
        $page->addH2('Register existing player');
        $page->addLogin('If you participated in EPC 2013 or a any other tournament using this system, then please login here', TRUE);
        $page->addParagraph('If you are sure you do not have any user, please click this button to proceed: <input type="button" id="view_search" class="viewButton" value="Register a new player">');
      $page->closeDiv();
      $page->startDiv('search', 'hidden');
        $page->addH2('Register a new player');
        $page->addParagraph('We might already know who you are! Enter your IFPA ID (visible in the address bar when you look at your IFPA page), your email address or phone number used for SO, SM or EPC registrations in the past, your first, last, middle, partial or full name (more than three letters) or even your three-letter TAG (include trailing spaces). Then press the button (or enter/return) and feel the magic. If we can\'t find you, just try another sarch - we\'ve got more than 20 000 friends, and you\'re most probably one of them.');
        $page->addParagraph('PLEASE SEARCH BEFORE YOU DECIDE TO REGISTER AS A NEW PERSON! If you have ever played a pinball tournament, you are most likely NOT a new guy.');
        $page->addParagraph('Enter IFPA ID, email address, phone number, name or tag: <input type="text" id="searchBox" name="search"> <input type="button" id="searchButton" value="Search">');
        $page->addParagraph('Do you want to try logging in again? <input type="button" id="view_login" class="viewButton" value="Back to login">');
        $page->startDiv('searchResults', 'hidden');
          $page->addParagraph('Find yourself in the table below, and click the <input type="button" value="This is me!"> button. If you can\'t find yourself, just try another search.');
          $page->addParagraph('<p id="newGuy" style="display: none">If you really can\'t find yourself in the database, click this button to register as a new person: <input type="button" id="addButton" value="I\'m a new guy!">');
          $page->addTable('resultsTable', array('Name', 'Tag', 'City', 'Country', 'IFPA', 'Picture', 'Me?'));
        $page->closeDiv();
      $page->closeDiv();
      $page->focus('usernameLogin');
      $page->datatables = TRUE;
      $page->addScript('
        $(".viewButton").click(function() {
          $("#login").hide();
          $("#search").hide();
          $("#" + this.id.replace("view_", "")).show();
          $("#" + ((this.id == "view_login") ? "usernameLogin" : "searchBox")).focus();
        });
        $("#searchButton").click(function() {
          $("#searchResults").show();
          if ($.trim($("#searchBox").val()).length > 0) {
            $("#newGuy").show();
            $("#resultsTable").show();
            var tbl = $("#resultsTable").dataTable({
              "bProcessing": true,
              "bDestroy": true,
              "fnDrawCallback": function() {
                $(":button").button();
                $(".isMe").click(function() {
                  $("#" + this.id.split("_")[0] + "_isMeForm").submit();
                });
                return true;
              },
              "bJQueryUI": true,
          	  "sPaginationType": "full_numbers",
              "iDisplayLength": -1,
              "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
              "bServerSide": false,
              "oLanguage": {
                "sProcessing": "<img src=\"'.config::$baseHref.'/images/ajax-loader.gif\" alt=\"Loading data...\">"
              },
              "sAjaxSource": "'.config::$baseHref.'/ajax/getPlayers.php?type=regSearch&search=" + $("#searchBox").val()
            });
            $("#resultsTable").css("width", "");
          } else {
            toolTip("searchBox", "Please enter a search term", true);
          }
        });
        $("#searchBox").keypress(function(e) {
          if (e.keyCode == $.ui.keyCode.ENTER) {
            if ($.trim($("#searchBox").val()).length > 0) {
              $("#searchButton").click();
            }
          }
        });
      ');
    }
  }
  
  $page->submit();

?>