<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');
  
  $number = (isId($_REQUEST['number'])) ? $_REQUEST['number'] : NULL;
  $person_id = (isId($_REQUEST['person_id'])) ? $_REQUEST['person_id'] : NULL;
  $tshirt_id = (isId($_REQUEST['tshirt_id'])) ? $_REQUEST['tshirt_id'] : NULL;
  $tshirtOrder_id = (isId($_REQUEST['tshirtOrder_id'])) ? $_REQUEST['tshirtOrder_id'] : NULL;

  $loginPerson = person('login');
  if ($person_id) {
    $person = person($person_id);
  } else {
    $person = $loginPerson;
  }
  if ($person) {
    if ($loginPerson->id == $person->id || $loginPerson->receptionist) {
      if ($tshirt_id) {
        $tshirt = tshirt($tshirt_id);
        if ($tshirt) {
          if ($tshirtOrder_id) {
            $tshirtOrder = tshirtOrder($tshirtOrder_id);
            if ($tshirtOrder) {
              if ($number) {
                $tshirtOrder->setNumber($number);
                $tshirtOrder = tshirtOrder($tshirtOrder->id);
                if ($tshirtOrder->number == $number) {
                  $json = success('T-shirt order ID '.$tshirtOrder->id.' set to '.$tshirtOrder->number.' number of T-shirts');
                } else {
                  $json = failure('Could not change T-shirt order ID '.$tshirtOrder->id.' to '.$tshirtOrder->number.' number of T-shirts');
                }
              } else if ($number == 0) {
                $tshirtOrder->delete();
                $tshirtOrder = tshirtOrder($tshirtOrder->id);
                if (!$tshirtOrder) {
                  $json = success('Removed T-shirt order ID '.$tshirtOrder_id);
                } else {
                  $json = failure('Could not remove T-shirt order ID '.$tshirtOrder_id);
                }
              } else {
                $json = failure('The number of T-shirts is invalid: '.$number);
              }
            } else {
              $json = failure('Could not find T-shirt order ID '.$tshirtOrder_id);
            }
          } else if ($tshirtOrder_id == 0) {
            if ($number) {
              $tshirtOrder = tshirtOrder(array(
                'name' => $tshirt->tournament->name.', '.$person->shortName.': '.$tshirt->colorName.' '.strtoupper($tshirt->size),
                'person_id' => $person->id,
                'tournamentTShirt_id' => $tshirt->id,
                'number' => $number,
                'firstName' => $person->firstName,
                'lastName' => $person->lastName,
                'initials' => $person->shortName,
                'streetAddress' => $person->streetAddress,
                'zipCode' => $person->zipCode,
                'telephoneNumber' => $person->telephoneNumber,
                'mobileNumber' => $person->mobileNumber,
                'dateRegistered' => today()
              ));
              $tshirtOrder_id = $tshirtOrder->save();
              $newOrder = tshirtOrder($tshirtOrder_id);
              if ($newOrder) {
                if ($newOrder->number = $tshirtOrder->number) {
                  $json = success('Created new order ID '.$newOrder->id.' for '.$newOrder->number.' number of T-shirts', array('newId' => $newOrder->id));
                } else {
                  $json = failure('Something went wrong saving the new order ID '.$newOrder->id);
                }
              } else {
                $json = failure('Could not save the new order');
              }
            } else {
              $json = failure('Zero T-shirts ordered - no order created');
            }
          } else {
            $json = failure('The order ID '.$tshirtOrder_id.' is invalid');
          }
        } else {
          $json = failure('Could not find T-shirt ID '.$tshirt_id);
        }
      } else {
        $json = failure('T-shirt ID not specified');
      }
    } else {
      $json = failure('Authorization failed');
    }
  } else {
    $json = failure('Could not identify the target person');
  }
  
  jsonEcho($json);
  
?>