<?php
//  https://www.flippersm.se/mobile/registermob.php?playerId=55&gameId=20&score=200000&user=bitwalk&password=abc123

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');
  noError(TRUE, TRUE, FALSE);

  if (isset($_REQUEST['user']) || isset($_REQUEST['username'])) {
    config::$login->action('logout');
    config::$login->verified = TRUE; // No nonce
    config::$login->action('login');
  } else {
    $page = new page('Register');    
  }
  
  $volunteer = volunteer('login');
  if ($volunteer->scorekeeper) {
    $personId = (isset($_REQUEST['playerId'])) ? $_REQUEST['playerId'] : NULL;
    $machineId = (isset($_REQUEST['gameId'])) ? $_REQUEST['gameId'] : NULL;
    $regScore = (isset($_REQUEST['score'])) ? $_REQUEST['score'] : NULL;
    if (isId($machineId)) {
      $machine = machine($machineId);
      if (isMachine($machine)) {
        $division = division($machine);
        if (isDivision($division)) {
          if (isId($personId)) {
            $person = person($personId);
            if (isPerson($person)) {
              $score = score($person, $machine);
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
    echo('statusCode=1'); // Login failed
  }
  
  $debug = (isset($_REQUEST['debug'])) ? $_REQUEST['debug'] : NULL;
  if ($debug) {
    debug(config::$login);    
  }
    
?>


