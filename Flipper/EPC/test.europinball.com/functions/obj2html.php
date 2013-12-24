<?php

  function getCurrencySelect($prefix = NULL, array $currencies = NULL, $defaultCurrency = NULL) {
    $currencies = ($currencies) ? $currencies : config::$acceptedCurrencies;
    $defaultCurrency = ($defaultCurrency) ? $defaultCurrency : config::$defaultCurrency;
    $currencyProps = config::$currencies;
    $prefix = ($prefix) ? html::newId();
    $select = new comboBox($prefix.'Currency', $currencies, $defaultCurrency, 'Currency');
    return select;
  }

?>