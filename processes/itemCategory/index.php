<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "createItemCategory") {
    $name = !empty($body['request']['name']) ? $db->escape($body['request']['name']) : $_REQUEST['name'];

    if($db->countRows(TBL_ITEM_CATEGORY, "name", "name='$name' AND status = 0")) {
      $fun->jsonResponse(false, "This item category already exist", "400");
    }
    $itmCat->createItemCategory($name);

  } else if($page == "getItemCategories") {
    $response = $itmCat->getItemCategory("id, name"); 
    $fun->jsonResponse(true, $response, "200");
    
  }