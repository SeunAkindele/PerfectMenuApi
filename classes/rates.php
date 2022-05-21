<?php
  class Rates {

    public function rateItem($itemId, $ratings, $type) {
      global $db;

      if($db->countRows(TBL_RATE, "id", "customer_id='" . ID . "' AND item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0")) {
        $this->updateItemRate($itemId, $ratings, $type);
      } else {
        $this->createItemRate($itemId, $ratings);
      }
    }

    public function createItemRate($itemId, $ratings) {
      global $db;

      $db->create(TBL_RATE, "rate='$ratings', item_id='$itemId', location_id='" . LOCATION . "', customer_id='" . ID . "', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME . "', status = 0");
    }

    public function updateItemRate($itemId, $ratings, $type){
      global $db;

      if($type == "gold") {
        $db->update(TBL_RATE, "rate='$ratings'", "item_id='$itemId' AND customer_id='" . ID . "' AND status = 0");
      } else if($type == "silver") {
        $currentRate = $this->getItemRate("rate", "item_id='$itemId' AND customer_id='" . ID . "' AND status = 0", "rate");
        $newRate = $currentRate + $ratings;
        $db->update(TBL_RATE, "rate='$newRate'", "item_id='$itemId' AND customer_id='" . ID . "' AND status = 0");
      }
    }

    public function itemRateRows($fields="*", $query="", $column="") {
      global $db;
      
      $queries = !empty($query) ? "$query AND" : "";
      return $db->fetch(TBL_RATE, $fields, "$queries status=0", $column);
    }

    public function getItemRate($fields="*", $conditions="", $col="") {
      global $db;
      
      $con = !empty($conditions) ? "AND $conditions" : "";
      return $this->itemRateRows($fields, "location_id='" . LOCATION . "' $con", $col);
    }
  }