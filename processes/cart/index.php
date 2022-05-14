<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "addToCart") {
    $itemId = !empty($body['request']['itemId']) ? $db->escape($body['request']['itemId']) : $_REQUEST['itemId'];

    $cart->addCart($itemId);
    
    $fun->jsonResponse(true, "Entry saved successfully", "200");
  } else if($page == "getCart") {

    $cartItems = $cart->getCart("*", "customer_id='" . ID . "'");
    $fun->jsonResponse(true, $cartItems, "200");
  }