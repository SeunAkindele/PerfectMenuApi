<?php
  class Order {
    public function createOrder($payType, $delivery, $onlineToken="") {
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
        $txn->createTxn($payType, $delivery, $token, $onlineToken);

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

    public function getCustomerOrderByToken($token, $staff="") {
      $staff = !empty($staff) ? "$staff AND" : "";
      return $this->getOrder("id, item_id, price, amount, qty, status", "$staff token='$token'");
    }

    public function cancleOrder($token, $customerId ="") {
      global $db;

      $customer = !empty($customerId) ? "customer_id='$customerId'" : "customer_id='" . ID . "'";
      $userId = !empty($customerId) ? ", user_id='" . ID . "'" : "";
      $db->update(TBL_ORDER, "status = 3 $userId", "token='$token' AND $customer AND location_id='" . LOCATION . "' AND status = 2");
    }

    public function confirmDeliveryOrder($token) {
      global $db;

      $db->update(TBL_ORDER, "status = 0", "token='$token' AND customer_id='" . ID . "' AND location_id='" . LOCATION . "' AND status = 1");
    }

    public function dispatchOrder($token, $customerId) {
      global $db;

      $db->update(TBL_ORDER, "status = 1, user_id='" . ID . "'", "token='$token' AND customer_id='$customerId' AND location_id='" . LOCATION . "' AND status = 2");
    }

    public function getCustomerTxn($staff="") {
      global $txn;

      return $txn->getTxn("token, customer_id, delivery_fee, pay_type, amount, vat_value, status, date, user_id", $staff);
    }

    public function validateStaff($staff="", $date="", $customerId="", $staffId="") {
      if(empty($staff)) {
        $con = !empty($customerId) ? "$date AND customer_id='$customerId'" : "$date AND customer_id='" . ID . "'";
      } else {
        if($staff == "past") {
          $con = empty($staffId) ? "$date AND user_id='" . ID . "'" : "$date AND user_id='$staffId'";
        } else if($staff == "present") {
          $con = empty($staffId) ? $date : "$date AND user_id='$staffId'";
        }
      }

      return $con;
    }
    
    public function getCustomerOrder($date="", $staff="", $customerId="", $staffId="") {
      global $itm, $usr;

      $data=[]; $pending = [];
      $date = !empty($date) ? $date : "date='" . CURRENT_DATE . "'";
      $staff = $this->validateStaff($staff, $date, $customerId, $staffId);
      $response = $this->getCustomerTxn($staff);
      
      if($response){
        foreach($response as $res) {
          $arr=[];
          $token = $res['token'];
          $orders = $this->getCustomerOrderByToken($token, $staff);
          foreach($orders as $order) {
            $name = $itm->getItemName($order['item_id']);
            $arr[] = array_merge($order, ["name"=>$name]);
          }
          $customerName = $usr->getUserName($res['customer_id']);
          $customerPhone = $usr->getUserPhone($res['customer_id']);
          $staffName = $res['user_id'] ? $usr->getUserName($res['user_id']) : null;
          $data[] = array_merge($res, ['order'=>$arr, "customer_name" => $customerName, "customer_phone" => $customerPhone, "staff_name" => $staffName]);
          array_push($pending, $res['status']);
        }
        $name = $usr->getUserName(ID);
        $email = $usr->getUserEmail(ID);
        $phone = $usr->getUserPhone(ID);

        $obj=['data' => $data, 'pending' => $pending, 'name' => $name, 'email' => $email, 'phone' => $phone];

        return $obj;
      }
    }

    public function getSalesData($id="") {
  
      $today = 0; $one=0; $two=0; $third=0; $fourth=0; $fifth=0; $sixth=0;
  
      $today += $this->getOrder($fields="SUM(amount) AS AMT", "$id date='" . CURRENT_DATE . "' AND status = 0", "AMT");
      $one += $this->getOrder($fields="SUM(amount) AS AMT", "$id date='" . PREVIOUS_DATE . "' AND status = 0", "AMT");
      $two += $this->getOrder($fields="SUM(amount) AS AMT", "$id date='" . SECOND_PREVIOUS_DATE . "' AND status = 0", "AMT");
      $third += $this->getOrder($fields="SUM(amount) AS AMT", "$id date='" . THIRD_PREVIOUS_DATE . "' AND status = 0", "AMT");
      $fourth += $this->getOrder($fields="SUM(amount) AS AMT", "$id date='" . FOURTH_PREVIOUS_DATE . "' AND status = 0", "AMT");
      $fifth += $this->getOrder($fields="SUM(amount) AS AMT", "$id date='" . FIFTH_PREVIOUS_DATE . "' AND status = 0", "AMT");
      $sixth += $this->getOrder($fields="SUM(amount) AS AMT", "$id date='" . SIXTH_PREVIOUS_DATE . "' AND status = 0", "AMT");

      return [$today, $one, $two, $third, $fourth, $fifth, $sixth];
    }

    public function getOrderStatus($id="") {
      global $db;

      $pending = 0; $delivered = 0; $cancled = 0; $dispatched = 0;

      $pending += $db->countRows(TBL_ORDER, "id", "location_id='" . LOCATION . "' AND status = 2");
      $dispatched += $db->countRows(TBL_ORDER, "id", "$id date='" . CURRENT_DATE . "' AND location_id='" . LOCATION . "' AND status = 1");
      $cancled += $db->countRows(TBL_ORDER, "id", "$id date='" . CURRENT_DATE . "' AND location_id='" . LOCATION . "' AND status = 3");
      $delivered += $db->countRows(TBL_ORDER, "id", "$id date='" . CURRENT_DATE . "' AND location_id='" . LOCATION . "' AND status = 0");

      $total = $pending + $dispatched + $cancled + $delivered;

      return ["pending" => $pending, "dispatched" => $dispatched, "cancled" => $cancled, "delivered" => $delivered, "total" => $total];
    }
  }