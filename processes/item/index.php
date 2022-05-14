<?php

  require "../classes/token_init.php";
  
  if(isset($_FILES['image']['name'])){
    $data = explode("_", $_FILES['image']['name']);
    $filename = $data[0];
    $forms = $data[1];
    $form = explode("-", $forms);
    $tmp = $_FILES['image']['tmp_name'];
    $page2 = $form[0];

    if($page2 == "createItem") {
      $name = $form[1];
      $price = $form[2];
      $ingredients = !empty($form[3]) ? explode(",", $form[3]) : "";
      $categoryId = $form[4];
      $vatStatus = $form[5];
  
      if($db->countRows(TBL_ITEM, "name", "name='$name' AND status = 0")) {
        $fun->jsonResponse(false, "This item already exist", "400");
      }
  
      $itm->createItem($filename, $name, $price, $ingredients, $categoryId, $vatStatus, $tmp);
    } 
  }

  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
  
  if($page == "getItems") {
    $response = $itm->getItemData(); 
    $fun->jsonResponse(true, $response, "200");
    
  } else if($page == "getCartNum") {
    $cartNum = $db->countRows(TBL_CART, "id", "customer_id='" . ID . "' AND status = 0");
    $fun->jsonResponse(true, $cartNum, "200");
  }