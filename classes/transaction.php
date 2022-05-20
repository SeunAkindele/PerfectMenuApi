<?php
  class Transaction {
    public function createTxn($payType, $delivery, $token, $onlineToken="") {
      global $db, $cart;

      $amount = $cart->sumCartAmount();
      $vatValue = $cart->sumCartVat();
      
      $db->create(TBL_TXN, "token='$token', online_token='$onlineToken', vat_value='$vatValue', delivery_fee='$delivery', amount='$amount', pay_type='$payType', location_id='" . LOCATION . "', customer_id='" . ID . "', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME . "', status = 2");
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

    public function cancleTxn($token) {
      global $db;

      $db->update(TBL_TXN, "status = 3", "token='$token' AND customer_id='" . ID . "' AND location_id='" . LOCATION . "' AND status = 2");
    }

    public function confirmDeliveryTxn($token) {
      global $db;
      
      $db->update(TBL_TXN, "status = 0", "token='$token' AND customer_id='" . ID . "' AND location_id='" . LOCATION . "' AND status = 1");
    }
  }
  