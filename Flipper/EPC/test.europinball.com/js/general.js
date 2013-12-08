function ucfirst(txt) {
  return txt.toString().substring(0, 1).toUpperCase() + txt.toString().substring(1); // Why is this not a native part of Javascript? 
}

var tooltips = [];
var tooltipsTimers = [];

function showTooltip(el, text, offset) {
  $(el).tooltipster({
    theme: '.tooltipster-light',
    content: text,
    interactiveAutoClose: false,
    position: 'right',
    offsetX: offset,
    timer: 3000
  })
  .tooltipster('show');
}
/*



  if (!$('#' + el.id + '_tooltip')) {
    
  }
  alert(el.id);
  var offset = (offset) ? offset : 15;
  if (typeof tooltipsTimers[el.id] != "undefined") {
    alert('timer defined');
    clearTimeout(tooltipsTimers[el.id]);
    tooltipsTimers.splice(el.id, 1);
  }
  if (typeof tooltips[el.id] != "undefined") {
    alert('tooltip defined');
    $(el).tooltip("destroy");
    tooltips.splice(el.id, 1);
  }
  tooltips[el.id] = $(el).tooltip({
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
  tooltipsTimers[el.id] = setTimeout(function(){
    $(el).tooltip("close")
    .tooltip("disable");
  }, 3000);
  */
