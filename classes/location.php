<?php

class Location {

  public function locationRows($fields="*", $query="", $column="") {
    global $db;
    
    $queries = !empty($query) ? "$query AND" : "";
    return $db->fetch(TBL_LOCATION, $fields, "$queries status=0", $column);
  }

  public function getLocationVatValue() {
    return $this->getLocation("vat_value", "id='" . LOCATION . "'", "vat_value");
  }

  public function getLocationName($id) {
    return $this->getLocation("name", "id='$id'", "name");
  }

  public function getLocation($fields="*", $conditions="", $col="") { 
    $con = !empty($conditions) ? $conditions : "";
    return $this->locationRows($fields, $con, $col);
  }
}