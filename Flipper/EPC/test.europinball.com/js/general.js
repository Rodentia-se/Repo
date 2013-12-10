function ucfirst(txt) {
  return txt.toString().substring(0, 1).toUpperCase() + txt.toString().substring(1); // Why is this not a native part of Javascript? 
}

function showMsg(txt) {
  $("#mainContent").tooltipster("update", txt).tooltipster("show");
  return true;
}

Number.prototype.toMoney = function(decimals, decimal_sep, thousands_sep, symbol, symbol_at_end) { 
  var n = this,
  c = isNaN(decimals) ? 2 : Math.abs(decimals), //if decimal is zero we must take it, it means user does not want to show any decimal
  d = decimal_sep || '.', //if no decimal separator is passed we use the dot as default decimal separator (we MUST use a decimal separator)
  t = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep, //if you don't want to use a thousands separator you can pass empty string as thousands_sep value
  s = (typeof symbol === 'undefined') ? '$ ' : symbol, //if you don't want to use a symbol separator you can pass empty string as symbol value
  sign = (n < 0) ? '-' : '',
  //extracting the absolute value of the integer part of the number and converting to string
  i = parseInt(n = Math.abs(n).toFixed(c)) + '',
  j = ((j = i.length) > 3) ? j % 3 : 0;
  return sign + ((!s || symbol_at_end) ? '' : s) + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '') + ((s && symbol_at_end) ? symbol : ''); 
}