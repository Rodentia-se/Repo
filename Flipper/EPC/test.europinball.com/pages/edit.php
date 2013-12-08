<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  if ($page->reqLogin('You need to be logged in to access this page. If you are don\'t have a user, please go to the <a href="'.config::$baseHref.'/registration/">registration page</a>.')) {
    $person = person('login');
    if ($person) {
      $page->addContent($person->getEdit());
      $page->addContent($person->getPhotoEdit(NULL, 'right')); 
      $page->jeditable = TRUE;
      $page->combobox = TRUE;
      $page->forms = TRUE;
      $page->addScript('
        $(".combobox").change(function(){
          var el = this;
          var combobox = document.getElementById(el.id + "_combobox");
          showTooltip(combobox, "Updating the database...", 45);
          $.post("'.config::$baseHref.'/ajax/setPlayerProp.php", {prop: el.id, value: $(el).val()})
          .done(function(data) {
            showTooltip(combobox, data.reason, 45);
            if (data.valid) {
              $(combobox).val($(el).children(":selected").text())
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
            showTooltip(combobox, "Fail: S: " + status + " E: " + error, 45); // Oh, no! Fail!
          })
        })
        .combobox();
        $(".custom-combobox-input").on("autocompleteclose", function(event, ui) {
          if ($("#" + this.id.replace("_combobox", "")).is("select") && $(this).val() == "" && $("#" + this.id.replace("_combobox", "")).val() != 0) {
alert("twice");
            $("#" + this.id.replace("_combobox", "")).val(0);
            $("#" + this.id.replace("_combobox", "")).change();
          }
        })
        .autocomplete("option", "autoFocus", true);
        $(".edit").change(function(){
          var el = this;
          if (el.id == "shortName") {
            $(el).val($(el).val().toUpperCase());
          } 
          var value = ($(el).is(":checkbox")) ? ((el.checked) ? 1 : 0) : $(el).val();
          var region_id = (this.id == "city") ? $("#region_id").val() : null;
          var country_id = (this.id == "city" || this.id == "region") ? $("#country_id").val() : null;
          var continent_id = (this.id == "city" || this.id == "region") ? $("#continent_id").val() : null;
          showTooltip(el, "Updating the database...");
          $.post("'.config::$baseHref.'/ajax/setPlayerProp.php", {prop: el.id, value: value, region_id: region_id, country_id: country_id, continent_id: continent_id})
          .done(function(data) {
            showTooltip(el, data.reason);
            if (data.valid) {
              $(el).data("previous", (($(el).is(":checkbox")) ? ((el.checked) ? 1 : 0) : $(el).val()));
            } else {
              if ($(el).is(":checkbox")) {
                el.checked = ($(el).data("previous"));
              } else {
                $(el).val($(el).data("previous"));
              }
            }
          })
          .fail(function(jqHXR,status,error) {
            showTooltip(el, "Fail: S: " + status + " E: " + error); // Oh, no! Fail!
          })
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
  } else {
    $page->addLogin();
  }
  
  $page->submit();

?>