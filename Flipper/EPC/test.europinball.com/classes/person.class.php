<?php

  class person extends base {
   
    public static $arrClass = 'persons';

    public static $select = '
      select
        o.id as id,
        o.firstName as firstName,
        o.lastName as lastName,
        concat(ifnull(o.firstName, " "), " ", ifnull(o.lastName, " ")) as name,
        concat(ifnull(o.firstName, " "), " ", ifnull(o.lastName, " ")) as fullName,
        o.initials as shortName,
        o.streetAddress as streetAddress,
        o.zipCode as zipCode,
        o.gender_id as gender_id,
        o.city_id as city_id,
        o.region_id as region_id,
        o.parentRegion_id as parentRegion_id,
        o.country_id as country_id,
        o.parentCountry_id as parentCountry_id,
        o.continent_id as continent_id,
        o.telephoneNumber as telephoneNumber,
        o.mobileNumber as mobileNumber,
        o.mailAddress as mailAddress,
        if(o.birthDate = "0000-00-00", NULL, o.birthDate) as birthDate,
        o.ifpa_id as ifpa_id,
        o.ifpaRank as ifpaRank,
        ifnull(o.paid, 0) as paid,
        o.payDate as payDate,
        o.comment as comment,
        o.nonce as nonce,
        o.username as username
      from person o
    ';

    public static $parents = array(
      'gender' => 'gender',
      'city' => 'city',
      'region' => 'region',
      'parentRegion' => 'region',
      'country' => 'country',
      'parentCountry' => 'country',
      'continent' => 'continent'
    );

    public static $children = array(
      'player' => array(
        'field' => 'person',
        'delete' => TRUE,
      ),
      'volunteer' => array(
        'field' => 'person',
        'delete' => TRUE,
      ),
      'matchPlayer' => 'person',
      'matchScore' => 'person',
      'owner' => 'contactPerson',
      'tshirt' => array(
        'table' => 'personTShirt', 
        'field' => 'person'
      ),
      'entry' => 'person',
      'score' => 'person',
      'score' => 'registerPerson',
      'team' => 'registerPerson'
    );

    public static $cols = array(
      'initials' => 'shortName',
      'city' => 'cityName',
      'region' => 'regionName',
      'parentRegion' => 'parentRegionName',
      'country' => 'countryName',
      'parentCountry' => 'parentCountryName',
      'continent' => 'continentName',
      'gender' => 'genderName'
    );
    
    public static $validators = array(
      'telephoneNumber' => '/^[0-9 \-\+\(\)]{6,}$/',
      'mobileNumber' => '/^[0-9 \-\+\(\)]{6,}$/',
      'birthDate' => 'validateDate',
      'dateRegistered' => 'validateDate',
      'shortName' => '/^[a-zA-Z0-9 \-]{1,3}$/',
      'initials' => '/^[a-zA-Z0-9 \-]{1,3}$/',
      'tag' => '/^[a-zA-Z0-9 \-]{1,3}$/'
    );
    
    public function __construct($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
     $persons = array('current', 'active', 'login', 'auth');
      if (is_string($data) && in_array($data, $persons) && $search === config::NOSEARCH) {
        if (isObj(config::$login->person) && isId(config::$login->person->id)) {
          $this->_set(config::$login->person);
          return TRUE;
        } else {
          $this->failed = TRUE;
          return FALSE;
        }
      }
      parent::__construct($data, $search, $depth);
      if ($this->id && !$this->failed) {
        $this->costs = $this->getCost();
        if ($this->costs) {
          $this->toPay = ($this->paid) ? $this->costs - $this->paid : $this->costs;
        }
        if (in_array($data, array('login', 'auth', 'active')) || in_array($search, array('login', 'auth', 'active'))) {
          $tournament = tournament('active');
        } else if ($data == 'current' || $search == 'current') {
          $tournament = tournament('current');
        } else if (isTournament($data)) {
          $tournament = $data;
        } else if (isDivision($data)) {
          $tournament = $data->tournament;
        } else if (isTournament($search)) {
          $tournament = $search;
        } else if (isDivision($search)) {
          $tournament = $search->tournament;
        }
        if (isTournament($tournament)) {
          $this->getVolunteer($tournament);
        }
      }
    }

    public function getVolunteer($tournemant = 'active') {
      $this->volunteer = volunteer($this, $tournemant);
      if ($this->volunteer) {
        $this->volunteer_id = $this->volunteer->id;
        $this->adminLevel_id = $this->volunteer->adminLevel_id;
        $this->adminLevel = $this->volunteer->adminLevel;
        $this->scorereader = $this->volunteer->scorereader;
        $this->allreader = $this->volunteer->allreader;
        $this->scorekeeper = $this->volunteer->scorekeeper;
        $this->receptionist = $this->volunteer->receptionist;
        $this->admin = $this->volunteer->admin;
        $this->hereVol = $this->volunteer->here;
        $this->hours = $this->volunteer->hours;
        $this->alloc = $this->volunteer->alloc;
        $this->hoursDiff = $this->volunteer->hoursDiff;
      }
      return $this->volunteer;
    }
    
    public function getCost($type = NULL) {
      if (!$type || isTournament($type) || in_array($type, array('active', 'current'))) {
        $tournament = getTournament($type);
        $divisions = divisions($tournament);
      } else if (isDivision($type) || in_array($type, config::$activeDivisions)) {
        $divisions = array(division($type));
      }
      $cost = 0;
      if ($type != 'tshirts' && $divisions) {
        foreach ($divisions as $division) {
          $player = player($this, $division);
          if ($player) {
            $cost += config::${$division->type.'Cost'};
          }
        }
      }
      if (isTournament($tournament) || !$type || $type == 'all' || $type == 'tshirts') {
        $tshirtOrders = tshirtOrders($this, $tournament);
        foreach ($tshirtOrders as $tshirtOrder) {
          $cost += $tshirtOrder->number * config::$tshirtCost;
        }
      }
      return $cost;
    }

    public function addPlayer($division = NULL) {
      $division = ($division) ? getDivision($division) : division('active');
      $player = player($this, $division);
      if (!$player) {
        $player = player((array) $this->getFlat(), config::NOSEARCH, 0);
        unset($player->id);
        $player->tournamentDivision_id = $division->id;
        $player->tournamentEdition_id = $division->tournamentEdition_id;
        $player->person_id = $this->id;
        $player->dateRegistered = date('Y-m-d');
        if ($division->main) {
          $players = players($division);
          if (config::$participationLimit[$division->type] && count($players) >= config::$participationLimit[$division->type]) {
            $player->waiting = TRUE;
          }
        }
        $id = $player->save();
        if (isId($id)) {
          $player = player($id);
          if ($player) {
            if ($player->waiting) {
              $this->db->seqWaiting();
            }
            return $id;
          }
        }
      }
      return false;
    }
    
    public function getEdit($type = 'profile', $title = NULL, $tournament = NULL, $prefix = NULL) {
      $tournament = getTournament($tournament);
      switch ($type) {
        case 'payment':
        case 'payments':
          $paymentDiv = new div($prefix.'PaymentDiv');
            if ($title) {
              $paymentDiv->addH2('Payment options', array('class' => 'entry-title'));
            }
            $paymentPerson = $paymentDiv->addHidden('paymentPerson_id', $this->id);
            $gotoProfileP = $paymentDiv->addParagraph('The numbers below are derived from your division registrations and T-shirt orders. You can change things in the ');
/*
              $gotoProfileBtn = $gotoProfileP->addClickButton('Profile editor', NULL, NULL, FALSE, '$("#profiletabLink").click();');
              $gotoProfileP->addContent(' or ');
              */
              $gotoTshirtBtn = $gotoProfileP->addClickButton('T-shirt orders', NULL, NULL, FALSE, '$("#tshirtstabLink").click();');
              $gotoProfileP->addContent(' tab (to also order T-shirt sizes), or you can just change the numbers here before paying (you will need to order T-shirt sizes before ');
              $gotoProfileP->addSpan('January 15th', NULL, 'bold');
              $gotoProfileP->addContent(').');
            //}
            $curDiv = $paymentDiv->addDiv($prefix.'paymentCurrencyDiv');
              $currencyChooser = $curDiv->addContent(getCurrencySelect($prefix.'Payment', ((config::$tshirts) ? FALSE : TRUE)));
            //}
            $divisions = divisions($tournament);
            foreach ($divisions as $division) {
              if (property_exists('config', $division->type.'Cost') && config::${$division->type.'Cost'}) {
                $divisionDiv = $paymentDiv->addDiv($division->id.'_CostDiv');
                  $cost = $this->getCost($division);
                  $spinnerParams = array(
                    'class' => 'paymentSpinner enterChange',
                    'data-division_id' => $division->id,
                    'data-eachcost' => $cost
                  );
                  $player = player($this, $division);
                  $divisionNum = ($player) ? 1 : 0;
                  $spinner = $divisionDiv->addSpinner($prefix.'Payment_'.$division->id, $divisionNum, 'text', ucfirst($division->type), $spinnerParams);
                    $moneySpan = $divisionDiv->addMoneySpan($spinner->value * $spinner->{'data-eachcost'}, $spinner->id.'_moneySpan', config::$currencies[$defaultCurrency]['format'], array('class' => 'payment'));
                    $costs += $spinner->value * $spinner->{'data-eachcost'};
                    $num += $spinner->value;
                  //}
                //}
              }
            }
            $tshirtDiv = $paymentDiv->addDiv($prefix.'PaymentTshirtsDiv');
              $tshirtOrders = tshirtOrders($this, $tournament);
              $tshirtNum = 0;
              foreach ($tshirtOrders as $tshirtOrder) {
                $tshirtNum += $tshirtOrder->number;
              }
              $spinnerParams = array(
                'class' => 'numOfTshirts paymentSpinner enterChange',
                'data-eachcost' => config::$tshirtCost
              );
              $spinner = $tshirtDiv->addSpinner($prefix.'PaymentTshirts', $tshirtNum, 'text', 'T-shirts', $spinnerParams);
                $moneySpan = $tshirtDiv->addMoneySpan($spinner->value * $spinner->{'data-eachcost'}, $spinner->id.'_moneySpan', config::$currencies[$defaultCurrency]['format'], array('class' => 'payment'));
                $costs += $spinner->value * $spinner->{'data-eachcost'};
              //}
            //}
            $paymentDiv->addChange('
              var el = this;
              var number = $(el).val();
              var each = $(el).data("eachcost");
              $("#" + el.id + "_moneySpanAmount").html((+ number * each));
              var cost = 0;
              var num = 0;
              $(".paymentSpinner").each(function() {
                cost += parseInt($("#" + this.id + "_moneySpanAmount").html());
              });
              $("#'.$prefix.'PaymentSubTotalDivMoneySpanAmount").html(cost);
              var toPay = cost - (+ parseInt($("#PaymentPaidDivMoneySpanAmount").html()) * -1);
              $("#PaidTooMuchAmount").html((+ toPay * -1));
              if (toPay > 0) {
                $(".paidTooMuch").hide();
                $(".paidAll").hide();
                $("#PaymentTotalDivMoneySpanAmount").html(toPay);
                $("#payPalImg").prop("disabled", false).prop("title", "Click to pay " + $("#PaymentTotalDivMoneySpan").html() + "!").prop("alt", "Click to pay " + $("#PaymentTotalDivMoneySpan").html() + "!");
                $("#payPalAmount").val(toPay * rate);
                $(".totalSpans").html(toPay * rate);
                $("#TshirtsOrderMore").hide();
              } else if (toPay == 0) {
                $(".paidTooMuch").hide();
                $(".paidAll").show();
                $("#PaymentTotalDivMoneySpanAmount").html(0);
                $("#payPalImg").prop("disabled", true).prop("title", "Nothing to pay!").prop("alt", "Nothing to pay!");
                $("#payPalAmount").val(0);
                $(".totalSpans").html(0);
              } else {
                $(".paidTooMuch").show();
                $(".paidAll").hide();
                $("#PaymentTotalDivMoneySpanAmount").html(0);
                $("#payPalImg").prop("disabled", true).prop("title", "Nothing to pay!").prop("alt", "Nothing to pay!");
                $("#payPalAmount").val(0);
                $(".totalSpans").html(0);
              }
              var orderMoreNum = ($("#PaidTooMuchAmount").html() > 0) ? Math.floor($("#PaidTooMuchAmount").html() / '.config::$tshirtCost.') : 0;
              $("#TshirtsOrderMoreNum").html(orderMoreNum);
              if (orderMoreNum) {
                $("#TshirtsOrderMore").show();
              } else {
                $("#TshirtsOrderMore").hide();
              }
              $("#'.$currencyChooser->id.'").change();
            ', '.paymentSpinner');
            $toPay = ($costs - $this->paid > 0) ? $costs - $this->paid : 0;
            $subTotalDiv = $paymentDiv->addDiv($prefix.'PaymentSubTotalDiv');
              $subTotalDiv->addLabel(' ');
              $subTotalDiv->addSpan(' ', NULL, 'short');
              $subTotalDiv->addMoneySpan($costs, NULL, config::$currencies[$defaultCurrency]['format'], array('class' => 'sum payment'));
            //}
            $paidDiv = $paymentDiv->addDiv($prefix.'PaymentPaidDiv');
              $paidDiv->addLabel(' ');
              $paidDiv->addLabel('Already paid:', NULL, NULL, 'short');
              $paidDiv->addMoneySpan($this->paid * -1, NULL, config::$currencies[$defaultCurrency]['format'], array('class' => 'payment'));
              $paidDiv->addSpan(' You have already paid everything.', $prefix.'PaidAll', (($costs - $this->paid == 0) ? 'paidAll' : 'hidden paidAll'));
              $paidDiv->addSpan(' You have already paid ', $prefix.'PaidTooMuchPrefix', (($costs - $this->paid < 0) ? 'paidTooMuch' : 'hidden paidTooMuch'));
              $paidDiv->addMoneySpan((+ ($costs - $this->paid) * -1), $prefix.'PaidTooMuch', config::$currencies[$defaultCurrency]['format'], array('class' => (($costs - $this->paid < 0) ? 'paidTooMuch' : 'hidden paidTooMuch')));
              $paidDiv->addSpan(' too much.', $prefix.'PaidTooMuchSuffix', (($costs - $this->paid < 0) ? 'paidTooMuch' : 'hidden paidTooMuch'));
            //}
            $totalDiv = $paymentDiv->addDiv($prefix.'PaymentTotalDiv');
              $totalDiv->addLabel(' ');
              $totalDiv->addLabel('To pay:', NULL, NULL, 'short');
              $totalDiv->addMoneySpan($toPay, NULL, config::$currencies[$defaultCurrency]['format'], array('class' => 'sum payment'));
            //}
          //}
          $paymentDiv->addParagraph('If you wish to pay for anyone other than the player logged in, just change the numbers above before you pay, and please include that information in the payment message. There is no fee for the eighties division.', NULL, 'italic');
          return $paymentDiv;
        break;
        case 'tshirt':
        case 'tshirts':
        case 'tshirtOrder':
        case 'tshirtOrders':
          $tshirtsDiv = new div($prefix.'TshirtEditDiv');
          if ($title) {
            $tshirtsDiv->addH2('T-shirt orders', array('class' => 'entry-title'));
          }
          $orderDiv = $tshirtsDiv->addDiv($prefix.'TshirtOrdersDiv', 'leftHalf');
            $tshirtPerson = $orderDiv->addHidden($prefix.'tshirtPerson_id', $this->id);
            $paragraph = $orderDiv->addParagraph('Please order your T-shirts below. Each T-shirt costs ');
              $costSpan = $paragraph->addMoneySpan(config::$tshirtCost, $prefix.'tshirtCostSpan', config::$currencies[config::$defaultCurrency]['format']);
            //}
            $curDiv = $orderDiv->addDiv($prefix.'tshirtCurrencyDiv');
              $currencyChooser = $curDiv->addContent(getCurrencySelect($prefix.'Tshirt', TRUE));
            //}
            $tshirts = tshirts($tournament);
            foreach ($tshirts as $tshirt) {
              $tshirtDiv = $orderDiv->addDiv($prefix.'tshirtsDiv_'.$tshirt->id);
                $tshirtOrder = tshirtOrder($this, $tshirt);
                $spinnerParams = array(
                  'class' => 'tshirtSpinner enterChange',
                  'data-tshirt_id' => $tshirt->id,
                  'data-tshirtorder_id' => (($tshirtOrder) ? $tshirtOrder->id : 0),
                  'data-eachcost' => config::$tshirtCost
                );
                $spinner = $tshirtDiv->addSpinner($prefix.'TshirtOrder_'.$tshirt->id, (($tshirtOrder) ? $tshirtOrder->number : 0), 'text', $tshirt->name, $spinnerParams);
                  $moneySpan = $tshirtDiv->addMoneySpan($spinner->value * $spinner->{'data-eachcost'}, $spinner->id.'_moneySpan', config::$currencies[$defaultCurrency]['format'], array('class' => 'payment'));
                  $costs += $spinner->value * $spinner->{'data-eachcost'};
                  $num += $spinner->value;
                  $spinner->addTooltip('');
                //}
              //}
            }
            $orderDiv->addChange('
              var el = this;
              var tshirtOrder_id = $(el).data("tshirtorder_id");
              var number = $(el).val();
              var each = $(el).data("eachcost");
              $(el).tooltipster("update", "Updating order...").tooltipster("show");
              $.post("'.config::$baseHref.'/ajax/tshirtOrder.php", {number: number, tshirt_id: $(el).data("tshirt_id"), tshirtOrder_id: tshirtOrder_id, person_id: $("#'.$tshirtPerson->id.'").val()})
              .done(function(data) {
                $(el).tooltipster("update", data.reason).tooltipster("show");
                if (data.newId || data.newId == 0) {
                  $(el).data("tshirtorder_id", data.newId);
                }
                $("#" + el.id + "_moneySpanAmount").html((+ number * each));
                var cost = 0;
                var num = 0;
                $(".tshirtSpinner").each(function() {
                  cost += parseInt($("#" + this.id + "_moneySpanAmount").html());
                  num += parseInt($(this).val());
                });
                $("#'.$prefix.'tshirtsSubTotalDivMoneySpanAmount").html(cost);
                $("#'.$prefix.'PaymentTshirtsDivMoneySpanAmount").html(cost);
                $(".numOfTshirts").val(num);
                $("#PaymentTshirts").change();
                $("#'.$currencyChooser->id.'").change();
              });
            ', '.tshirtSpinner');
            $subTotalDiv = $orderDiv->addDiv($prefix.'tshirtsSubTotalDiv');
              $subTotalDiv->addInput($prefix.'tshirtsNumOfTshirts', $num, 'text', 'Total', array('disabled' => TRUE, 'class' => 'short numOfTshirts'));
              $subTotalDiv->addMoneySpan($costs, NULL, config::$currencies[$defaultCurrency]['format'], array('class' => 'sum payment'));
            //}
            $toBuyFor = $this->paid - $this->getCost();
            $orderMoreNum = ($toBuyFor > 0) ? floor($toBuyFor / config::$tshirtCost) : 0;
            $orderMoreDiv = $orderDiv->addDiv($prefix.'tshirtsOrderMore');
              $orderMoreP = $orderMoreDiv->addParagraph('You have already paid enough to order ', $prefix.'TshirtsOrderMore', (($orderMoreNum > 0) ? '' : 'hidden'));
              $orderMoreP->addSpan($orderMoreNum, $prefix.'TshirtsOrderMoreNum');
              $orderMoreP->addContent(' more T-shirts.');
            $goToPaymentDiv = $orderDiv->addDiv('goToPaymentDiv');
              $goToPaymentP = $goToPaymentDiv->addParagraph('Go to the ');
                $gotoPaymentBtn = $goToPaymentP->addClickButton('payment tab', NULL, NULL, FALSE, '$("#paymenttabLink").click();');
                $goToPaymentP->addContent(' to pay or check payment status.');
              //}
            //}
            $orderDiv->addParagraph('Note that changing anything above will be reflected in the T-shirts field on the payment tab.', NULL, 'italic');
          //}
          $tshirtsDiv->addImg(config::$baseHref.'/images/objects/tshirt/2014.jpg', NULL, array('class' => 'rightHalf'));
          return $tshirtsDiv;
        break;
        case 'profile':
        case 'player':
        case 'person':
        default:
          foreach (config::$activeSingleDivisions as $divisionType) {
            $player = ($this->id) ? player($this, $divisionType) : NULL;
            $checkboxes .= page::getInput(($player), $prefix.$divisionType, $divisionType, 'checkbox', 'edit', ucfirst($divisionType), FALSE, ((in_array($divisionType, config::$editDivisions)) ? FALSE : TRUE));
          }
          $genders = genders('all');
          $cities = cities('all');
          $regions = regions('all');
          $countries = countries('all');
          $continents = continents('all');
          return '
            <div id="editDiv">
            	<h2 class="entry-title">'.(($title) ? $title : 'Edit profile').'</h2>
              <p class="italic">Note: All changes below are INSTANT when you press enter or move away from the field.</p>
              '.(($player->waiting) ? '<p>You are on the WAITING LIST for this tournament, and we will contact you id a participation sport becomes available for you.</p>' : '').'
              <div>'.page::getInput($this->firstName, $prefix.'firstName', 'firstName', 'text', 'edit', 'First name').'</div>
              <div>'.page::getInput($this->lastName, $prefix.'lastName', 'lastName', 'text', 'edit', 'Last name').'</div>
              <div>'.page::getInput($this->shortName, $prefix.'shortName', 'shortName', 'text', 'edit', 'Tag').'</div>
              <div>'.$genders->getSelect('gender_id', 'combobox', 'Gender', $this->gender_id).'</div>
              <div>'.page::getInput($this->streetAddress, $prefix.'streetAddress', 'streetAddress', 'text', 'edit', 'Address').'</div>
              <div>'.page::getInput($this->zipCode, $prefix.'zipCode', 'zipCode', 'text', 'edit', 'ZIP').'</div>
              <div id="cityDiv">'.page::getInput(NULL, $prefix.'city', 'city', 'text', 'edit', 'New city', TRUE).'</div>
              <div id="city_idDiv">'.$cities->getSelect('city_id', 'combobox', 'City', $this->city_id, TRUE).'</div>
              <div id="regionDiv">'.page::getInput(NULL, $prefix.'region', 'region', 'text', 'edit', 'New region', TRUE).'</div>
              <div id="region_idDiv">'.$regions->getSelect('region_id', 'combobox', 'Region', $this->region_id, TRUE).'</div>
              <div>'.$countries->getSelect('country_id', 'combobox', 'Country', $this->country_id).'</div>
              <div>'.$continents->getSelect('continent_id', 'combobox', 'Continent', $this->continent_id).'</div>
              <div>'.page::getInput($this->telephoneNumber, $prefix.'telephoneNumber', 'telephoneNumber', 'text', 'edit', 'Phone').'</div>
              <div>'.page::getInput($this->mobileNumber, $prefix.'mobileNumber', 'mobileNumber', 'text', 'edit', 'Cell phone').'</div>
              <div>'.page::getInput($this->mailAddress, $prefix.'mailAddress', 'mailAddress', 'text', 'edit', 'Email').'</div>
              <div>'.page::getLabel('Divisions').$checkboxes.'</div>
              <div>'.page::getInput($this->birthDate, $prefix.'birthDate', 'birthDate', 'text', 'edit date', 'Born').'</div>
            </div>
          ';
        break;
      }
    }
    
    public function getUid() {
      if ($this->username) {
        $uid = config::$login->Uid($this->username);
        if ($uid) {
          return $uid;
        } else {
          error('Person username is invalid.');
        }
      } else {
        error('This person has no user.');
      }
      return FALSE;
    }

    public function setUsername($username = NULL) {
      return $this->setProp('username', $username);
    }
    
    public function setNonce($nonce) {
      return $this->setProp('nonce', $nonce);
    }

    public function setPaid($amount = 1) {
      return $this->setProp('paid', $amount);
    }

    public function getLink($type = 'object', $anchor = TRUE, $thumbnail = FALSE, $preview = FALSE, $defaults = TRUE) {
      switch ($type) {
        case 'ifpa':
          if ($this->ifpa_id) {
            return '<a href="http://www.ifpapinball.com/player.php?player_id='.$this->ifpa_id.'" target="_new">'.(($this->ifpaRank && $this->ifpaRank != 0) ? $this->ifpaRank : 'Unranked').'</a>';
          } else {
            return 'Unranked';
          }
        break;
        default:
          return parent::getLink($type, $anchor, $thumbnail, $preview, $defaults);
        break;
      }
    }

    public static function validateMailAddress($email, $obj = FALSE) {
      $atIndex = strrpos($email, "@");
      if (is_bool($atIndex) && !$atIndex) {
        return validated(FALSE, 'There is no @ sign in the address.', $obj);
      } else {
        $domain = substr($email, $atIndex+1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64) {
          return validated(FALSE, 'The local part of the address is too long.', $obj);
        } else if ($domainLen < 1 || $domainLen > 255) {
          return validated(FALSE, 'The domain part of the address is too long.', $obj);
        } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
          return validated(FALSE, 'The local part of the address can\'t start or end with a dot.', $obj);
        } else if (preg_match('/\\.\\./', $local)) {
          return validated(FALSE, 'The local part of the address has two dots in a row.', $obj);
        } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
          return validated(FALSE, 'The domain part of the address contains invalid characters.', $obj);
        } else if (preg_match('/\\.\\./', $domain)) {
          return validated(FALSE, 'The domain part of the address has two dots in a row.', $obj);
        } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
          if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
            return validated(FALSE, 'The local part of the address contains invalid characters.', $obj);
          }
        }
        if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
          return validated(FALSE, 'It seems that the domain doesn\'t exist.', $obj);
        }
      }
      return validated(TRUE, 'The address has been validated.', $obj);
    }

    public static function validateUsername($username, $obj = FALSE) {
      if (!preg_match('/^[a-zA-Z0-9\-_]{3,32}$/', $username)) {
        return validated(FALSE, 'Username must be at least three characters and can only include a-Z, A-Z, 0-9, dashes and underscores.', $obj);
      } else {
        $person = person('username', $username);
        $currentPerson = person('current');
        if ($person && $currentPerson && $currentPerson->id == $person->id) {
          return validated(TRUE, 'Username is already yours, you didn\'t change it.', $obj);
        } else if ($person) {
          return validated(FALSE, 'Username is already taken.', $obj);
        } else if (config::$login->ValidateUsername($username)) {
          return validated(TRUE, 'Username is up for grabs.', $obj);
        } else {
          return validated(FALSE, 'Username not accepted by system', $obj);
        }
      }
    }
    
    public static function validatePassword($password, $obj = FALSE) {
      if (preg_match('/^(?=.*\d)(?=.*[A-Za-z])(?=.*[!@#$])[0-9A-Za-z!@#$]{6,50}$/', $password)) {
        if (ulPassword::IsValid($password)) {
          return validated(TRUE, 'Password is valid', $obj);
        } else {
          return validated(FALSE, 'Password not accepted by system', $obj);
        }
      } else {
        return validated(FALSE, 'Password is required to be at least 6 characters, including a number, a letter and one of !@#$', $obj);
      }
    }
    
  }

?>