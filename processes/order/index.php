<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "deliveryFee") {
    $payType = "offline";
    $delivery = !empty($body['request']['delivery']) ? $body['request']['delivery'] : $_REQUEST['delivery'];
    $ord->createOrder($payType, $delivery);
    $fun->jsonResponse(true, "Entry saved successfully", "200");

  } else if($page == "getOrder") {
    $order = $ord->getCustomerOrder();
    $fun->jsonResponse(true, $order, "200");

  } else if($page == "getPastOrder") {
    $pastOrder = $ord->getCustomerOrder("date < '" . CURRENT_DATE . "'");
    $fun->jsonResponse(true, $pastOrder, "200");

  } else if($page == "cancleOrder") {
    $token = !empty($body['request']['token']) ? $body['request']['token'] : $_REQUEST['token'];
    $txn->cancleTxn($token);
    $ord->cancleOrder($token);
    $fun->jsonResponse(true, "Entry deleted successfully", "200");
    
  } else if($page == "confirmDelivery") {
    $token = !empty($body['request']['token']) ? $body['request']['token'] : $_REQUEST['token'];
    
    $txn->confirmDeliveryTxn($token);
    $ord->confirmDeliveryOrder($token);
    $fun->jsonResponse(true, "Entry confirmed successfully", "200");
    
  }