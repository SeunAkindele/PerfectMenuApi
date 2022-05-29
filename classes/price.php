<?php
  class Price {
    public function createItemPrice($itemId, $categoryId, $price) {
      global $db;

      $db->create(TBL_PRICE, "price='$price', item_id='$itemId', category_id='$categoryId', location_id='" . LOCATION . "', user_id='" . ID . "', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME . "', status = 0");
    }

    public function updatePrice($itemId, $price){
      global $db;

      $db->update(TBL_PRICE, "price='$price'", "item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0 ");
    }

    public function itemPriceRows($fields="*", $query="", $column="") {
      global $db;
      
      $queries = !empty($query) ? "$query AND" : "";
      return $db->fetch(TBL_PRICE, $fields, "$queries status=0", $column);
    }

    public function getItemPrice($fields="*", $conditions="", $col="") {
      global $db;
      
      $con = !empty($conditions) ? "AND $conditions" : "";
      return $this->itemPriceRows($fields, "location_id='" . LOCATION . "' $con", $col);
    }
  }