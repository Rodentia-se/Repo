<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Admin tools');

  if ($page->reqLogin('You need to be logged in to access this page. If you are don\'t have a user, please go to the <a href="'.config::$baseHref.'/registration/">registration page</a>.')) {
    $volunteer = volunteer('login');
    if ($volunteer->admin) {
      $div = new div('paymentDiv');
      $loading = $div->addLoading();
      $persons = persons(tournament('active'));
      $select = $persons->getSelectObj();
      $select->addCombobox();
      $div->addContent($select);
      $div->addFocus('#persons_combobox', TRUE);
      $paidDiv = $div->addDiv('paidDiv', 'noInput');
      $paidDiv->addLabel('Paid:');
      $paidSpan = $paidDiv->addMoneySpan(0, 'paid', config::$currencies[config::$defaultCurrency]['format']);
      $costsDiv = $div->addDiv('costsDiv');
      $costsDiv->addLabel('Should pay:');
      $costsSpan = $costsDiv->addMoneySpan(0, 'costs', config::$currencies[config::$defaultCurrency]['format']);
      $payDiv = $div->addDiv('payDiv');
      $payDiv->addLabel('Left to pay:');
      $paySpan = $payDiv->addMoneySpan(0, 'pay', config::$currencies[config::$defaultCurrency]['format']);
      $paySpan->addClasses('sum');
      $setDiv = $div->addDiv();
      $setPaid = $setDiv->addInput('setPaid', 0, 'text', 'Set paid total', array('class' => 'short'));
      $setPaid->disabled = TRUE;
      $select->addChange('
        $("body").addClass("modal");
        var num = 3;
        $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "person", id: $(this).val(), prop: "paid"})
        .done(function(data) {
          num--;
          if (num == 0) {
            $("body").removeClass("modal");
          }
          if (data.valid) {
            $("#'.$paidSpan->id.'").html(parseInt(data.reason).toMoney(0, ".", " ", "", "'.config::$currencies[config::$defaultCurrency]['format'].'"));
            $("#'.$setPaid->id.'").val(data.reason).focus().select().prop("disabled", false);
          } else {
            showMsg(data.reason);
          }
        })
        .fail(function(jqHXR,status,error) {
          showMsg("Fail: S: " + status + " E: " + error);
        });
        $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "person", id: $(this).val(), prop: "costs"})
        .done(function(data) {
          num--;
          if (num == 0) {
            $("body").removeClass("modal");
          }
          if (data.valid) {
            $("#'.$costsSpan->id.'").html(parseInt(data.reason).toMoney(0, ".", " ", "", "'.config::$currencies[config::$defaultCurrency]['format'].'"));
          } else {
            showMsg(data.reason);
          }
        })
        .fail(function(jqHXR,status,error) {
          showMsg("Fail: S: " + status + " E: " + error);
        });
        $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "person", id: $(this).val(), prop: "toPay"})
        .done(function(data) {
          num--;
          if (num == 0) {
            $("body").removeClass("modal");
          }
          if (data.valid) {
            $("#'.$paySpan->id.'").html(parseInt(data.reason).toMoney(0, ".", " ", "", "'.config::$currencies[config::$defaultCurrency]['format'].'"));
          } else {
            showMsg(data.reason);
          }
        })
        .fail(function(jqHXR,status,error) {
          showMsg("Fail: S: " + status + " E: " + error);
        });
      ');
      $setPaid->addChange('
        var el = this;
        $("body").addClass("modal");
        $.post("'.config::$baseHref.'/ajax/setPersonProp.php", {person_id: $("#'.$select->id.'").val(), prop: "paid", value: $(el).val()})
        .done(function(data) {
          if (data.valid) {
            $("#'.$select->id.'").change();
          } else {
            showMsg(data.reason);
          }
        })
        .fail(function(jqHXR,status,error) {
          showMsg("Fail: S: " + status + " E: " + error);
        });
      ');
      $page->addContent($div);
    } else {
      $paragraph = new paragraph('You need to be an administrator to access this page. Please logout and log back in as administrator.');
      $page->addContent($paragraph);
    }

    $page->submit();
  }

?>