<?php
  class Transaction {
    public function createTxn($payType, $delivery, $token) {
      global $db, $cart;

      $amount = $cart->sumCartAmount();
      $vatValue = $cart->sumCartVat();

      if($payType == "offline") {
        $db->create(TBL_TXN, "token='$token', vat_value='$vatValue', delivery_fee='$delivery', amount='$amount', pay_type='$payType', location_id='" . LOCATION . "', customer_id='" . ID . "', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME . "', status = 2");
      } else {

      }
    } 

    public function txnRows($fields="*", $query="", $column="") {
      global $db;
      
      return $db->fetch(TBL_TXN, $fields, "$query", $column);
    }

    public function getTxn($fields="*", $conditions="", $col="") {
      global $db;
      
      $con = !empty($conditions) ? "AND $conditions" : "";
      return $this->txnRows($fields, "location_id='" . LOCATION . "' $con", $col);
    }

   
    // public function getTxnData() {
    //   global $prc, $ing;

    //   $data = [];
    //   $response = $this->getTxn();

    //   if($response){
    //     foreach($response as $res) {
    //       $itemId=$res['id'];
    //       $price = $prc->getItemPrice("price", "item_id='$itemId'", "price");
    //       $ingredients = $ing->getItemIngredients("id, name, item_id, status", "item_id='$itemId'");
    //       $data[] = array_merge($res, ["price" => $price, "ingredients" => $ingredients]);
    //     }
    //   }

    //   return $data;
    // }
  }
  