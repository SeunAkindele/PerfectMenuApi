<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "createStaff") {
    $email = !empty($body['request']['email']) ? $db->escape($body['request']['email']) : $_REQUEST['email'];
    $name = !empty($body['request']['name']) ? $db->escape($body['request']['name']) : $_REQUEST['name'];
    $type = !empty($body['request']['type']) ? $db->escape($body['request']['type']) : $_REQUEST['type'];
    $phone = !empty($body['request']['phone']) ? $db->escape($body['request']['phone']) : $_REQUEST['phone'];
    $location = !empty($body['request']['location']) ? $db->escape($body['request']['location']) : $_REQUEST['location'];

    if($db->countRows(TBL_USER, "email", "email='$email' AND status = 0")) {
      $fun->jsonResponse(false, "This email already exist", "400");
    }
  
    if($db->countRows(TBL_USER, "phone", "phone='$phone' AND status = 0")) {
      $fun->jsonResponse(false, "This phone number already exist", "400");
    }  

    $usr->createStaff($name, $email, $phone, $type, $location);

    $fun->jsonResponse(true, "Entry saved successfully", "200");

  } else if($page == "getOrder") {
    $orders = $ord->getCustomerOrder("", "present");
    $fun->jsonResponse(true, $orders, "200");
    
  } else if($page == "getPastOrder") {
    $pastOrder = $ord->getCustomerOrder("date < '" . CURRENT_DATE . "'", "past");
    $fun->jsonResponse(true, $pastOrder, "200");

  }else if($page == "getCustomerOrder") {
    $customerId = !empty($body['request']['customerId']) ? $body['request']['customerId'] : $_REQUEST['customerId'];
    $customerOrder = $ord->getCustomerOrder("", "", $customerId);
    $fun->jsonResponse(true, $customerOrder, "200");

  }else if($page == "getCustomerPastOrder") {
    $customerId = !empty($body['request']['customerId']) ? $body['request']['customerId'] : $_REQUEST['customerId'];
    $customerPastOrder = $ord->getCustomerOrder("date < '" . CURRENT_DATE . "'", "", $customerId);
    $fun->jsonResponse(true, $customerPastOrder, "200");

  }else if($page == "cancleOrder") {
    $token = !empty($body['request']['token']) ? $body['request']['token'] : $_REQUEST['token'];
    $customerId = !empty($body['request']['customerId']) ? $body['request']['customerId'] : $_REQUEST['customerId'];
    $txn->cancleTxn($token, $customerId);
    $ord->cancleOrder($token, $customerId);
    $fun->jsonResponse(true, "Entry deleted successfully", "200");
    
  } else if($page == "dispatchOrder") {
    $token = !empty($body['request']['token']) ? $body['request']['token'] : $_REQUEST['token'];
    $customerId = !empty($body['request']['customerId']) ? $body['request']['customerId'] : $_REQUEST['customerId'];
    $txn->dispatchOrderTxn($token, $customerId);
    $ord->dispatchOrder($token, $customerId);
    $fun->jsonResponse(true, "Entry confirmed successfully", "200");
    
  } else if($page == "getStaffs") {
    $staffs = $usr->getUser("id, name, disabled_status, phone", "type > 0 AND email !='lagos@pm.com' AND id !='" . ID . "'");
    $fun->jsonResponse(true, $staffs, "200");

  } else if($page == "disableStaff") {
    $staffId = !empty($body['request']['staffId']) ? $body['request']['staffId'] : $_REQUEST['staffId'];
    $db->update(TBL_USER, "disabled_status=1", "id='$staffId' AND status = 0 AND type=1");
    $fun->jsonResponse(true, "Staff updated successfully", "200");

  } else if($page == "enableStaff") {
    $staffId = !empty($body['request']['staffId']) ? $body['request']['staffId'] : $_REQUEST['staffId'];
    $db->update(TBL_USER, "disabled_status=0", "id='$staffId' AND status = 0 AND type=1");
    $fun->jsonResponse(true, "Staff updated successfully", "200");

  }else if($page == "getStaffOrder") {
    $staffId = !empty($body['request']['staffId']) ? $body['request']['staffId'] : $_REQUEST['staffId'];
    $staffOrder = $ord->getCustomerOrder("", "present", "", $staffId);
    $fun->jsonResponse(true, $staffOrder, "200");

  }else if($page == "getStaffPastOrder") {
    $staffId = !empty($body['request']['staffId']) ? $body['request']['staffId'] : $_REQUEST['staffId'];
    $staffPastOrder = $ord->getCustomerOrder("date < '" . CURRENT_DATE . "'", "past", "", $staffId);
    $fun->jsonResponse(true, $staffPastOrder, "200");

  }