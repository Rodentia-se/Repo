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
          var el = this;
          var tooltip = $(el).parent().tooltip({
            items: "[data-reason]",
            content: "Updating the database...",
            disable: true
          });
          tooltip.tooltip("open");
          $.post("'.config::$baseHref.'/ajax/setPlayerProp.php", {prop: el.id, value: $(el).val()})
          .done(function(data) {
            tooltip.content = data.reason;
            if (data.success) {
              $("#" + el.id + "_combobox").val($(el).children(":selected").text());
              if (data.parents) {
                $.each(data.parents, function(key, geo) {
                  if (!stop) {
                    if (data.parent_obj == geo) {
                      if (data.parent_id != $("#" + geo + "_id").val()) {
                        $("#" + geo + "_id").val(data.parent_id);
                        $("#" + geo + "_id").change();
                      }
                      var stop = true;
                    } else if ($("#" + geo + "_id").val() != 0) {
                      $("#" + geo + "_id").val(0);
                      $("#" + geo + "_id_combobox").val("");
                    }
                  }
                });
              }
              $(el).data("previous", $(el).val());
            } else {
              $(el).val($(el).data("previous"));
            }
          })
          .fail(function(jqHXR,status,error) {
            debugOut("Fail: S: " + status + " E: " + error); // Oh, no! Fail!
            debugOut(jqHXR.responseText);
          })
        })
        .combobox();
        $(".custom-combobox-input").on("autocompleteclose", function(event, ui) {
          if ($("#" + this.id.replace("_combobox", "")).is("select") && $(this).val() == "" && $("#" + this.id.replace("_combobox", "")).val() != 0) {
alert("twice");
            $("#" + this.id.replace("_combobox", "")).val(0);
            $("#" + this.id.replace("_combobox", "")).change();
          }
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