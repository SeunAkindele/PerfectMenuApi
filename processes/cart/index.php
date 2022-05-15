<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "addToCart") {

    $itemId = !empty($body['request']['itemId']) ? $db->escape($body['request']['itemId']) : $_REQUEST['itemId'];
    $cart->addCart($itemId);
    $fun->jsonResponse(true, "Entry saved successfully", "200");

  } else if($page == "getCart") {

    $cartItems = $cart->getCartData();
    $fun->jsonResponse(true, $cartItems, "200");

  } else if($page == "updateCart") {

    $itemId = !empty($body['request']['itemId']) ? $db->escape($body['request']['itemId']) : $_REQUEST['itemId'];
    $operator = !empty($body['request']['operator']) ? $db->escape($body['request']['operator']) : "";

    $cart->updateCart($itemId, $operator);
    $fun->jsonResponse(true, "Entry saved successfully", "200");

  } else if($page == "deleteCart") {
    $id = !empty($body['request']['id']) ? $db->escape($body['request']['id']) : $_REQUEST['id'];
    $itemId = !empty($body['request']['itemId']) ? $db->escape($body['request']['itemId']) : $_REQUEST['itemId'];

    $cart->deleteCart($id,  $itemId);
    $fun->jsonResponse(true, "Deleted successfully", "200");
  }