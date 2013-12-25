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
            dataStore.setItem("curVal", $(this).val());
            $(".currencyChooser").val($(this).val());
            $(".curCode").val($("#currency_" + $(this).val()).val());
            $(".curCodeSpan").html($("#currency_" + $(this).val()).val());
            var format = $("#currency_" + $(this).val()).data("format")
            $(".moneySpan").each(function() {
              $(this).html(parseInt($(this).html().replace(/[^0-9]/g, "")).toMoney(0, ".", " ", "", format));
            });
          })
          .first().change();
        });
      ');
    return $select;
  }
