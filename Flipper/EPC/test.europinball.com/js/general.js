function ucfirst(txt) {
  return txt.toString().substring(0, 1).toUpperCase() + txt.toString().substring(1); // Why is this not a native part of Javascript? 
}

function showMsg(txt) {
  $("#mainContent").tooltipster("update", txt).tooltipster("show");
  return true;
}