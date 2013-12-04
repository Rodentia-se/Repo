<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  if ($page->reqLogin('You need to be logged in and registered as a participant to access this page. Please go to the <a href="'.config::$baseHref.'/reigstration/">registration page</a> or login here:')) {
    $person = $page->login->person;
    if ($person) {
      $page->addContent($person->getEdit());
      $page->addContent($person->getPhotoEdit(NULL, 'right')); 
      $page->jeditable = TRUE;
      $page->combobox = TRUE;
      $page->forms = TRUE;
      $page->addScript('
        $(".combobox").change(function(){
          var sel = this;
          $.post("'.config::$baseHref.'/ajax/setPlayerProp.php", {prop: sel.id, value: $(sel).val()})
          .done(function(data) {
            if (data.success) {
              $("#" + sel.id + "_combobox").val($(sel).children(":selected").text());
              if (data.new_id && data.new_id != $("#" + data.new_obj + "_id").val()) {
                $("#" + data.new_obj + "_id").val(data.new_id);
                $("#" + data.new_obj + "_id").change();
              }
              $.each(data.nulls, function(key, geo) {
                $("#" + geo + "_id").val(0);
                $("#" + geo + "_id").change();
              });
              $(sel).data("previous", $(sel).val());
            } else {
              $(sel).val($(sel).data("previous"));
            }
          })
          .fail(function(jqHXR,status,error) {
            debugOut("Fail: S: " + status + " E: " + error); // Oh, no! Fail!
            debugOut(jqHXR.responseText);
          })
        })
        .combobox();
        $(".edit").change(function(){
          var input = this;
          $.post("'.config::$baseHref.'/ajax/setPlayerProp.php", {prop: input.id, value: $(input).val()})
          .done(function(data) {
            if (data.success) {
              $(input).data("previous", $(input).val());
            } else {
              $(input).val($(input).data("previous"));
            }
          })
          .fail(function(jqHXR,status,error) {
            debugOut("Fail: S: " + status + " E: " + error); // Oh, no! Fail!
            debugOut(jqHXR.responseText);
          });
        });
        $(".edit").change(function(){
          var input = this;
          $.post("'.config::$baseHref.'/ajax/setPlayerProp.php", {prop: input.id, value: $(input).val()})
          .done(function(data) {
            if (data.success) {
              $(input).data("previous", $(input).val());
            } else {
              $(input).val($(input).data("previous"));
            }
          })
          .fail(function(jqHXR,status,error) {
            debugOut("Fail: S: " + status + " E: " + error); // Oh, no! Fail!
            debugOut(jqHXR.responseText);
          });
        });
        $(".check").change(function(){
          var box = this;
          $.post("'.config::$baseHref.'/ajax/setPlayerProp.php", {prop: box.id, value: ((box.checked) ? 1 : 0)})
          .done(function(data) {
            if (data.success) {
              $(box).data("previous", ((box.checked) ? 1 : 0));
            } else {
              box.checked = ($(box).data("previous"));
            }
          })
          .fail(function(jqHXR,status,error) {
            debugOut("Fail: S: " + status + " E: " + error); // Oh, no! Fail!
            debugOut(jqHXR.responseText);
          });
        });
        $(".date").datepicker({
          dateFormat: "yy-mm-dd",
          yearRange: "-100:-0",
          changeYear: true, 
          changeMonth: true 
        });
        $("#cityDiv").hide();
        $("#regionDiv").hide();
        $(".addIcon").click(function() {
          $("#" + this.id.replace("add_", "") + "_idDiv").hide();
          $("#" + this.id.replace("add_", "") + "Div").show();
        });
        $(".closeIcon").click(function() {
          $("#" + this.id.replace("close_", "") + "_idDiv").show();
          $("#" + this.id.replace("close_", "") + "Div").hide();
          $("#" + this.id.replace("close_", "")).val("");
        });
      ');
    } else {
      error('Could not find you in the database?', TRUE);
    }
  }
  
  $page->submit();

?>