<?php
  class Order {
    public function createOrder($payType, $delivery) {
      global $db, $fun, $cart, $txn;
      
      $cartData = $cart->getCart("*", "customer_id='" . ID . "' AND location_id='" . LOCATION . "' AND status = 0");
      
      if($cartData){
        // token will be fetched here
        $token = $fun->token();
        
        foreach($cartData as $cd) {
          $itemId = $cd['item_id'];
          $categoryId = $cd['category_id'];
          $vatValue = $cd['vat_value'];
          $qty = $cd['qty'];
          $price = $cd['price'];
          $amount = $cd['amount'];

          $db->create(TBL_ORDER, "token='$token', item_id='$itemId', vat_value='$vatValue', category_id='$categoryId', qty='$qty', price='$price', amount='$amount', location_id='" . LOCATION . "', customer_id='" . ID . "', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME . "', status = 2");
        }

        // transaction will be saved here
        $txn->createTxn($payType, $delivery, $token);

        $db->delete(TBL_CART, "customer_id='" . ID . "' AND location_id='" . LOCATION . "' AND status = 0");
        // token will be saved here
        $fun->saveToken($token);
      }
    }

    public function orderRows($fields="*", $query="", $column="") {
      global $db;
      
      return $db->fetch(TBL_ORDER, $fields, $query, $column);
    }

    public function sumOrderAmount() {
      return $this->getOrder("SUM(amount) AS AMT", "customer_id='" . ID . "'", "AMT");
    }

    public function sumOrderVat() {
      return $this->getOrder("SUM(vat_value) AS VAT", "customer_id='" . ID . "'", "VAT");
    }

    public function getOrder($fields="*", $conditions="", $col="") {
      global $db;
      
      $con = !empty($conditions) ? "AND $conditions" : "";
      return $this->orderRows($fields, "location_id='" . LOCATION . "' $con", $col);
    }

    public function getOrderData() {
     
    }
  }