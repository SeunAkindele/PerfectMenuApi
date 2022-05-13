<?php
  class ItemCategory {
    public function createItemCategory($name) {
      global $db, $fun;
      
      $db->create(TBL_ITEM_CATEGORY, "name='$name', location_id='" . LOCATION . "', user_id='" . ID . "', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME . "', status = 0");
      
      $fun->jsonResponse(true, "Entry saved successfully", "200");
    }

    public function itemCategoryRows($fields="*", $query="", $column="") {
      global $db;
      
      $queries = !empty($query) ? "$query AND" : "";
      return $db->fetch(TBL_ITEM_CATEGORY, $fields, "$queries status = 0", $column);
    }

    public function getItemCategory($fields="*", $conditions = "", $col="") {
      $con = !empty($conditions) ? "AND $conditions" : "";
      return $this->itemCategoryRows($fields, "location_id='" . LOCATION . "' $con", $col);
    }

  }