<?php

  class person extends base {
   
    public static $instances;
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

    // @todo: Fix children
/*
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
*/

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
        $this->hereVol = $this->volunteer->here;
        $this->hours = $this->volunteer->hours;
        $this->alloc = $this->volunteer->alloc;
        $this->hoursDiff = $this->volunteer->hoursDiff;
        $adminLevels = adminLevels('all');
        foreach ($adminLevels as $adminLevel) {
          $adminLevelName = $adminLevel->name;
          $this->$adminLevelName = $this->volunteer->$adminLevelName;
        }
      } else {
        $this->adminLevel_id = 1;
        $this->adminLevel = adminLevel(1);
        $this->player = 1;
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
    
    public function addVolunteer($tournament = NULL, $hours = NULL, $size = NULL, $adminLevel = NULL) {
      $tournament = getTournament($tournament);
      $size = size($size);
      $adminLevel = adminLevel($adminLevel);
      $volunteer = volunteer($this, $tournament);
      if (!$volunteer) {
        $volunteer = volunteer((array) $this->getFlat(), config::NOSEARCH, 0);
        unset($volunteer->id);
        $volunteer->tournamentEdition_id = $tournament->id;
        $volunteer->person_id = $this->id;
        $volunteer->dateRegistered = date('Y-m-d');
        $volunteer->hours = $hours;
        $volunteer->size_id = ($size) ? $size->id : NULL;
        $volunteer->size = ($size) ? $size->name : NULL;
        $volunteer->adminLevel_id = ($adminLevel) ? $adminLevel->id : NULL;
        $id = $volunteer->save();
        if (isId($id)) {
          $volunteer = volunteer($id);
          if ($volunteer) {
            return $id;
          }
        }
      }
      return false;
    }

    public function getEdit($type = 'edit', $title = NULL, $tournament = NULL, $prefix = NULL) {
      $tournament = getTournament($tournament);
      switch ($type) {
        case 'payment':
        case 'payments':
          $paymentsDiv = new div($prefix.'PaymentsDiv');
            if ($title) {
              $paymentsDiv->addH2('Payment options', array('class' => 'entry-title'));
            }
            $paymentsPerson = $paymentsDiv->addHidden('paymentsPerson_id', $this->id);
            $gotoProfileP = $paymentsDiv->addParagraph('The numbers below are derived from your division registrations and T-shirt orders. You can change things in the ');
/*
              $gotoProfileBtn = $gotoProfileP->addClickButton('Profile editor', NULL, NULL, FALSE, '$("#profiletabLink").click();');
              $gotoProfileP->addContent(' or ');
              */
              $gotoTshirtBtn = $gotoProfileP->addClickButton('T-shirt orders', NULL, NULL, FALSE, '$("#tshirtstabLink").click();');
              $gotoProfileP->addContent(' tab (to also order T-shirt sizes), or you can just change the numbers here before paying (you will need to order T-shirt sizes before ');
              $gotoProfileP->addSpan('January 15th', NULL, 'bold');
              $gotoProfileP->addContent(').');
            //}
            $curDiv = $paymentsDiv->addDiv($prefix.'paymentsCurrencyDiv');
              $currencyChooser = $curDiv->addContent(getCurrencySelect($prefix.'Payments', ((config::$tshirts) ? FALSE : TRUE)));
            //}
            $divisions = divisions($tournament);
            foreach ($divisions as $division) {
              if (property_exists('config', $division->type.'Cost') && config::${$division->type.'Cost'}) {
                $divisionDiv = $paymentsDiv->addDiv($division->id.'_CostDiv');
                  $cost = $this->getCost($division);
                  $spinnerParams = array(
                    'class' => 'paymentsSpinner enterChange',
                    'data-division_id' => $division->id,
                    'data-eachcost' => $cost
                  );
                  $player = player($this, $division);
                  $divisionNum = ($player) ? 1 : 0;
                  $spinner = $divisionDiv->addSpinner($prefix.'Payments_'.$division->id, $divisionNum, 'text', ucfirst($division->type), $spinnerParams);
                    $moneySpan = $divisionDiv->addMoneySpan($spinner->value * $spinner->{'data-eachcost'}, $spinner->id.'_moneySpan', config::$currencies[$defaultCurrency]['format'], array('class' => 'payments'));
                    $costs += $spinner->value * $spinner->{'data-eachcost'};
                    $num += $spinner->value;
                  //}
                //}
              }
            }
            $tshirtDiv = $paymentsDiv->addDiv($prefix.'PaymentsTshirtsDiv');
              $tshirtOrders = tshirtOrders($this, $tournament);
              $tshirtNum = 0;
              foreach ($tshirtOrders as $tshirtOrder) {
                $tshirtNum += $tshirtOrder->number;
              }
              $spinnerParams = array(
                'class' => 'numOfTshirts paymentsSpinner enterChange',
                'data-eachcost' => config::$tshirtCost
              );
              $spinner = $tshirtDiv->addSpinner($prefix.'PaymentsTshirts', $tshirtNum, 'text', 'T-shirts', $spinnerParams);
                $moneySpan = $tshirtDiv->addMoneySpan($spinner->value * $spinner->{'data-eachcost'}, $spinner->id.'_moneySpan', config::$currencies[$defaultCurrency]['format'], array('class' => 'payments'));
                $costs += $spinner->value * $spinner->{'data-eachcost'};
              //}
            //}
            $paymentsDiv->addChange('
              var el = this;
              var number = $(el).val();
              var each = $(el).data("eachcost");
              $("#" + el.id + "_moneySpanAmount").html((+ number * each));
              var cost = 0;
              var num = 0;
              $(".paymentsSpinner").each(function() {
                cost += (+ $("#" + this.id + "_moneySpanAmount").html());
              });
              $("#'.$prefix.'PaymentsSubTotalDivMoneySpanAmount").html(cost);
              var toPay = cost - (+ $("#PaymentsPaidDivMoneySpanAmount").html() * -1);
              $("#PaidTooMuchAmount").html((+ toPay * -1));
              if (toPay > 0) {
                $(".paidTooMuch").hide();
                $(".paidAll").hide();
                $("#PaymentsTotalDivMoneySpanAmount").html(Math.ceil(toPay));
                $("#payPalImg").prop("disabled", false).prop("title", "Click to pay " + $("#PaymentsTotalDivMoneySpan").html() + "!").prop("alt", "Click to pay " + $("#PaymentsTotalDivMoneySpan").html() + "!");
                $("#TshirtsOrderMore").hide();
              } else if (toPay == 0) {
                $(".paidTooMuch").hide();
                $(".paidAll").show();
                $("#PaymentsTotalDivMoneySpanAmount").html(0);
                $("#payPalImg").prop("disabled", true).prop("title", "Nothing to pay!").prop("alt", "Nothing to pay!");
              } else {
                $(".paidTooMuch").show();
                $(".paidAll").hide();
                $("#PaymentsTotalDivMoneySpanAmount").html(0);
                $("#payPalImg").prop("disabled", true).prop("title", "Nothing to pay!").prop("alt", "Nothing to pay!");
              }
              var orderMoreNum = ($("#PaidTooMuchAmount").html() > 0) ? Math.floor($("#PaidTooMuchAmount").html() / '.config::$tshirtCost.') : 0;
              orderMoreNum = ($("#PaymentsTshirts").val() > $("#tshirtsNumOfTshirts").val()) ? $("#PaymentsTshirts").val() - $("#tshirtsNumOfTshirts").val() : orderMoreNum - ($("#tshirtsNumOfTshirts").val() - $("#PaymentsTshirts").val());
              $("#TshirtsOrderMoreNum").html(orderMoreNum);
              if (orderMoreNum) {
                $("#TshirtsOrderMore").show();
              } else {
                $("#TshirtsOrderMore").hide();
              }
              $("#payPalMsg").val("ID: " + $("#paymentsPerson_id").val() + ", Main: " + $("#Payments_15").val() + ", T-shirts: " + $("#PaymentsTshirts").val());
              $("#'.$currencyChooser->id.'").change();
            ', '.paymentsSpinner');
            $toPay = ($costs - $this->paid > 0) ? $costs - $this->paid : 0;
            $subTotalDiv = $paymentsDiv->addDiv($prefix.'PaymentsSubTotalDiv');
              $subTotalDiv->addLabel(' ');
              $subTotalDiv->addSpan(' ', NULL, 'short');
              $subTotalDiv->addMoneySpan($costs, NULL, config::$currencies[$defaultCurrency]['format'], array('class' => 'sum payments'));
            //}
            $paidDiv = $paymentsDiv->addDiv($prefix.'PaymentsPaidDiv');
              $paidDiv->addLabel(' ');
              $paidDiv->addLabel('Already paid:', NULL, NULL, 'short');
              $paidDiv->addMoneySpan($this->paid * -1, NULL, config::$currencies[$defaultCurrency]['format'], array('class' => 'payments'));
              $paidDiv->addSpan(' You have already paid everything.', $prefix.'PaidAll', (($costs - $this->paid == 0) ? 'paidAll' : 'hidden paidAll'));
              $paidDiv->addSpan(' You have already paid ', $prefix.'PaidTooMuchPrefix', (($costs - $this->paid < 0) ? 'paidTooMuch' : 'hidden paidTooMuch'));
              $paidDiv->addMoneySpan((+ ($costs - $this->paid) * -1), $prefix.'PaidTooMuch', config::$currencies[$defaultCurrency]['format'], array('class' => (($costs - $this->paid < 0) ? 'paidTooMuch' : 'hidden paidTooMuch')));
              $paidDiv->addSpan(' too much.', $prefix.'PaidTooMuchSuffix', (($costs - $this->paid < 0) ? 'paidTooMuch' : 'hidden paidTooMuch'));
            //}
            $totalDiv = $paymentsDiv->addDiv($prefix.'PaymentsTotalDiv');
              $totalDiv->addLabel(' ');
              $totalDiv->addLabel('To pay:', NULL, NULL, 'short');
              $totalDiv->addMoneySpan($toPay, NULL, config::$currencies[$defaultCurrency]['format'], array('class' => 'sum payments'));
            //}
          //}
          $paymentsDiv->addScriptCode('
            $(document).ready(function() {
              $("#PaymentsTshirts").change();
            });
          ');
          $paymentsDiv->addParagraph('If you wish to pay for anyone other than the player logged in, just change the numbers above before you pay, and please include that information in the payment message. There is no fee for the eighties division.', NULL, 'italic');
          return $paymentsDiv;
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
                $spinnerClass = 'tshirtSpinner';
                $spinnerParams = array(
                  'class' => $spinnerClass.' enterChange',
                  'data-tshirt_id' => $tshirt->id,
                  'data-tshirtorder_id' => (($tshirtOrder) ? $tshirtOrder->id : 0),
                  'data-eachcost' => config::$tshirtCost
                );
                $spinner = $tshirtDiv->addSpinner($prefix.'TshirtOrder_'.$tshirt->id, (($tshirtOrder) ? $tshirtOrder->number : 0), 'text', $tshirt->name, $spinnerParams);
                  $moneySpan = $tshirtDiv->addMoneySpan($spinner->value * $spinner->{'data-eachcost'}, $spinner->id.'_moneySpan', config::$currencies[$defaultCurrency]['format'], array('class' => 'payments'));
                  $costs += $spinner->value * $spinner->{'data-eachcost'};
                  $num += $spinner->value;
                  $spinner->addTooltip('');
                //}
              //}
            }
            $subTotalDiv = $orderDiv->addDiv($prefix.'tshirtsSubTotalDiv');
              $subTotalDiv->addInput($prefix.'tshirtsNumOfTshirts', $num, 'text', 'Total', array('disabled' => TRUE, 'class' => 'short numOfTshirts'));
              $subTotalDiv->addMoneySpan($costs, NULL, config::$currencies[$defaultCurrency]['format'], array('class' => 'sum payments'));
              $subTotalDiv->style = 'margin-bottom: 15px;';
            //}
            $toBuyFor = $this->paid - $this->getCost();
            $orderMoreNum = ($toBuyFor > 0) ? floor($toBuyFor / config::$tshirtCost) : 0;
            $orderMoreDiv = $orderDiv->addDiv($prefix.'tshirtsOrderMore');
              $orderMoreP = $orderMoreDiv->addParagraph('You have already paid (or are going to pay) enough to order ', $prefix.'TshirtsOrderMore', (($orderMoreNum > 0) ? '' : 'hidden'));
              $orderMoreP->addSpan($orderMoreNum, $prefix.'TshirtsOrderMoreNum');
              $orderMoreP->addContent(' more T-shirts.');
            $goToPaymentsDiv = $orderDiv->addDiv('goToPaymentsDiv');
              $goToPaymentsP = $goToPaymentsDiv->addParagraph('Go to the ');
                $gotoPaymentsBtn = $goToPaymentsP->addClickButton('payments tab', NULL, NULL, FALSE, '$("#paymentstabLink").click();');
                $goToPaymentsP->addContent(' to pay or check payment status.');
              //}
            //}
            $orderDiv->addParagraph('Note that changing anything above will be reflected in the T-shirts field on the payments tab.', NULL, 'italic');
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
                $(".'.$spinnerClass.'").each(function() {
                  cost += parseInt($("#" + this.id + "_moneySpanAmount").html());
                  num += parseInt($(this).val());
                });
                $("#'.$subTotalDiv->id.'MoneySpanAmount").html(cost);
                $("#'.$prefix.'PaymentsTshirtsDivMoneySpanAmount").html(cost);
                $(".numOfTshirts").val(num);
                $("#PaymentsTshirts").change();
              });
            ', '.'.$spinnerClass);
          //}
          $tshirtsDiv->addImg(config::$baseHref.'/images/objects/tshirt/2014.jpg', NULL, array('class' => 'rightHalf'));
          return $tshirtsDiv;
        break;
        case 'photo':
          return $this->getPhotoEdit($prefix);
        break;
        case 'user':
        case 'users':
          $this->getVolunteer($tournament);
          $usersDiv = new div($prefix.'usersDiv');
            $userNameDiv = $usersDiv->addDiv($prefix.'usersUsermameDiv', 'noInput');
              $userNameDiv->addLabel('Username');
              $userNameDiv->addSpan($this->username);
            //$userNameDiv
            $passwordDiv = $usersDiv->addDiv($prefix.'usersPasswordDiv');
              $newPassword = $passwordDiv->addInput('password', NULL, 'password', 'Set password');
              $setPasswordButton = $passwordDiv->addButton('Set password');
              $setPasswordButton->addTooltip('');
              $setPasswordButton->addClick('
                var el = this;
                if ($(el).val().length < 7) {
                  $(el).tooltipster("update", "Please use at least 6 characters").tooltipster("show");
                } else {
                  if (confirm("You are about to change the password for '.$this->name.'. Is this what you want to do?")) {
                    $(el).tooltipster("update", "Setting password...").tooltipster("show");
                    $("body").addClass("modal");
                    $.post("'.config::$baseHref.'/ajax/setPersonProp.php", {person_id: '.$this->id.', prop: "password", value: $("#'.$newPassword->id.'").val()})
                    .done(function(data) {
                      $(el).tooltipster("update", data.reason).tooltipster("show");
                      $("body").removeClass("modal");
                      if (data.valid) {
                        $("#'.$newPassword->id.'").val("");
                      }
                    });
                  } else {
                    $(el).tooltipster("update", "Password not changed").tooltipster("show");
                  }
                }
              ');
            //$passwordDiv
            $adminLevelDiv = $usersDiv->addDiv($prefix.'usersAdminLevelDiv');
              $adminLevels = adminLevels('all');
              $adminLevelSelect = $adminLevelDiv->addContent($adminLevels->getSelectObj($prefix.'usersAdminLevel', $this->adminLevel_id, 'Administrator level'));
                $adminLevelSelect->addCombobox();
                $adminLevelSelect->addTooltip('', TRUE, '#'.$adminLevelSelect->id.'_combobox')->offsetX = 38;
                $adminLevelSelect->addChange('
                  var el = this;
                  if ($(el).val() != 0) {
                    $("#" + el.id + "_combobox").tooltipster("update", "Changing administrator level...").tooltipster("show");
                    $("body").addClass("modal");
                    $.post("'.config::$baseHref.'/ajax/setPersonProp.php", {person_id: '.$this->id.', prop: "adminLevel", value: $(el).val()})
                    .done(function(data) {
                      if (data.valid) {
                        $(el).data("previous", $(el).val());
                      } else {
                        $(el).val($(el).data("previous"));
                      }
                      $("body").removeClass("modal");
                      $("#" + el.id + "_combobox").tooltipster("update", data.reason).tooltipster("show");
                      $("#" + el.id + "_combobox").val($(el).children(":selected").text());
                    });
                  }
                ');
              //$adminLevelSelectpa
            //$adminLevelDiv
          //$usersDiv
          return $usersDiv;
        break;
        case 'profile':
        case 'edit':
        case 'player':
        case 'person':
        default:
          $comboboxClass = 'combobox';
          $editClass = 'edit';
          $dateClass = 'date';
          $profileDiv = new div($prefix.'editDiv');
            $profileDiv->addH2((($title) ? $title : 'Edit profile'), array('class' => 'entry-title'));
            $profileDiv->addParagraph('Note: All changes below are INSTANT when you press enter or move away from the field.', NULL, 'italic');
            if ($player->waiting) {
              $profileDiv->addParagraph($this->name.' is on the WAITING LIST for this tournament, and we will be contacted if a participation sport becomes available (make sure the contact information below is correct).');
            }
            $fields = array(
              'firstName' => 'First name',
              'lastName' => 'Last name',
              'shortName' => 'Tag',
              'streetAddress' => 'Address',
              'zipCode' => 'ZIP',
              'telephoneNumber' => 'Phone',
              'mobileNumber' => 'Cell phone',
              'mailAddress' => 'Emil',
              'birthDate' => 'Born',
            );
            foreach ($fields as $field => $label) {
              $editDivs[$field] = new div($prefix.$field.'Div');
                $editDivs[$field]->addInput($field, $this->$field, 'text', $label, array('id' => $prefix.$field, 'class' => (($field == 'birthDate') ? $dateClass.' ' : '').$editClass));
              //}
            }
            $profileDiv->addContent($editDivs['firstName']);
            $profileDiv->addContent($editDivs['lastName']);
            $profileDiv->addContent($editDivs['shortName']);
            $div = $profileDiv->addDiv($prefix.'gender_idDiv');
              $sel = $div->addContent(genders('all')->getSelectObj('gender_id', $this->gender_id, 'Gender', array('class' => $comboboxClass)));
              $sel->id = $prefix.'gender_id';
            //}
            $profileDiv->addContent($editDivs['streetAddress']);
            $profileDiv->addContent($editDivs['zipCode']);
            foreach (array('city' => 'cities', 'region' => 'regions') as $geo => $geoArr) {
              $sel = $div->addDoublebox($geoArr('all')->getSelectObj($geo.'_id', $this->{$geo.'_id'}, ucfirst($geo), array('class' => $comboboxClass)), FALSE, $geo);
              //}
            }
            foreach (array('country' => 'countries', 'continent' => 'continents') as $geo => $geoArr) {
              $div = $profileDiv->addDiv($prefix.$geo.'_idDiv');
                $sel = $div->addContent($geoArr('all')->getSelectObj($geo.'_id', $this->{$geo.'_id'}, ucfirst($geo), array('class' => $comboboxClass)));
              //}
            } 
            $profileDiv->addContent($editDivs['telephoneNumber']);
            $profileDiv->addContent($editDivs['mobileNumber']);
            $profileDiv->addContent($editDivs['mailAddress']);
            $div = $profileDiv->addDiv($prefix.'divisionsDiv', 'divisionsDiv');
              $div->addLabel('Divisions', NULL, NULL, 'normal');
              foreach (config::$activeSingleDivisions as $divisionType) {
                $player = ($this->id) ? player($this, $divisionType) : NULL;
                $box[$divisionType] = $div->addCheckbox($divisionType, ($player), array('id' => $prefix.$divisionType, 'class' => $editClass));
              }
            //}
            $box['main']->disabled = TRUE;
            $box['main']->checked = TRUE;
            $profileDiv->addContent($editDivs['birthDate']);
            $profileDiv->addScriptCode('
              $(".'.$comboboxClass.'").combobox()
              .change(function(){
                var el = this;
                var combobox = document.getElementById(el.id + "_combobox");
                $(combobox).tooltipster("update", "Updating the database...").tooltipster("show");
                $.post("'.config::$baseHref.'/ajax/setPersonProp.php", {prop: el.id, value: $(el).val()})
                .done(function(data) {
                  $(combobox).tooltipster("update", data.reason).tooltipster("show");
                  if (data.valid) {
                    $(combobox).val($(el).children(":selected").text())
                    if (data.parents) {
                      $.each(data.parents, function(key, geo) {
                        if (!stop) {
                          if (data.parent_obj == geo) {
                            if (data.parent_id != $("#" + geo + "_id").val()) {
                              $("#'.$prefix.'" + geo + "_id").val(data.parent_id);
                              $("#'.$prefix.'" + geo + "_id").change();
                            }
                            var stop = true;
                          } else if ($("#'.$prefix.'" + geo + "_id").val() != 0) {
                            $("#'.$prefix.'" + geo + "_id").val(0);
                            $("#'.$prefix.'" + geo + "_id_combobox").val("");
                          }
                        }
                      });
                    }
                    $(el).data("previous", $(el).val());
                  } else {
                    $(el).val($(el).data("previous"));
                  }
                })
                .fail(function(jqHXR,status,error) {
                  $(combobox).tooltipster("update", "Fail: S: " + status + " E: " + error).tooltipster("show");
                })
              });
              $(".custom-combobox-input").tooltipster({
                theme: ".tooltipster-light",
                content: "Updating the database...",
                trigger: "custom",
                position: "right",
                offsetX: 38,
                timer: 3000
              });
              $(".'.$editClass.'").change(function(){
                var el = this;
                if (el.id == "'.$prefix.'shortName") {
                  $(el).val($(el).val().toUpperCase());
                } 
                var value = ($(el).is(":checkbox")) ? ((el.checked) ? 1 : 0) : $(el).val();
                var region_id = (this.id == "'.$prefix.'city") ? $("#'.$prefix.'region_id").val() : null;
                var country_id = (this.id == "'.$prefix.'city" || this.id == "'.$prefix.'region") ? $("#'.$prefix.'country_id").val() : null;
                var continent_id = (this.id == "'.$prefix.'city" || this.id == "'.$prefix.'region") ? $("#'.$prefix.'continent_id").val() : null;
                $(el).tooltipster("update", "Updating the database...").tooltipster("show");
                $.post("'.config::$baseHref.'/ajax/setPersonProp.php", {prop: el.id, value: value, region_id: region_id, country_id: country_id, continent_id: continent_id})
                .done(function(data) {
                  $(el).tooltipster("update", data.reason).tooltipster("show");
                  if (data.valid) {
                    $(el).data("previous", (($(el).is(":checkbox")) ? ((el.checked) ? 1 : 0) : $(el).val()));
                  } else {
                    if ($(el).is(":checkbox")) {
                      el.checked = ($(el).data("previous"));
                    } else {
                      $(el).val($(el).data("previous"));
                    }
                  }
                })
                .fail(function(jqHXR,status,error) {
                  $(el).tooltipster("update", "Fail: S: " + status + " E: " + error).tooltipster("show");
                })
              })
              .tooltipster({
                theme: ".tooltipster-light",
                content: "Updating the database...",
                position: "right",
                trigger: "custom",
                timer: 3000
              });
              $(".'.$dateClass.'").datepicker({
                dateFormat: "yy-mm-dd",
                yearRange: "-100:-0",
                defaultDate: "-30y",
                changeYear: true,
                showWeek: true,
                changeMonth: true 
              });
            ');
          //}
          return $profileDiv;
        break;
      }
    }
    
    public function getRegSearch() {
      return array(
        $this->name,
        $this->shortName,
        $this->cityName,
        $this->countryName,
        $this->getLink('ifpa'),
        $this->getPhotoIcon(),
        '<form id="'.$this->id.'_isMeForm" method="POST"><input type="hidden" name="register" value="isMe"><input type="hidden" name="person_id" value="'.$this->id.'"><input type="button" id="'.$this->id.'_isMe" class="isMe" value="This is me!"></form>'
      );
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
    
    public function setProp($prop, $value = NULL) {
      if (in_array($prop, array('password', 'adminLevel'))) {
        return $this->{'set'.ucfirst($prop)}($value);
      } else {
        return parent::setProp($prop, $value);
      }
    }
 
    public function setPassword($password) {
      if ($this->username) {
        if ($password) {
          return config::$login->SetPassword($this->getUid(), $password);
        } else {
          error('No password provided.');
        }
      } else {
        error('This person has no user.');
      }
      return FALSE;
    }
    
    public function setAdminLevel($adminLevel = 1) {
      $adminLevel = adminLevel($adminLevel);
      if (!isAdminLevel($adminLevel)) {
        $adminLevel = adminLevel(1);
      }
      $tournament = getTournament();
      $volunteer = volunteer($this, $tournament);
      if (!isVolunteer($volunteer)) {
        $volunteer = $this->addVolunteer($tournament);
      }
      if (isVolunteer($volunteer)) {
        return $volunteer->setProp('adminLevel_id', $adminLevel->id);
      } else {
        error('Could not add '.$this->name.' as volunteer.');
      }
      return FALSE;
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