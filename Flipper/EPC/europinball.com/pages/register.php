<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');

  if ($page->loggedin()) {
    $person = person('login');
    $player = player($person);
    if ($player) {
      $_SESSION['msg'] = 'You are logged in and already registered.';
      header('Location: '.config::$baseHref.'/edit/');
    } else {
      if ($_REQUEST['register'] == 'yes' || $_REQUEST['action'] == 'newUser') {
        $division = division('active');
        $add = $person->addPlayer();
        $players = players($division);
        if ($add) {
          if (config::$participationLimit && count($players) > config::$participationLimit) {
            $page->addH2('Register player');
            $page->addParagraph('Unfortunately we could not add you to the tournament, since it is already full.');
            $page->addParagraph('We have added you to the waiting list, and we will contact you if a spot becomes available for you.');
            $page->addClickButton('Update your info', 'update', NULL, TRUE, 'edit');
            $page->focus('updateButton');
          } else {
            header('Location: '.config::$baseHref.'/edit/');
          }
        } else {
          $page->addH2('Register player');
          $page->addParagraph('Something went wrong trying to add you to the tournament. You can either try again or contact us for assistance.');
          $page->addClickButton('Try again');
          $page->focus('TryagainButton');
        }
      } else {
        $tournament = tournament('active');
        $page->addH2('Register player');
        $page->addClickButton('Register', NULL, NULL, array('register' => 'yes'), NULL, NULL, NULL, 'POST', 'You are logged in as '.$person->name.'. Press the button to register for '.$tournament->name.'.');
        $page->focus('registerButton');
      }
    }
  } else {
    if ($_REQUEST['register'] == 'isMe') {
      $person_id = $_REQUEST['person_id'];
      if (!isId($person_id)) {
        $person = person('new');
        $person_id = $person->save();
      }
      if (isId($person_id)) {
        $person = person($person_id);
        if ($person) {
          if ($person->username) {
            $page->addH2('Register player');
            $page->addLogin('Hello '.$person->name.'! You are '.(($_REQUEST['action'] == 'newUser') ? 'now' : 'already').' registered as a user. Please login here:');
            $page->focus('username');
          } else {
            if ($_REQUEST['action'] == 'newUser') {
              $page->addParagraph('Something went wrong. Please note any error messages above, and try again. If it still doesn\'t work, please <a href="'.config::$baseHref.'/misc/contact-us/">contact us</a>.');
            }
            $page->addParagraph('You have identified yourself as '.$person->name.' '.(($person->shortName) ? '('.$person->shortName.')' : '').(($person->cityName || $person->countryName) ? ' from '.(($person->cityName) ? $person->cityName.', ' : '').$person->countryName : '').'. Make sure this is correct, and then choose a username and password below.');
            $page->addNewUser('Register a new user', $person_id, 1);
            $page->addScript('$("#1newUserForm").append("<input type=\"hidden\" name=\"register\" value=\"isMe\">");');
            $page->focus('username');
          }
        } else {
          if ($_REQUEST['action'] == 'newUser') {
            $page->addParagraph('Something went wrong. Please note any error messages above, and try again. If it still doesn\'t work, please <a href="'.config::$baseHref.'/misc/contact-us/">contact us</a>.');
          }
          $page->addParagraph('You have identified yourself as a new guy. Make sure this is correct, and then choose a username and password below.');
          $page->addNewUser('Register a new user', $person_id, 2);
          $page->addScript('$("#2newUserForm").append("<input type=\"hidden\" name=\"register\" value=\"isMe\">");');
          $page->focus('username');
        }
      } else {
        $page->addParagraph('Something went wrong. Please note any error messages above, and try again. If it still doesn\'t work, please <a href="'.config::$baseHref.'/misc/contact-us/">contact us</a>.');
        $page->addClickButton('Try again', 'tryagain2');
        $page->focus('tryagain2Button');
      }
    } else {
      $page->startDiv('login');
        $page->addH2('Register player');
        $page->addLogin('If you participated in EPC 2013 or a any other tournament using this system, then please login here', TRUE);
        $page->addParagraph('If you are sure you do not have any user, please click this button to proceed: <input type="button" id="view_search" class="viewButton" value="Register a new player">');
        $page->addScript('
          $(".viewButton").click(function() {
            $("#login").hide();
            $("#search").hide();
            $("#addNewGuy").hide();
            $("#" + this.id.replace("view_", "")).show();
            $("#" + ((this.id == "view_login") ? "username" : "searchBox")).focus();
          });
        ');
      $page->closeDiv();
      $page->startDiv('search', 'hidden');
        $page->addH2('Register a new player');
        $page->addParagraph('We might already know who you are! Enter your IFPA ID (visible in the address bar when you look at your IFPA page), your email address or phone number used for SO, SM or EPC registrations in the past, your first, last, middle, partial or full name (more than three letters) or even your three-letter TAG (include trailing spaces). Then press the button (or enter/return) and feel the magic. If we can\'t find you, just try another sarch - we\'ve got more than 20 000 friends, and you\'re most probably one of them.');
        $page->addParagraph('PLEASE SEARCH BEFORE YOU DECIDE TO REGISTER AS A NEW PERSON! If you have ever played a pinball tournament, you are most likely NOT a new guy.');
        $page->addParagraph('Enter IFPA ID, email address, phone number, name or tag: <input type="text" id="searchBox" name="search"> <input type="button" id="searchButton" value="Search">');
        $page->addScript('
          $("#searchButton").click(function() {
            if ($.trim($("#searchBox").val()).length > 0) {
              $("#searchResults").show();
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
                  $(".photoPopup").each(function() {
                    $(this).dialog({
                      autoOpen: false,
                      show: {
                        effect: "blind",
                        duration: 1000,
                      },
                      hide: {
                        effect: "blind",
                        duration: 1000
                      },
                      modal: true, 
                      width: "auto",
                      height: "auto"
                    });
                  });
                  $(".photoIcon").click(function() {
                    var photoDiv = $(this).data("photodiv");
                    $("#" + photoDiv).dialog("open");
                    $(document).on("click", ".ui-widget-overlay", function() {
                      $("#" + photoDiv).dialog("close");
                    });
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
              $("#searchBox").tooltipster("show");
            }
            $("#searchBox").focus();
          });
          $("#searchBox").keypress(function(e) {
            if (e.keyCode == $.ui.keyCode.ENTER) {
              $("#searchButton").click();
            }
          })
          .tooltipster({
            theme: ".tooltipster-light",
            content: "Please enter a search term...",
            interactiveAutoClose: false,
            position: "right",
            trigger: "custom",
            timer: 3000
          });
        ');
        $page->addParagraph('Do you want to try logging in again? <input type="button" id="view_login" class="viewButton" value="Back to login">');
        $page->startDiv('searchResults', 'hidden');
          $page->addParagraph('Find yourself in the table below, and click the <input type="button" value="This is me!"> button. If you can\'t find yourself, just try another search.');
          $page->addParagraph('If you really can\'t find yourself in the database, click this button to register as a new person: <input type="button" id="addButton" value="I\'m a new guy!">', 'newGuy', 'hidden');
          $page->addForm('add', array('register', 'isMe'));
          $page->addScript('
            $("#addButton").click(function() {
              $("#searchResults").hide();
              $("#newGuy").hide();
              $("#addNewGuy").show();
              $("#addNewGuyusername").focus();
            });
          ');
          $page->addTable('resultsTable', array('Name', 'Tag', 'City', 'Country', 'IFPA', 'Picture', 'Me?'));
        $page->closeDiv();
        $page->startDiv('addNewGuy', 'hidden');
          $page->addParagraph('You have identified yourself as a new guy. Make sure this is correct, and then choose a username and password below.');
          $page->addParagraph('If you want to search again, click here: <input type="button" id="view_search_again" value="Search again">');
          $page->addNewUser('Register a new user', "0", 'addNewGuy');
          $page->addScript('$("#newUsernewUserForm").append("<input type=\"hidden\" name=\"register\" value=\"isMe\">");');
          $page->addScript('
            $("#view_search_again").click(function() {
              $("#addNewGuy").hide();
              $("#newGuy").show();
              $("#searchBox").focus();
            });
          ');
        $page->closeDiv();
      $page->closeDiv();
      $page->focus('username');
      $page->datatables = TRUE;
    }
  }
  
  $page->submit();

?>