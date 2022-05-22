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

    public function getItemRateData($itemId) {
      global $db;
      
      $one = $this->getItemRate("SUM(rate) AS RATE", "rate = 1 AND item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0", "RATE");
      $two = $this->getItemRate("SUM(rate) AS RATE", "rate = 2 AND item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0", "RATE");
      $three = $this->getItemRate("SUM(rate) AS RATE", "rate = 3 AND item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0", "RATE");
      $four = $this->getItemRate("SUM(rate) AS RATE", "rate = 4 AND item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0", "RATE");
      $five = $this->getItemRate("SUM(rate) AS RATE", "rate = 5 AND item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0", "RATE");

      $rate1 = $one ? $one : 0;
      $rate2 = $two ? $two : 0;
      $rate3 = $three ? $three : 0;
      $rate4 = $four ? $four : 0;
      $rate5 = $five ? $five: 0;

      $customers = $db->countRows(TBL_RATE, "DISTINCT customer_id", "location_id='" . LOCATION . "' AND status = 0");
      $raters = $customers ? $customers : 0;
      $sum = $rate1 + $rate2 + $rate3 + $rate4 + $rate5;
      
      if($sum > 0) {
        $ratings = $sum / $raters;
      } else {
        $ratings = 0;
      }
      
      return $ratings;
    }

    public function getEachItemRate($itemId) {
      global $db;
      
      $one = $db->countRows(TBL_RATE, "id", "rate = 1 AND item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0");
      $two = $db->countRows(TBL_RATE, "id", "rate = 2 AND item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0");
      $three = $db->countRows(TBL_RATE, "id", "rate = 3 AND item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0");
      $four = $db->countRows(TBL_RATE, "id", "rate = 4 AND item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0");
      $five = $db->countRows(TBL_RATE, "id", "rate = 5 AND item_id='$itemId' AND location_id='" . LOCATION . "' AND status = 0");

      $rate1 = $one && $one > 0 ? $one / ($one + $two + $three + $four + $five) : 0;
      $rate2 = $two && $two > 0 ? $two / ($one + $two + $three + $four + $five) : 0;
      $rate3 = $three && $three > 0 ? $three / ($one + $two + $three + $four + $five) : 0;
      $rate4 = $four && $four > 0 ? $four / ($one + $two + $three + $four + $five) : 0;
      $rate5 = $five && $five > 0 ? $five / ($one + $two + $three + $four + $five) : 0;

      $obj = ["one" => $rate1, "two" => $rate2, "three" => $rate3, "four" => $rate4, "five" => $rate5];

      return $obj;
    }
  }