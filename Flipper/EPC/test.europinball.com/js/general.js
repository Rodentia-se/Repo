var dataStore = window.sessionStorage;

function ucfirst(txt) {
  return txt.toString().substring(0, 1).toUpperCase() + txt.toString().substring(1); // Why is this not a native part of Javascript? 
}

function showMsg(txt) {
  $("#mainContent").tooltipster("update", txt).tooltipster("show");
  return true;
}

Number.prototype.toMoney = function(decimals, decimal_sep, thousands_sep, symbol, format) { 
  var n = this,
  c = isNaN(decimals) ? 2 : Math.abs(decimals), //if decimal is zero we must take it, it means user does not want to show any decimal
  d = decimal_sep || '.', //if no decimal separator is passed we use the dot as default decimal separator (we MUST use a decimal separator)
  t = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep, //if you don't want to use a thousands separator you can pass empty string as thousands_sep value
  s = (typeof symbol === 'undefined') ? '$' : symbol, //if you don't want to use a symbol separator you can pass empty string as symbol
  f = (format) ? format : ((symbol) ? symbol + ' §' : '§'), //If format is supplied as a string with an § in it, the § will be replaced with the number and the symbol parameter will not be used
  sign = (n < 0) ? '-' : '',
  //extracting the absolute value of the integer part of the number and converting to string
  i = parseInt(n = Math.abs(n).toFixed(c)) + '',
  j = ((j = i.length) > 3) ? j % 3 : 0;
  var num = sign + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '');
  return f.replace(/§/, num);
}