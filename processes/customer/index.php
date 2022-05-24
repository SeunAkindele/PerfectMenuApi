<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "getCustomers") {
    $customers = $usr->getUser("id, name, disabled_status, phone", "type=0");
    $fun->jsonResponse(true, $customers, "200");

  }