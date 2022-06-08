<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "getDashboard") {
    $id  = TYPE == 1 ? "user_id='" . ID . "' AND " : "";

    $salesData = $ord->getSalesData($id);
    $orderStatusData = $ord->getOrderStatus($id);
    $mostSoldItem = $ord->getMostSoldItem();
    $customers = $usr->getActiveDisabledCustomers();
    $staffs = $usr->getActiveDisabledStaffs();
    $items = $itm->getActiveDisabledItems();

    $data=['salesData' => $salesData, 'orderStatusData' => $orderStatusData, 'mostSoldItem' => $mostSoldItem, "customers" => $customers, "staffs" => $staffs, "items" => $items];

    $fun->jsonResponse(true, $data, "200");
  }