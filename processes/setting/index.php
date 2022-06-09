<?php

  require "../classes/token_init.php";
  
  $page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];
 
  if($page == "manageProfile") {
    $phone = !empty($body['request']['phone']) ? $db->escape($body['request']['phone']) : $_REQUEST['phone'];
    $password = !empty($body['request']['password']) ? $db->escape($body['request']['password']) : "";
    $address = !empty($body['request']['address']) ? $db->escape($body['request']['address']) : "";
  
    if($db->countRows(TBL_USER, "phone", "phone='$phone' AND status = 0 AND id !='" . ID . "'")) {
      $fun->jsonResponse(false, "This phone number already exist", "400");
    }  

    if(!empty($password)){
      $salt = $db->salt();
      $pwd = $db->hashPass($password, $salt);
      $db->update(TBL_USER, "password='$pwd', salt='$salt'", "id='" . ID . "' AND location_id='" . LOCATION . "' AND status = 0");
    }

    $db->update(TBL_USER, "phone='$phone', address='$address'", "id='" . ID . "' AND location_id='" . LOCATION . "' AND status = 0");

    $fun->jsonResponse(true, "Entry saved successfully. Kindly login again.", "200");

  }