<?php

  function isAssoc(&$arr) {
    if (is_array($arr)) {
      for (reset($arr); is_int(key($arr)); next($arr));
      return !is_null(key($arr));
    } else {
      return false;
    }
  }
  
  function isJson($string) {
    if (is_string($json)) {
      json_decode($string);
      return (json_last_error() == JSON_ERROR_NONE);
    } else {
      return FALSE;
    }
  }
  
  function preDump($obj, $title = NULL) {
    echo '<pre>';
    echo ($title) ? $title.': ' : '';
    var_dump($obj);
    echo '</pre>';
  }
  
  function output($msg = NULL, $title = NULL, $props = NULL, $valid = TRUE, $type = FALSE, $die = FALSE) {
    switch ($type) {
      case 'json':
      case 'obj':
      case 'object':
      case 'array':
        $return = array(
          'valid' => $valid,
          'reason' => $msg
        );
        if ($props) {
          foreach ($props as $prop => $value) {
            $return[$prop] = $value;
          }
        }
        return ($type == 'array') ? $return : (($type == 'json') ? json_encode((object) $return) : (object) $return);
      break;
      case 'text':
        $text = is_object($msg) ? $msg->reason : $msg;
        return ($title) ? $title.': '.$text : $text;
      break;
      case 'bool':
        return ($valid);
      break;
      case 'dump':
      default:
        preDump($msg, $title);
        $text = is_object($msg) ? $msg->reason : $msg;
        if ($die) {
          die(($title) ? $title.': '.$text.'. Abort requested.' : $text.'. Abort requested.');
        }
        return ($valid);
      break;
    }
  }
  
  function warning($text) {
    return output($text, 'WARNING');
  }

  function error($text = NULL, $props = NULL, $json = FALSE, $die = FALSE) {
    return output($text, 'ERROR', $props, FALSE, (($json) ? 'json' : 'dump'), $die);
  }

  function failure($text = NULL, $props = NULL, $json = TRUE) {
    return output($text, 'FAILURE', $props, FALSE, (($json) ? 'json' : 'dump'));
  }

  function success($text = NULL, $props = NULL, $json = TRUE) {
    return output($text, 'SUCCESS', $props, TRUE, (($json) ? 'json' : 'dump'));
  }
  
  function debug($msg, $title = NULL, $die = FALSE) {
    if (config::$debug) {
      return output($msg, 'DEBUG'.(($title) ? ': '.$title : ''), NULL, NULL, 'dump', $die);
    } else {
      return FALSE;
    }
  }
  
  function jsonEcho($obj) {
    if (isJson($obj)) {
      $json = $obj;
    } else if (is_object($obj) || is_array($obj)) {
      $json = json_encode($obj);
    } else {
      return FALSE;
    }
    header('Content-Type: application/json');
    echo($json);
    return TRUE;
  }

  function testRun($msg, $title = NULL) {
    if (config::$debug) {
      global $testNum;
      $testNum++;
      return debug($msg, '(Num: '.$testNum.') : '.$title);
    } else {
      return FALSE;
    }
  }

  function validated($valid = TRUE, $reason = NULL, $obj = FALSE) {
    return output($reason, 'VALIDATION', NULL, $valid, (($obj) ? 'object' : 'bool'));
  }
  
  function validateDate($date, $obj = FALSE) {
    if ($date && !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)) {
      return validated(FALSE, 'The date is invalid. Please use ISO format YYYY-MM-DD.', $obj);
    } else if (!$date || checkdate(preg_replace('/00/','01',substr($date, 5,2)), preg_replace('/00/','01',substr($date, 8,2)), substr($date, 0,4))) {
      return validated(TRUE, 'The date is valid.', $obj);
    } else {
      return validated(FALSE, 'The date is invalid.', $obj);
    }
  }
  
  function validate($class, $prop, $value, $obj = NULL) {
    if (isObj($class, TRUE)) {
      return call_user_func(get_class($obj).'::validate', $prop, $value, $obj);
    } else if (isObj($class)) {
      return call_user_func($class.'::validate', $prop, $value, $obj);
    }
  }
?>