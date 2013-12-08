<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  header('Content-Type: text/html');

  $prefix = $_REQUEST['prefix'];
  $obj = $_REQUEST['obj'];
  $path = config::$baseDir.'/images/objects/'.$_REQUEST['obj'].'/';

  if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_FILES['imageUpload']['name'];
    $size = $_FILES['imageUpload']['size'];
			
    if(strlen($name)) {
      list($txt, $ext) = explode('.', $name);
      if(in_array($ext, config::$photoExts)) {
        if($size < (1024*1024) && $size != 0) {
          $tmp = $_FILES['imageUpload']['tmp_name'];
          $previewFile = $path.'/preview/'.$_REQUEST['id'].'.'.$ext;
          if (move_uploaded_file($tmp, $previewFile)) {
            chmod($previewFile, 0664);
            //          usleep(100000);
            echo '
              <script src="'.config::$baseHref.'/js/contrib/jquery.form.min.js" type="text/javascript"></script>
              <img src="'.config::$baseHref.'/images/objects/'.$obj.'/preview/'.$_REQUEST['id'].'.'.$ext.'?nocache='.rand(10000,20000).'" class="preview" id="'.$prefix.'thumb" alt="Preview of image">
              <div id="'.$prefix.'imageLoader"></div>
              <script type="text/javascript">
                $(document).ready(function() { 
                  $(\'#'.$prefix.'imageUpload\').on(\'change\', function() {
                    $(\'#'.$prefix.'preview\').html(\'\');
                    $(\'#'.$prefix.'imageLoader\').html(\'<img src="'.config::$baseHref.'/images/loader.gif" alt="Uploading...."/>\');
                    $(\'#'.$prefix.'submitImg\').prop(\'disabled\', false);
                    $(\'#'.$prefix.'imageForm\').ajaxForm({
                      target: \'#'.$prefix.'preview\'
                    }).submit();
                    $(\'#'.$prefix.'imageLoader\').html(\'\');
                  });
                  $(\'#'.$prefix.'thumb\').on(\'click\', function() {
                    $(\'#'.$prefix.'imageUpload\').trigger(\'click\');
                  });
                }); 
              </script>
            ';
          } else {
            echo 'File move failed';
          }
        } else {
          echo 'Image file is too big (size is max 1 MB) or the file was corrupt.';
        }
      } else {
        echo 'Invalid file format..';	
      }
    } else {
      echo 'Please select image..!';
    }
    exit;
  }

?>