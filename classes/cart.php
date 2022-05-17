<?php
  class Cart {
    public function addCart($itemId) {
      global $db;
      
      if($db->countRows(TBL_CART, "*", "item_id='$itemId' AND customer_id='" . ID . "' AND status = 0")) {
        return $this->updateCart($itemId);
      } else {
        return $this->insertCart($itemId);
      }
    }

    public function insertCart($itemId) {
      global $db, $itm, $fun, $loc, $prc;

      $categoryId= $itm->getItemCategoryIdByItemId($itemId);
      $price = $prc->getItemPrice("price", "item_id='$itemId'", "price");
      $vatStatus = $itm->getItemVatStatus($itemId);
      $vatValue = $loc->getLocationVatValue();
      $calVatValue = $fun->calculateVatValue($vatStatus, $vatValue, $price);

      $db->create(TBL_CART, "item_id='$itemId', category_id='$categoryId', price='$price', amount='$price', qty=1, vat_value='$calVatValue', location_id='" . LOCATION . "', customer_id='" . ID . "', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME . "', status = 0");
    }

    public function updateCart($itemId, $operator="") {
      global $db, $itm, $fun, $loc;
      
      $vatStatus = $itm->getItemVatStatus($itemId);
      $vatValue = $loc->getLocationVatValue();
      $price = $this->getCartPriceByItemId($itemId);
      $qty = empty($operator) ? $this->getCartQtyByItemId($itemId) + 1 : $this->getCartQtyByItemId($itemId) - 1;
      $amount = $price * $qty;
      $calVatValue = $fun->calculateVatValue($vatStatus, $vatValue, $amount);

      $db->update(TBL_CART, "qty='$qty', amount='$amount', vat_value='$calVatValue'", "item_id='$itemId' AND customer_id='" . ID . "' AND location_id='" . LOCATION . "' AND status = 0");
    }

    public function getCartPriceByItemId($itemId) {
      return $this->getCart("price", "item_id='$itemId' AND customer_id='" . ID . "'", "price");
    }

    public function getCartQtyByItemId($itemId) {
      return $this->getCart("qty", "item_id='$itemId' AND customer_id='" . ID . "'", "qty");
    }

    public function cartRows($fields="*", $query="", $column="") {
      global $db;
      
      $queries = !empty($query) ? "$query AND" : "";
      return $db->fetch(TBL_CART, $fields, "$queries status=0", $column);
    }

    public function getCart($fields="*", $conditions="", $col="") {
      $con = !empty($conditions) ? "AND $conditions" : "";
      return $this->cartRows($fields, "location_id='" . LOCATION . "' $con", $col);
    }

    public function sumCartAmount() {
      return $this->getCart("SUM(amount) AS AMT", "customer_id='" . ID . "'", "AMT");
    }

    public function sumCartVat() {
      return $this->getCart("SUM(vat_value) AS VAT", "customer_id='" . ID . "'", "VAT");
    }

    public function getCartData(){
      global $db, $itm;

      $carts=[];
      $data="";
      $response = $this->getCart("id, qty, price, vat_value, amount, item_id", "customer_id='" . ID . "'");

      if($response){
        foreach($response as $res){
          $itemId = $res['item_id'];
          $name = $itm->getItemName($itemId);
          $image = $itm->getItemImageName($itemId);
          $carts[] = array_merge($res, ["name" => $name, "image" => $image]);
        }

        $total = $this->sumCartAmount();
        $vat = $this->sumCartVat();

        $data = ["carts" => $carts,"total" => $total, "vat" => $vat];
      }
      
      return $data;
    }

    public function deleteCart($id, $itemId) {
      global $db;

      $db->delete(TBL_CART, "id='$id' AND item_id='$itemId' AND customer_id='" . ID. "' AND location_id='" . LOCATION . "' AND status = 0");
    }
  }