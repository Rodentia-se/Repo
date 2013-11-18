<?php

  class db extends PDO {
    
    private $host = 'localhost';
    private $name = 'epc_test';
    private $user = 'epc';
    private $pass = 'vLdqLYyvxSZermEv';
    private $charset = 'utf8';
    private $dbh;
    private $sth;
    
    public function __construct() {
      parent::__construct('mysql:host='.$this->host.';dbname='.$this->name.';charset='.$this->charset, $this->user, $this->pass);
      $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      unset($this->pass);
    }
    
    public function getObjectById($class, $id) {
      echo $class.' '.$id.': ';
      if (array_key_exists('ID'.$id, $class::$instances)) {
        echo 'YES<br/>';
        return $class::$instances['ID'.$id];
      } else {
        echo 'NO<br/>';
        $query = $class::$select.' where o.id = '.$id;
        $this->sth = $this->query($query);
        $obj = $this->sth->fetchObject($class);
        if ($this->last_row_count()) {
          $class::$instances['ID'.$id] = $obj;
          return $obj;
        } else {
          return FALSE;
        }
      }
    }

    public function last_row_count() {
      return $this->query("SELECT FOUND_ROWS()")->fetchColumn();
    }

  }


?>