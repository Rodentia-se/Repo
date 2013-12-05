<?php

  function isAssoc(&$arr) {
    if (is_array($arr)) {
      for (reset($arr); is_int(key($arr)); next($arr));
      return !is_null(key($arr));
    } else {
      return false;
    }
  }
  
  function isId($id) {
    return preg_match('/^[0-9]+$/', $id);
  }

  function preDump($obj, $title = NULL) {
    echo '<pre>';
    echo ($title) ? $title.':' : '';
    var_dump($obj);
    echo '</pre>';
  }
  
  function warning($text) {
    preDump($text,'WARNING');
  }

  function error($text, $die = FALSE, $json = FALSE) {
    if ($json) {
      $error = (object) array('success' => false, 'reason' => $text);
      return json_encode($error);
    } else {
      preDump($text,'ERROR');
      if ($die) {
        die('Abort requested.');
      }
    }
  }

  function success($text = NULL, $props = NULL) {
    $json = (object) array('success' => true, 'reason' => $text);
    if ($props) {
      foreach ($props as $prop => $value) {
        $json->{$prop} = $value;
      }
    }
    return $json;
  }
  
  function debug($text, $die = FALSE) {
    if (config::$debug) {
      preDump($text,'DEBUG');
      if ($die) {
        die('Abort requested.');
      }
    }
  }

  function validate($valid = TRUE, $reason = NULL, $obj = FALSE) {
    if ($obj) {
      $return = object():
      $return->valid = $valid;
      if ($reason) {
        $return->reason = $reason;
      }
      return $return;
    } else {
      return $valid;
    }
  }

?>