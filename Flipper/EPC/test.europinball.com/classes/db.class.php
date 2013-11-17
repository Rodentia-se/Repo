<?php

  class db extends PDO {
    
    private $host = 'localhost';
    private $name = 'epc_test';
    private $user = 'epc';
    private $pass = 'vLdqLYyvxSZermEv';
    private $charset = 'utf8';
    private $dbh;
    
    public function __construct() {
      try {
        $dbh = new PDO('mysql:host='.$this->host.';dbname='.$this->name.';charset='.$this->charset, $this->user, $this->pass);
      } catch (PDOException $e) {
        die('Failed to connect to MySQL: '.$e->getMessage());
      }
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->_set($dbh);
    }
    
    protected function _set($data) {
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }
    }

    public function getObjectById($class, $id) {
      $query = $class::$select.' where o.id = '.$id;
      $sth = $dbh->query($query);
      $obj = $sth->fetchObject(get_class($object));
      if ($this->last_row_count()) {
        return $obj;
      } else {
        return false;
      }
    }

    public function last_row_count() {
      return $this->query("SELECT FOUND_ROWS()")->fetchColumn();
    }

  }


?>