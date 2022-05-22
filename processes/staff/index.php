<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "createStaff") {
    $email = !empty($body['request']['email']) ? $db->escape($body['request']['email']) : $_REQUEST['email'];
    $name = !empty($body['request']['name']) ? $db->escape($body['request']['name']) : $_REQUEST['name'];
    $type = !empty($body['request']['type']) ? $db->escape($body['request']['type']) : $_REQUEST['type'];
    $phone = !empty($body['request']['phone']) ? $db->escape($body['request']['phone']) : $_REQUEST['phone'];
    $location = !empty($body['request']['location']) ? $db->escape($body['request']['location']) : $_REQUEST['location'];

    if($db->countRows(TBL_USER, "email", "email='$email' AND status = 0")) {
      $fun->jsonResponse(false, "This email already exist", "400");
    }
  
    if($db->countRows(TBL_USER, "phone", "phone='$phone' AND status = 0")) {
      $fun->jsonResponse(false, "This phone number already exist", "400");
    }  

    $usr->createStaff($name, $email, $phone, $type, $location);

    $fun->jsonResponse(true, "Entry saved successfully", "200");

  } 