<?php
//  https://www.flippersm.se/mobile/registermob.php?playerId=55&gameId=20&score=200000&user=bitwalk&password=abc123

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');
  noError(TRUE, TRUE, FALSE);

  $page = new page('Register');
  if (!$page->loggedin()) {
    config::$login->verified = TRUE; // No nonce
    config::$login->action('login');
  }

  $volunteer = volunteer('login');
  if ($volunteer->scorekeeper) {
    $personId = (isset($_REQUEST['playerId'])) ? $_REQUEST['playerId'] : NULL;
    $machineId = (isset($_REQUEST['gameId'])) ? $_REQUEST['gameId'] : NULL;
    $regScore = (isset($_REQUEST['score'])) ? preg_replace('/[^0-9]/', '', $_REQUEST['score']) : NULL;

      if (isId($machineId)) {
      $machine = machine($machineId);
      if (isMachine($machine)) {
        $division = division($machine);
        if (isDivision($division)) {
          if (isId($personId)) {
            $person = person($personId);
            if (isPerson($person)) {
              $player = player($person, $division);
              if (isPlayer($player)) {
                $entries = entries($player);
                if ($entries && count($entries) > 0) {
                  $entry = $entries[0];  // TODO: Remove EPC 2014 specific restrictions
                } else {
                  $entry = entry($player->addEntry());
                }
                $score = score($entry, $machine);
                if (!isScore($core)) {
                  $scores = scores($entry);
                  if (!$scores || count($scores) < 5) {
                    $score = $entry->addScore($machine);
                  }
                }
                if (isScore($score)) {
                  if ($score->score) {
                    echo('statusCode=4');
                  } else {
                    if (isId($regScore)) {
                      if ($regScore > 0) {
                        $score->score = $regScore;
                        $save = $score->save();
                        if ($save) {
                          $checkScore = score($score->id);
                          if ($checkScore->score == $regScore) {
                            echo('statusCode=0');
                          } else {
                            echo('statusCode=2');
                          }
                        } else {
                          echo('statusCode=2');
                        }
                      } else {
                        echo('statusCode=2');
                      }
                    } else {
                      echo('statusCode=2');
                    }
                  }
                } else {
                  echo('statusCode=2');
                }
              } else {
                echo('statusCode=2');
              }
            } else {
              echo('statusCode=2');
            }
          } else {
            echo('statusCode=2');
          }
        } else {
          echo('statusCode=2');
        }
      } else {
        echo('statusCode=2');
      }
    } else {
      echo('statusCode=2');
    }
  } else {
    echo('statusCode=1'); // Login failed
  }
  
?>


