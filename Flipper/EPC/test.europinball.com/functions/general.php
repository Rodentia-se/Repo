<?php

  function isAssoc(&$arr) {
    if (is_array($arr)) {
      for (reset($arr); is_int(key($arr)); next($arr));
      return !is_null(key($arr));
    } else {
      return false;
    }
  }
  
  function isJson($string = NULL) {
    if (is_string($string)) {
      json_decode($string);
      return (json_last_error() == JSON_ERROR_NONE);
    } else {
      return FALSE;
    }
  }
  
  function now($time = TRUE) {
    return ($time) ? date('Y-m-d H:i:s') : date('Y-m-d');
  }
  
  function today() {
    return now(FALSE);
  }
  
  function is($string ) {
    return ($string || $string === 0 || $string === "0") ? TRUE : FALSE;
  }
  
  function mergeToArray($obj1 = NULL, $obj2 = NULL, $delimiter = ' ') {
    $obj1 = (is($obj1)) ? $obj1 : '';
    $obj2 = (is($obj2)) ? $obj2 : '';
    if (is_array($obj1) || is_object($obj1)) {
      if (is_array($obj2) || is_object($obj2)) {
        return array_filter(array_unique(array_merge((array) $obj1, (array) $obj2)));
      } else {
        return array_filter(array_unique(array_merge((array) $obj1, explode(' ', trim((string) $obj2)))));
      }
    } else {
      if (is_array($obj2) || is_object($obj2)) {
        return array_filter(array_unique(array_merge(explode(' ', trim((string) $obj1)), (array) $obj2)));
      } else {
        return array_filter(array_unique(array_merge(explode(' ', trim((string) $obj1)), explode(' ', trim((string) $obj2)))));
      }
    }
  }
  function camelCaseToSpace($txt, $ucfirst = FALSE) {
    $regexp = '/#
      (?<=[a-z])
      (?=[A-Z])
      /x';
    $array = preg_split($regexp, $txt);
    $return = implode($array, ' ');
    return ($ucfirst) ? ucfirst($return) : $return;
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
    if (config::$showWarnings) {
      return output($text, 'WARNING');
    } else {
      return FALSE;
    }
  }

  function error($text = NULL, $props = NULL, $json = FALSE, $die = FALSE) {
    if (config::$showErrors) {
      return output($text, 'ERROR', $props, FALSE, (($json) ? 'json' : 'dump'), $die);
    } else {
      return FALSE;
    }
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
  
  function jsonEcho($obj, $exit = FALSE) {
    if (isJson($obj)) {
      $json = $obj;
    } else if (is_object($obj) || is_array($obj)) {
      $json = json_encode($obj);
    } else {
      return FALSE;
    }
    header('Content-Type: application/json');
    echo($json);
    if (is($exit)) {
      exit $exit;
    }
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