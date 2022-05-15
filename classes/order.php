<?php
  class Order {
    public function createOrder($payType, $delivery) {
      global $db, $fun, $cart;
      
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

          $db->create(TBL_ORDER, "token='$token', item_id='$itemId', vat_value='$vatValue', category_id='$categoryId', qty='$qty', price='$price', delivery_fee='$delivery', amount='$amount', location_id='" . LOCATION . "', customer_id='" . ID . "', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME . "', status = 0");

        }

        // transaction will be saved here

        $db->delete(TBL_CART, "customer_id='" . ID . "' AND location_id='" . LOCATION . "' AND status = 0");
        // token will be saved here
        $fun->saveToken($token);
      }

    }

    public function orderRows($fields="*", $query="", $column="") {
      global $db;
      
      $queries = !empty($query) ? "$query AND" : "";
      return $db->fetch(TBL_ORDER, $fields, "$queries status=0", $column);
    }

    public function getOrder($fields="*", $conditions="", $col="") {
      global $db;
      
      $con = !empty($conditions) ? "AND $conditions" : "";
      return $this->orderRows($fields, "location_id='" . LOCATION . "' $con", $col);
    }

   
    public function getOrderData() {
      global $prc, $ing;

      $data = [];
      $response = $this->getItem();

      if($response){
        foreach($response as $res) {
          $itemId=$res['id'];
          $price = $prc->getItemPrice("price", "item_id='$itemId'", "price");
          $ingredients = $ing->getItemIngredients("id, name, item_id, status", "item_id='$itemId'");
          $data[] = array_merge($res, ["price" => $price, "ingredients" => $ingredients]);
        }
      }

      return $data;
    }
  }