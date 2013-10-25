<?php
  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  printHeader('EPC 2013', __baseHref__);
  printTopper();

  $content = '<h2 class="entry-title">Payment options!</h2>';
  $content .= '
    <p>Please make sure to complete all the registrations you want to:
      <ul>
        <li><a href="'.__baseHref__.'/registration/">Player registration</a></li>
        <li><a href="'.__baseHref__.'/registration/team-registration/">Team registration</a></li>
        <li><a href="'.__baseHref__.'/your-pages/qualification-groups/">Qualification groups</a></li>
        <li><a href="'.__baseHref__.'/registration/t-shirts/">T-shirts</a></li>
        <li><a href="'.__baseHref__.'/help-out/volunteer-registration/">Volunteer registration</a></li>
      </ul>
    </p>
  ';
  if (checkLogin($dbh, $ulogin, false)) {
  
    $player = getPlayerById($dbh, getIdFromUser($dbh, $ulogin->Username($_SESSION['uid'])));
  
    $main = ($player->mainPlayerId > 0) ? 1 : 0;
    $classics = ($player->classicsPlayerId > 0) ? 1 : 0;
    $team = $player->getTeam($dbh);
    $team = ($team) ? count($team) : 0;
    $tShirt = $player->getNoOfTshirts($dbh);
  
    $currencies = array('SEK', 'EUR', 'GBP', 'USD');
    $rate['SEK'] = 1;
    $rate['EUR'] = 8;
    $rate['GBP'] = 10;
    $rate['USD'] = 6;
  
    $mainCost = 300;
    $classicsCost = 200;
    $teamCost = 100;
    $tShirtCost = 100;
  
    foreach($currencies as $currency) {
      $mainCosts[$currency] = round($main * $mainCost / $rate[$currency]);
      $classicsCosts[$currency] = round($classics * $classicsCost / $rate[$currency]);
      $teamCosts[$currency] = round($team * $teamCost / $rate[$currency]);
      $tShirtCosts[$currency] = round($tShirt * $tShirtCost / $rate[$currency]);
      $totalCost[$currency] = $mainCosts[$currency] + $classicsCosts[$currency] + $teamCosts[$currency] + $tShirtCosts[$currency];
      $currencyChoice .= '<input type="radio" id="currency" name="currency" value="'.$currency.'" onchange="currencyChange(this);" '.(($currency == 'SEK') ? 'checked' : '').'><label class="labelTd">'.$currency.'</label>';
    }
    
    $currency = 'SEK';
    $content .= '
      <input type="hidden" id="idHidden" value="'.$player->id.'">
      <input type="hidden" id="nameHidden" value="'.$player->name.'">
      <input type="hidden" id="currency" value="'.$currency.'">
      <input type="hidden" id="paid" value="'.$player->paid.'">
      <p class="italic">Note: Team payment is per person - to pay for the full team, enter 4 in the team field.<br />
      Our exchange rates are lousy, so you will save money by paying in SEK.<br />
      The fields are pre-filled from your registrations, but you can change it if you want to pay for other people as well.</p>
      <table>
        <tr>
          <td class="labelTd"><label>Choose currency:</label></td>
          <td colspan="2">'.$currencyChoice.'</td>
        </tr>
      </table>
      <table>
        <tr>
          <td class="labelTd"><label>Main division:</label></td>
          <td>
            <input type="text" size="3em" id="mainCosts" value="'.$main.'" onchange="paymentChange(this);">
            <input type="hidden" size="3em" id="mainCost" value="'.$mainCost.'">
          </td>
          <td class="costTd"><span id="mainCostSpan">'.$currency.' '.$mainCosts[$currency].'</span></td>
        </tr>
        <tr>
          <td class="labelTd"><label>Classics division:</label></td>
          <td>
            <input type="text" size="3em" id="classicsCosts" value="'.$classics.'" onchange="paymentChange(this);">
            <input type="hidden" size="3em" id="classicsCost" value="'.$classicsCost.'">
          </td>
          <td class="costTd"><span id="classicsCostSpan">'.$currency.' '.$classicsCosts[$currency].'</span></td>
        </tr>
        <tr>
          <td class="labelTd"><label>Team division (per person):</label></td>
          <td>
            <input type="text" size="3em" id="teamCosts" value="'.$team.'" onchange="paymentChange(this);">
            <input type="hidden" size="3em" id="teamCost" value="'.$teamCost.'">
          </td>
          <td class="costTd"><span id="teamCostSpan">'.$currency.' '.$teamCosts[$currency].'</span></td>
        </tr>
        <tr>
          <td class="labelTd"><label>T-shirts:</label></td>
          <td>
            <input type="text" size="3em" id="tShirtCosts" value="'.$tShirt.'" onchange="paymentChange(this);">
            <input type="hidden" size="3em" id="tShirtCost" value="'.$tShirtCost.'">
          </td>
          <td class="costTd"><span id="tShirtCostSpan">'.$currency.' '.$tShirtCosts[$currency].'</span></td>
        </tr>
        <tr class="sumTr">
          <td class="labelTd sumTd"><label><span class="bold">Total</span>'.(($player->paid > 0) ? ' (<span id="paidCurrency">'.$currency.'</span> <span id="curPaid">'.$player->paid.'</span> already paid)' : '').':</label></td>
          <td class="sumTd">&nbsp;<input type="hidden" id="totalCost" value="'.$totalCost[$currency].'"></td>
          <td class="costTd bold sumTd"><span id="totalCostSpan">'.$currency.' '.((($totalCost[$currency] - $player->paid) > 0) ? ($totalCost[$currency] - $player->paid) : 0).'</span></td>
        </tr>
      </table>
      <p class="italic">Sum may not add up exactly, due to currency rates and roundings.</p>
      <table id="paymentMethodTable">
        <tr>
          <td class="labelTd"><label>Choose payment method:</label></td>
          <td class="labelTd"><input type="radio" checked id="paymentTypePayPal" name="paymentType" value="paypal" onchange="paymentMethodChange(this);"><label for="paymentTypePayPal">Electronic/credit card</label></td>
          <td class="labelTd"><input type="radio" id="paymentTypeDomestic" name="paymentType" value="domestic" onchange="paymentMethodChange(this);"><label for="paymentTypeDomestic">Domestic bank transfer</label></td>
          <td class="labelTd"><input type="radio" id="paymentTypeInternational" name="paymentType" value="international" onchange="paymentMethodChange(this);"><label for="paymentTypeInternational">International bank transfer</label></td>
        </tr>
      </table>
      <div id="domesticTable" style="display: none;">
        <table>
          <tr>
            <td colspan="2" id="domesticCosts">Please pay '.$currency.' '.((($totalCost[$currency] - $player->paid) > 0) ? ($totalCost[$currency] - $player->paid) : 0).' to one of the below:</td>
          </tr>
          <tr>
            <td class="labelTd"><label>Bankgiro:</label></td>
            <td>5909-5182</td>
          </tr>
          <tr>
            <td class="labelTd"><label>Swedbank:</label></td>
            <td>8327-9 903.192.049-0</td>
          </tr>
          <tr>
            <td class="labelTd"><label>SEB:</label></td>
            <td>5267-2992762</td>
          </tr>
        </table>
      </div>
      <div id="internationalTable" style="display: none;">
        <table>
          <tr>
            <td colspan="2" id="internationalCosts">Please pay '.$currency.' '.((($totalCost[$currency] - $player->paid) > 0) ? ($totalCost[$currency] - $player->paid) : 0).' to the account below:</td>
          </tr>
          <tr>
            <td class="labelTd"><label>BIC/SWIFT address:</label></td>
            <td>SWEDSESS</td>
          </tr>
          <tr>
            <td class="labelTd"><label>IBAN number:</label></td>
            <td>SE2280000832799031920490</td>
          </tr>
        </table>
      </div>
      <div id="paypalTable">
        <span class="italic">Please click on the image to pay using your credit card or PayPal account. To pay with your credit card on PayPal, choose the "Don\'t have a PayPal account" option on the next page.</span>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
          <input type="hidden" name="cmd" value="_xclick">
          <input type="hidden" name="business" value="the@pal.pp.se">
          <input type="hidden" name="undefined_quantity" value="1">
          <input type="hidden" name="item_name" value="EPC Entrance Fee">
          <input type="hidden" name="item_number" value="1">
          <input type="hidden" id="payPalAmount" name="amount" value="'.((($totalCost[$currency] - $player->paid) > 0) ? ($totalCost[$currency] - $player->paid) : 0).'">
          <input type="hidden" name="page_style" value="StockholmOpen">
          <input type="hidden" name="no_shipping" value="1">
          <input type="hidden" name="return" value="'.__baseHref__.'/payment-successful/">
          <input type="hidden" name="cancel_return" value="'.__baseHref__.'/payment-canceled/">
          <input type="hidden" name="cn" value="What you are paying for">
          <input type="hidden" name="on0" value="Pay for">
          <input type="hidden" id="payPalMsg" name="os0" value="ID: '.$player->id.' Main: '.$main.' Classics: '.$classics.' Teams: '.$team.' T-shirts: '.$tShirt.'">
          <input type="hidden" id="payPalCurrency" name="currency_code" value="'.$currency.'">
          <input type="image" src="'.__baseHref__.'/images/paypal_'.$currency.'.gif" border="0" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!" id="payPalImg">
        </form>
        <p class="italic">Note: The payment page will say &quot;Stockholm Open&quot;. This is normal, since the Stockholm Open staff is organizing EPC this year.</p>
      </div>
      <p>When paying with PayPal or credit card, we will get all the needed info automatically. But if you pay through a bank transfer, please mark all payments with names or initials for all participants / teams you pay for, depending on the space available. You can choose to pay for several participants at one occasion, as long as there\'s room for the required information.</p>
    ';

  } else {
    $content .= '<p>You need to <a href="../">register</a> to create a user before you can use the payment options.</p>';
    $content .= showLogin($ulogin, 'If you already have a user, please login.');
  }
  
  
  
  echo($content);
  printFooter($dbh, $ulogin);
?>
