<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "deliveryFee") {
    $payType = "offline";
    $delivery = !empty($body['request']['delivery']) ? $body['request']['delivery'] : $_REQUEST['delivery'];
    $ord->createOrder($payType, $delivery);

    $fun->jsonResponse(true, "Entry saved successfully", "200");

  } else if($page == "getOrder") {

  }