<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  if ($page->reqLogin('You need to be logged in to access this page. If you are don\'t have a user, please go to the <a href="'.config::$baseHref.'/registration/">registration page</a>.')) {
    $person = person('login');
    if ($person) {
      $page->jeditable = TRUE;
      $page->combobox = TRUE;
      $page->tooltipster = TRUE;
      $page->forms = TRUE;
      $page->startDiv('tabs');
        $page->startUl();
          foreach(config::$editSections as $section) {
            $page->addLi('<a href="#'.preg_replace('/[^a-zA-Z0-9]/', '', $section).'" id="'.preg_replace('/[^a-zA-Z0-9]/', '', $section).'tabLink">'.ucfirst($section).'</a>');
          }
        $page->closeUl();
        if (in_array('profile', config::$editSections)) {
          $page->startDiv('profile');
            $page->addContent($person->getEdit());
            $page->addScript('
              $(".combobox").combobox()
              .change(function(){
                var el = this;
                var combobox = document.getElementById(el.id + "_combobox");
                $(combobox).tooltipster("update", "Updating the database...").tooltipster("show");
                $.post("'.config::$baseHref.'/ajax/setPlayerProp.php", {prop: el.id, value: $(el).val()})
                .done(function(data) {
                  $(combobox).tooltipster("update", data.reason).tooltipster("show");
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
                  $(combobox).tooltipster("update", "Fail: S: " + status + " E: " + error).tooltipster("show");
                })
              });
              $(".custom-combobox-input").on("autocompleteclose", function(event, ui) {
                if ($("#" + this.id.replace("_combobox", "")).is("select") && $(this).val() == "" && $("#" + this.id.replace("_combobox", "")).val() != 0) {
                  $("#" + this.id.replace("_combobox", "")).val(0);
                  $("#" + this.id.replace("_combobox", "")).change();
                }
              })
              .tooltipster({
                theme: ".tooltipster-light",
                content: "Updating the database...",
                trigger: "custom",
                position: "right",
                offsetX: 38,
                timer: 3000
              });
              $(".edit").change(function(){
                var el = this;
                if (el.id == "shortName") {
                  $(el).val($(el).val().toUpperCase());
                } 
                var value = ($(el).is(":checkbox")) ? ((el.checked) ? 1 : 0) : $(el).val();
                var region_id = (this.id == "city") ? $("#region_id").val() : null;
                var country_id = (this.id == "city" || this.id == "region") ? $("#country_id").val() : null;
                var continent_id = (this.id == "city" || this.id == "region") ? $("#continent_id").val() : null;
                $(el).tooltipster("update", "Updating the database...").tooltipster("show");
                $.post("'.config::$baseHref.'/ajax/setPlayerProp.php", {prop: el.id, value: value, region_id: region_id, country_id: country_id, continent_id: continent_id})
                .done(function(data) {
                  $(el).tooltipster("update", data.reason).tooltipster("show");
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
                  $(el).tooltipster("update", "Fail: S: " + status + " E: " + error).tooltipster("show");
                })
              })
              .tooltipster({
                theme: ".tooltipster-light",
                content: "Updating the database...",
                position: "right",
                trigger: "custom",
                timer: 3000
              });
              $(".date").datepicker({
                dateFormat: "yy-mm-dd",
                yearRange: "-100:-0",
                defaultDate: "-30y",
                changeYear: true, 
                changeMonth: true 
              });
              $("#cityDiv").hide();
              $("#regionDiv").hide();
              $(".addIcon").click(function() {
                $("#" + this.id.replace("add_", "") + "_idDiv").hide();
                $("#" + this.id.replace("add_", "") + "Div").show().find("input").first().focus();
              });
              $(".closeIcon").click(function() {
                $("#" + this.id.replace("close_", "") + "Div").hide();
                $("#" + this.id.replace("close_", "") + "_idDiv").show().find("input").first().focus();
                $("#" + this.id.replace("close_", "")).val("");
              });
            ');
          $page->closeDiv();
        }
        if (in_array('photo', config::$editSections)) {
          $page->startDiv('photo');
            $page->addContent($person->getPhotoEdit());
          $page->closeDiv();
        }
        if (in_array('security', config::$editSections)) {
          $page->startDiv('security');
            $page->addUserEdit();
          $page->closeDiv();
        }
        if (in_array('team', config::$editSections)) {
          $page->startDiv('team');
          $page->closeDiv();
        }
        if (in_array('qualGroup', config::$editSections)) {
          $page->startDiv('qualGroup');
          $page->closeDiv();
        }
        if (in_array('t-shirts', config::$editSections)) {
          $page->startDiv('tshirts');
          $page->closeDiv();
        }
        if (in_array('volunteer', config::$editSections)) {
          $page->startDiv('volunteer');
          $page->closeDiv();
        }
        if (in_array('payment', config::$editSections)) {
          $page->startDiv('payment');
            $page->addH2('Payment options');
            $page->startDiv('currencyDiv');
              $page->addSimpleSelect(config::$acceptedCurrencies, 'currency', 'currency');
              foreach(config::$acceptedCurrencies as $currency) {
                $page->addInput(config::$currencies[$currency]['format'], config::$currencies[$currency]['shortName'].'Format',  config::$currencies[$currency]['shortName'].'Format', 'hidden');
                $page->addInput(config::$currencies[$currency]['rate'], config::$currencies[$currency]['shortName'].'Rate',  config::$currencies[$currency]['shortName'].'Rate', 'hidden');
              }
            $page->closeDiv();
            $page->addScript('
              try {
                var curVal = dataStore.getItem("curVal");
              } catch(e) {
                var curVal = 0;
              };
              $("#currency").val(curVal)
              .combobox()
              .change(function(){
                dataStore.setItem("curVal", $(this).val());
                $(".cost").change();
              });
            ');
            $divisions = divisions('active');
            foreach ($divisions as $division) {
              if (property_exists('config', $division->type.'Cost') && config::${$division->type.'Cost'}) {
                $page->startDiv($division->type.'CostDiv');
                  $cost = $person->getCost($division);
                  $costs += $cost;
                  $page->addInput(1, $division->type.'Num', $division->type.'Num', 'text', 'cost short', camelCaseToSpace($division->type, TRUE));
                  $page->addSpan($cost, $division->type.'Cost', 'currency');
                  $page->addInput($cost, $division->type.'Each', $division->type.'Each', 'hidden', 'each');
                $page->closeDiv();
              }
            }
            $page->startDiv('paidDiv');
              $page->addInput($person->paid, 'paidText', 'paidText', 'text', 'short', 'Paid', FALSE, TRUE);
              $page->addSpan($person->paid * -1, 'paidCur', 'currency');
              $page->addInput($person->paid, 'paid', 'paid', 'hidden');
            $page->closeDiv();
            $page->startDiv('totalDiv', 'noInput');
              $page->addLabel('Total');
              $page->addSpan('&nbsp;', 'paidFiller', 'short');
              $page->addSpan($costs - $person->paid, 'total', 'currency');
            $page->closeDiv();
          $page->closeDiv();
          $page->addScript('
            $(".cost").change(function() {
              var num = parseInt($(this).val().replace(/[^0-9]/g, ""));
              alert(this.id);
              var each = parseInt($("#" + this.id.replace("Num", "Each")).val().replace(/[^0-9]/g, ""));
              var rate = $("#" + $("#currency").children(":selected").text() + "Rate").val();
              var cost = num * each * rate;
              var format = $("#" + $("#currency").children(":selected").text() + "Format").val();
              $("#" + this.id.replace("Num", "Cost")).html(cost.toMoney(0, ".", " ", "", format));
              var costs = 0;
              $(".each").each(function() {
                var num = parseInt($("#" + this.id.replace("Each", "Num")).val().replace(/[^0-9]/g, ""));
                costs += parseInt($(this).val()) * num;
              }); 
              var paid = parseInt($("#paid").val());
              var total = (costs - paid) * rate;
              $("#total").html(total.toMoney(0, ".", " ", "", format));
              var paidCur = paid * rate * -1;
              $("#paidCur").html(paidCur.toMoney(0, ".", " ", "", format));
              var paidText = paidCur * -1;
              $("#paidText").val(paidText.toMoney(0, ".", " ", "", format));
            })
            .change();
          ');
        }
      $page->closeDiv();
      $page->addScript('
        try {
          var tabIndex = dataStore.getItem("tabIndex");
        } catch(e) {
          var tabIndex = 0;
        };
        $("#tabs").tabs({
          active: tabIndex,
          activate: function(event, ui) {
            var newTabIndex = ui.newTab.parent().children().index(ui.newTab);
            dataStore.setItem("tabIndex", newTabIndex);
            var firstField = ui.newPanel.find("input[type=text],textarea,select").filter(":visible:first");
            firstField.focus();
          },
          create: function(event, ui) {
            var firstField = ui.panel.find("input[type=text],textarea,select").filter(":visible:first");
            firstField.focus();
          }
        });
        $(".custom-combobox-input").autocomplete("option", "autoFocus", true)
      ');
    } else {
      error('Could not find you in the database?', TRUE);
    }
  }
  
  $page->submit();

?>