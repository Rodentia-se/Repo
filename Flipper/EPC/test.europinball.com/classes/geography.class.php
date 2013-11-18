<?php

  abstract class geography extends base {
    
    public function getLocations() {
      return $this->db->getObjectsByParent('location', $this);
    }

  }
?>