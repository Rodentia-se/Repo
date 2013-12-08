function ucfirst(txt) {
  return txt.toString().substring(0, 1).toUpperCase() + txt.toString().substring(1); // Why is this not a native part of Javascript? 
}

var tooltips = [];

function showTooltip(el, text, offset) {
  alert(el.id);
  var offset = (offset) ? offset : 15;
  if (typeof tooltips[el.id] != "undefined") {
    clearTimeout(tooltips[el.id]);
  }
  $(el).tooltip({
    content: text,
    position: {
      my: "left+" + offset + " center",
      at: "right center"
    }
  })
  .on("mouseover mouseleave focusin focusout", function(event) {
    event.stopImmediatePropagation();
  })
  .tooltip("enable")
  .tooltip("open");
  tooltips[el.id] = setTimeout(function(){
    $(el).tooltip("close")
    .tooltip("disable");
  }, 3000);
}
