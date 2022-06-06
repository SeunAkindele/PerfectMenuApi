<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "getDashboard") {
    $id  = TYPE == 1 ? "user_id='" . ID . "' AND " : "";

    $salesData = $ord->getSalesData($id);
    $orderStatusData = $ord->getOrderStatus($id);

    $data=['salesData' => $salesData, 'orderStatusData' => $orderStatusData];

    $fun->jsonResponse(true, $data, "200");
  }