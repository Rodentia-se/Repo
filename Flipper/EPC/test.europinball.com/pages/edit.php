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
          $.post("'.config::$baseHref.'/ajax/setPlayerProp.php", {prop: this.id, value: $(this).val()})
          .done(function(data) {
            if (data.success) {
              $("#" + this.id + "_combobox").val($(this).children(":selected").text());
              if (data.new_id) {
                $("#" + data.new_obj + "_id option:eq(" + data.new_id + ")").prop("selected", true);
                $("#" + data.new_obj + "_id").change();
              }
              alert($(this).data("previous"));
              alert($(this).attr("data-previous"));
              $(this).attr("data-previous", $(this).val());
            } else {
              $("#" + this.id + " option:eq(" + $(this).data("previous") + ")").prop("selected", true);
            }
          })
          .fail(function(jqHXR,status,error) {
            debugOut("Fail: S: " + status + " E: " + error); // Oh, no! Fail!
            debugOut(jqHXR.responseText);
          });
        });
        $(".combobox").combobox();
        $(".date").datepicker({
          dateFormat: "yy-mm-dd",
          changeYear: true, 
          yearRange: "-100:-0",
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