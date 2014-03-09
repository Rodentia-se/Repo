<?php

  define('__ROOT__', dirname(dirname(dirname(__FILE__))));
  require_once(__ROOT__.'/functions/init.php');

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
        $prefix = 't-shirts';
        $tshirtsDiv = new div($prefix.'Div');
          $tshirtsDiv->data_title = ucfirst($prefix);
          $tshirtsDiv->addH2($tshirtsDiv->data_title, array('class' => 'entry-title'));
          $paymentLevels = $persons->getListOf('paid');
          sort($paymentLevels);
          foreach ($paymentLevels as $paymentLevel) {
            if ($paymentLevel >= config::$baselineCost + config::$tshirtCost) {
              ${$tshirtsDiv->id.$paymentLevel.'NumDiv'} = $tshirtsDiv->addDiv();
                ${$tshirtsDiv->id.$paymentLevel.'NumDiv'}->addLabel('Paid € '.$paymentLevel);
                ${$tshirtsDiv->id.$paymentLevel.'Num'} = $persons->getNumOf('paid', $paymentLevel);
                ${$tshirtsDiv->id.$paymentLevel.'NumDiv'}->addSpan(${$tshirtsDiv->id.$paymentLevel.'Num'}.' players');
                $tshirtsNum += (+ ${$tshirtsDiv->id.$paymentLevel.'Num'} * ($paymentLevel - config::$baselineCost) / 15);
              //Num
            }
          }
          $tshirtsNumDiv = $tshirtsDiv->addDiv();
            $tshirtsNumDiv->addLabel('Estimated');
            $tshirtsNumDiv->addSpan(floor($tshirtsNum).' T-shirts paid for'); 
          //$tshirtsNumDiv
          $tshirts = tshirts($tournament);
          $headers = array('T-shirt', 'Total', 'Reservers', 'Reserved', 'Delivered', 'Sold on site', 'In stock', 'For sale');
          foreach ($tshirts as $tshirt) {
            $rows[] = array($tshirt->name, $tshirt->number, $tshirt->reservers, $tshirt->reserved, $tshirt->delivered, $tshirt->soldOnSite, $tshirt->inStock, $tshirt->forSale);
            $tshirtsRes += $tshirt->reserved;
          }
          $tshirtsResDiv = $tshirtsDiv->addDiv();
            $tshirtsResDiv->addLabel('Reserved');
            $tshirtsResDiv->addSpan($tshirtsRes.' T-shirts'); 
          //$tshirtsNumDiv
          $tshirtsDiv->addH3('Details', array('class' => 'entry-title'));
          $tshirtsDiv->addTable($rows, $headers)->addDatatables();
          $tshirtOrders = tshirtOrders($tournament);
          $mailAddresses = $tshirtOrders->getListOf('mailAddress');
          $otherAddresses = array_diff($personMailAddresses, $mailAddresses);
          $tshirtsPaidPersons = $persons->getFiltered('paid', 45, '>=');
          $tshirtsPaidAddresses = $tshirtsPaidPersons->getListOf('mailAddress');
          $tshirtsPaidNotChosenAddresses = array_diff($tshirtsPaidAddresses, $mailAddresses);
          if ($mailAddresses || $otherAddresses || $tshirtsPaidAddresses || $tshirtsPaidNotChosenAddresses) {
            $tshirtsDiv->addH2('Email addresses', array('class' => 'entry-title'))->addCss('margin-top', '15px');
            $tshirtsDiv->addParagraph('Note: Players that haven\'t registered their email address are not included. Click in the box to copy the addresses to your clipboard.', NULL, 'italic');
            $tshirtMailTabs = $tshirtsDiv->addTabs(NULL, 'tshirtMailTabs');
          }
          if ($tshirtsPaidNotChosenAddresses) {
            $tshirtMailPaidNotChosenDiv = $tshirtMailTabs->addDiv('tshirtMailDiv_paidNoChosen', NULL, array('data-title' => 'Paid, but no choices'));
              $tshirtMailPaidNotChosenDiv->addParagraph('Email addresses to all players that have paid for T-shirts, but NOT chosen any:');
              $tshirtMailPaidNotChosenDiv->addParagraph(implode(', ', $tshirtsPaidNotChosenAddresses), $prefix.'tshirtsPaidNotChosenAddresses', 'toCopy');
            //}
          }
          if ($mailAddresses) {
            $tshirtMailChosenDiv = $tshirtMailTabs->addDiv('tshirtMailDiv_chosen', NULL, array('data-title' => 'Made choices'));
              $tshirtMailChosenDiv->addParagraph('Email addresses to all players that have chosen T-shirts, no matter if they paid for them or not;');
              $tshirtMailChosenDiv->addParagraph(implode(', ', $mailAddresses), $prefix.'mailAddresses', 'toCopy');
            //}
          }
          if ($tshirtsPaidAddresses) {
            $tshirtMailPaidDiv = $tshirtMailTabs->addDiv('tshirtMailDiv_paid', NULL, array('data-title' => 'Paid'));
              $tshirtMailPaidDiv->addParagraph('Email addresses to all players that have paid for T-shirts, no matter if they have chosen T-shirts or not:');
              $tshirtMailPaidDiv->addParagraph(implode(', ', $tshirtsPaidAddresses), $prefix.'tshirtsPaidAddresses', 'toCopy');
            //}
          }
          if ($otherAddresses) {
            $tshirtMailOtherDiv = $tshirtMailTabs->addDiv('tshirtMailDiv_other', NULL, array('data-title' => 'No choices'));
              $tshirtMailOtherDiv->addParagraph('Email addresses to all players that have NOT chosen T-shirts, no matter if they paid or not:');
              $tshirtMailOtherDiv->addParagraph(implode(', ', $otherAddresses), $prefix.'otherAddresses', 'toCopy');
            //}
          }
          $tshirtsDiv->addParagraph('More coming soon...')->addCss('margin-top', '15px');;
        //$tshirtsDiv
        echo $tshirtsDiv->getHtml();
      } else {
        echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
      }

    ?>