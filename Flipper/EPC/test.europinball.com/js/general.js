function ucfirst(txt) {
  return txt.toString().substring(0, 1).toUpperCase() + txt.toString().substring(1); // Why is this not a native part of Javascript? 
}

var tooltips = [];

function showTooltip(el, text, offset) {
  alert(text);
  alert(el.id);
  var offset = (offset) ? offset : 15;
  if (typeof tooltips[el.id] != "undefined") {
    clearTimeout(tooltips[el.id]);
  }
  if ($(el).data("tooltipset")) {
    $(el).tooltip("option", "content", text);
    $(el).tooltip("open");
  } else {
    $(el).tooltip({
      content: text,
      position: {
        my: "left+" + offset + " center",
        at: "right center"
      }
    })
    .on("mouseout focusout", function(event) {
      event.stopImmediatePropagation();
    })
    .tooltip("open");
  }
  tooltips[el.id] = setTimeout(function(){
    $(el).tooltip("destroy")
  }, 3000);
}
