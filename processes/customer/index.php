<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "getCustomers") {
    $customers = $usr->getUser("id, name, disabled_status, phone", "type=0");
    $fun->jsonResponse(true, $customers, "200");

  } else if($page == "disableCustomer") {
    $customerId = !empty($body['request']['customerId']) ? $body['request']['customerId'] : $_REQUEST['customerId'];
    $db->update(TBL_USER, "disabled_status=1", "id='$customerId' AND status = 0 AND type=0");
    $fun->jsonResponse(true, "Customer updated successfully", "200");

  } else if($page == "enableCustomer") {
    $customerId = !empty($body['request']['customerId']) ? $body['request']['customerId'] : $_REQUEST['customerId'];
    $db->update(TBL_USER, "disabled_status=0", "id='$customerId' AND status = 0 AND type=0");
    $fun->jsonResponse(true, "Customer updated successfully", "200");

  }