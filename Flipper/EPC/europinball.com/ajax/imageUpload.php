<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $prefix = $_REQUEST['prefix'];
  $obj = $_REQUEST[$prefix.'obj'];
  $previewPath = $_REQUEST[$prefix.'previewPath'];
  $id = (isId($_REQUEST[$prefix.'id'])) ? $_REQUEST[$prefix.'id'] : NULL;
  $action = $_REQUEST[$prefix.'action'];
  $relPath = '/images/objects/'.$obj.'/';
  $path = config::$baseDir.$relPath;
  if ($action == 'preview') {
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == 'POST') {
      $name = $_FILES['imageUpload']['name'];
      $size = $_FILES['imageUpload']['size'];
      header('Content-Type: text/html');
      if(strlen($name)) {
        list($txt, $ext) = explode('.', $name);
        if(in_array($ext, config::$photoExts)) {
          if($size < (1024*1024) && $size != 0) {
            $tmp = $_FILES['imageUpload']['tmp_name'];
            $previewFile = $path.'/preview/'.$id.'.'.$ext;
            if (move_uploaded_file($tmp, $previewFile)) {
//              chmod($previewFile, 0664);
              //          usleep(100000);
              echo '
                <script src="'.config::$baseHref.'/js/contrib/jquery.form.min.js" type="text/javascript"></script>
                <img src="'.config::$baseHref.'/images/objects/'.$obj.'/preview/'.$id.'.'.$ext.'?nocache='.rand(10000,20000).'" class="preview" id="'.$prefix.'thumb" alt="Preview of image">
                <div id="'.$prefix.'imageLoader"></div>
                <input type="hidden" name="'.$prefix.'previewPath" id="'.$prefix.'previewPath" value="'.$relPath.'/preview/'.$id.'.'.$ext.'">
                <script type="text/javascript">
                  $(document).ready(function() { 
                    $("#'.$prefix.'imageUpload").on("change", function() {
                      $("#'.$prefix.'preview").html("");
                      $("#'.$prefix.'imageLoader").html("<img src=\"'.config::$baseHref.'/images/loader.gif\" alt=\"Uploading....\"/>");
                      $("#'.$prefix.'submitImg").button("option", "disabled", false);
                      $("#'.$prefix.'imageForm").ajaxForm({
                        target: "#'.$prefix.'preview"
                      }).submit();
                      $("#'.$prefix.'imageLoader").html("");
                    });
                    $("#'.$prefix.'thumb").on("click", function() {
                      $("#'.$prefix.'imageUpload").trigger("click");
                    });
                    $("#'.$prefix.'submitImg").tooltipster({
                      theme: ".tooltipster-light",
                      content: "Saving the image...",
                      trigger: "custom",
                      position: "right",
                      timer: 3000
                    })
                    .click(function() {
                      var el = this;
                      var path = $("#'.$prefix.'previewPath").val();
                      $(el).tooltipster("show");
                      $.post("'.config::$baseHref.'/ajax/imageUpload.php", {obj: "'.$obj.'", id: '.$id.', previewPath: path, action: "save"})
                      .done(function(data) {
                        $(el).tooltipster("update", data.reason).tooltipster("show");
                      })
                      .fail(function(jqHXR,status,error) {
                        $(el).tooltipster("update", "Fail: S: " + status + " E: " + error).tooltipster("show");
                      });
                    });
                  }); 
                </script>
              ';
            } else {
              echo 'File move failed. Reload page to try again.';
            }
          } else {
            echo 'Image file is too big (size is max 1 MB) or the file was corrupt. Reload page to try again.';
          }
        } else {
          echo 'Invalid file format. Reload page to try again.';	
        }
      } else {
        echo 'Please select image! Reload page to try again.';
      }
    } else {
      echo 'Unkonwn error... Reload page to try again.';
    }
  } else if ($action == 'save') {
    if (class_exists($obj)) {
      $target = $obj($id);
      if ($target) {
        $save = $target->setPhoto((($previewPath) ? $previewPath : NULL));
        if ($save) {
          $json = success('Photo saved');
        } else {
          $json = failure('Could not save photo');
        }
      } else {
        $json = failure('Could not find the '.$obj);
      }
    } else {
      $json = failure('Could not find the '.$obj.' object type');
    }
  } else {
    $json = failure('Unknown action');
  }
  if ($json) {
    jsonEcho($json);
  }

?>