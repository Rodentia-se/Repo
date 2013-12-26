<?php

  function getCurrencySelect($prefix = NULL, $init = FALSE, array $currencies = NULL, $defaultCurrency = NULL) {
    $currencies = ($currencies) ? $currencies : config::$acceptedCurrencies;
    $defaultCurrency = ($defaultCurrency) ? $defaultCurrency : config::$defaultCurrency;
    $currencyProps = config::$currencies;
    $prefix = ($prefix) ? $prefix : html::newId();
    $select = new select($prefix.'Currency', $currencies, $defaultCurrency, 'Currency', array('class' => 'short currencyChooser'));
    $select->addCombobox();
    if ($init) {
      foreach($currencies as $key => $currency) {
        $inputParams = array(
          'data-rate' => config::$currencies[$currency]['rate'],
          'data-symbol' => config::$currencies[$currency]['symbol'],
          'data-format' => config::$currencies[$currency]['format']
        );
        $select->addAfter(new hidden('currency_'.$key, $currency, $inputParams));
      }
      $select->addAfter(new scriptCode('
        $(document).ready(function() {
          try {
            var curVal = dataStore.getItem("curVal");
          } catch(e) {
            var curVal = 0;
          };
          curVal = (parseInt(curVal)) ? parseInt(curVal) : 0;
          $(".currencyChooser").change(function() {
            var curVal = $(this).val();
            var currency = $("#currency_" + curVal).val().toUpperCase();
            dataStore.setItem("curVal", curVal);
            $(".currencyChooser").each(function() {
              $(this).val(curVal);
              $("#" + this.id + "_combobox").val(currency);
            });
            $(".currencySpan").html(currency);
            $(".currencyInput").val(currency);
            var format = $("#currency_" + $(this).val()).data("format");
            var rate = $("#currency_" + $(this).val()).data("rate");
            $(".moneySpan").each(function() { 
              $(this).html((+ parseInt($("#" + this.id + "Amount").html().replace(/[^0-9\-]/g, "")) * rate).toMoney(0, ".", " ", "", format));
            });
            $(".moneyInput").each(function() {
              $(this).val(parseInt($(this).val().replace(/[^0-9\-]/g, "") * rate).toMoney(0, ".", " ", "", format));
            });
            $("#payPalImg").attr("src", "'.config::$baseHref.'/images/paypal_" + currency +".gif");
            var toPay = $("#PaymentTotalDivMoneySpanAmount").html();
            if (toPay > 0) {
              $("#payPalImg").prop("disabled", false).prop("title", "Click to pay " + $("#PaymentTotalDivMoneySpan").html() + "!").prop("alt", "Click to pay " + $("#PaymentTotalDivMoneySpan").html() + "!");
              $("#payPalAmount").val(toPay * rate);
              $(".totalSpans").html(toPay * rate);
              $("#TshirtsOrderMore").hide();
            } else {
              $("#payPalImg").prop("disabled", true).prop("title", "Nothing to pay!").prop("alt", "Nothing to pay!");
              $("#payPalAmount").val(0);
              $(".totalSpans").html(0);
              var orderMoreNum = ($("#PaidTooMuchAmount").html() > 0) ? Math.floor($("#PaidTooMuchAmount").html() / '.config::$tshirtCost.') : 0;
              if (orderMoreNum) {
                $("#TshirtsOrderMoreNum").html(orderMoreNum);
                $("#TshirtsOrderMore").show();
              } else {
                $("#TshirtsOrderMore").hide();
              }
            }
         })
          .val(curVal).first().change();
        }); 
      '));
    }
    return $select;
  }

?>