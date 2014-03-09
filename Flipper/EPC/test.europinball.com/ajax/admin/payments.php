        $prefix = 'payments';
        ${$prefix.'Div'} = $tabs->addDiv($prefix.'Div');
          ${$prefix.'Div'}->data_title = ucfirst($prefix);
          ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
          ${$prefix.'NumDiv'} = ${$prefix.'Div'}->addDiv();
            ${$prefix.'NumDiv'}->addLabel('Paid registrations');
            ${$prefix.'Num'} = $persons->getNumOf('paid');
            ${$prefix.'NumDiv'}->addSpan(${$prefix.'Num'}.' players');
          //Num
          $paymentLevels = $persons->getListOf('paid');
          sort($paymentLevels);
          foreach ($paymentLevels as $paymentLevel) {
            ${$prefix.$paymentLevel.'NumDiv'} = ${$prefix.'Div'}->addDiv();
              ${$prefix.$paymentLevel.'NumDiv'}->addLabel('Paid € '.$paymentLevel);
              ${$prefix.$paymentLevel.'Num'} = $persons->getNumOf('paid', $paymentLevel);
              ${$prefix.$paymentLevel.'NumDiv'}->addSpan(${$prefix.$paymentLevel.'Num'}.' players');
            //30Num
          }
          ${$prefix.'SumDiv'} = ${$prefix.'Div'}->addDiv();
            ${$prefix.'SumDiv'}->addLabel('Total payments');
            ${$prefix.'Sum'} = $persons->getSumOf('paid');
            ${$prefix.'SumDiv'}->addSpan('€ '.${$prefix.'Sum'});
          //Sum
          ${$prefix.'Div'}->addH2('Change payment', array('class' => 'entry-title'));
          ${$prefix.'SelectDiv'} = ${$prefix.'Div'}->addDiv();
            ${$prefix.'Select'} = ${$prefix.'SelectDiv'}->addContent($persons->getSelectObj($prefix.'Persons', NULL, 'Persons'));
              ${$prefix.'Select'}->addCombobox();
              ${$prefix.'SelectDiv'}->addFocus('#'.${$prefix.'Select'}->id.'_combobox', TRUE);
            //$paymentSelect
          //$paymentsSelectDiv
          $paidDiv = ${$prefix.'Div'}->addDiv('paidDiv', 'noInput');
            $paidDiv->addLabel('Paid:');
            $paidSpan = $paidDiv->addMoneySpan(0, 'paid', config::$currencies[config::$defaultCurrency]['format']);
          //$paidDiv
          $costsDiv = ${$prefix.'Div'}->addDiv('costsDiv', 'noInput');
            $costsDiv->addLabel('Should pay:');
            $costsSpan = $costsDiv->addMoneySpan(0, 'costs', config::$currencies[config::$defaultCurrency]['format']);
          //$costsDiv
          $payDiv = ${$prefix.'Div'}->addDiv('payDiv', 'noInput');
            $payDiv->addLabel('Left to pay:');
            $paySpan = $payDiv->addMoneySpan(0, 'pay', config::$currencies[config::$defaultCurrency]['format']);
            $paySpan->addClasses('sum');
          //$payDiv
          $setDiv = ${$prefix.'Div'}->addDiv();
            $setPaid = $setDiv->addInput('setPaid', 0, 'text', 'Set paid total', array('class' => 'short'));
            $setPaid->disabled = TRUE;
            $setPaid->addChange('
              var el = this;
              $("body").addClass("modal");
              $.post("'.config::$baseHref.'/ajax/setPersonProp.php", {person_id: $("#'.${$prefix.'Select'}->id.'").val(), prop: "paid", value: $(el).val()})
              .done(function(data) {
                if (data.valid) {
                  $("#'.${$prefix.'Select'}->id.'").change();
                  setTimeout(function() {
                    $("#'.${$prefix.'Select'}->id.'_combobox").focus().select()
                  }, 500);
                } else {
                  showMsg(data.reason);
                  $("body").removeClass("modal");
                }
              });
            ');
          //$setDiv
          ${$prefix.'Select'}->addChange('
            $("body").addClass("modal");
            var modals = 3;
            $.post("'.config::$baseHref.'/ajax/getProp.php", {class: "person", id: $(this).val(), prop: "paid"})
            .done(function(data) {
              modals--;
              if (modals == 0) {
                $("body").removeClass("modal");
              }
              if (data.valid) {
                $("#'.$paidSpan->id.'").html(parseInt(data.reason).toMoney(0, ".", " ", "", "'.config::$currencies[config::$defaultCurrency]['format'].'"));
                $("#'.$setPaid->id.'").val(data.reason).focus().select().prop("disabled", false);
              } else {
                showMsg(data.reason);
              }
            });
            $.post("'.config::$baseHref.'/ajax/getProp.php", {class: "person", id: $(this).val(), prop: "costs"})
            .done(function(data) {
              modals--;
              if (modals == 0) {
                $("body").removeClass("modal");
              }
              if (data.valid) {
                $("#'.$costsSpan->id.'").html(parseInt(data.reason).toMoney(0, ".", " ", "", "'.config::$currencies[config::$defaultCurrency]['format'].'"));
              } else {
                showMsg(data.reason);
              }
            });
            $.post("'.config::$baseHref.'/ajax/getProp.php", {class: "person", id: $(this).val(), prop: "toPay"})
            .done(function(data) {
              modals--;
              if (modals == 0) {
                $("body").removeClass("modal");
              }
              if (data.valid) {
                $("#'.$paySpan->id.'").html(parseInt(data.reason).toMoney(0, ".", " ", "", "'.config::$currencies[config::$defaultCurrency]['format'].'"));
              } else {
                showMsg(data.reason);
              }
            });
          ');
        //}
