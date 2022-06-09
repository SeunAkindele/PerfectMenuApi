<?php
  require "../classes/init.php";

  if(!empty($body['request']['page'])){
    if($body['request']['page'] == "getLocations") {
      $locations = $loc->getLocation();
      $fun->jsonResponse(true, $locations, "200");
    }
  }

  $name = !empty($body['request']['name']) ? $db->escape($body['request']['name']) : $_REQUEST['name'];
  $email = !empty($body['request']['email']) ? $db->escape($body['request']['email']) : $_REQUEST['email'];
  $phone = !empty($body['request']['phone']) ? $db->escape($body['request']['phone']) : $_REQUEST['phone'];
  $password = !empty($body['request']['password']) ? $db->escape($body['request']['password']) : $_REQUEST['password'];
  $location = !empty($body['request']['location']) ? $db->escape($body['request']['location']) : $_REQUEST['location'];
  $address = !empty($body['request']['address']) ? $db->escape($body['request']['address']) : $_REQUEST['address'];

  // checking for empty inputs
  if($fun->checkEmptyInput([$name, $email, $phone, $password, $address])) {
    $fun->jsonResponse(false, "None of the fields must be empty", "400");
  }

  // encrypting password
  $salt = $db->salt();
  $pwd = $db->hashPass($password, $salt);

  // validating & creating customer
  if($db->countRows(TBL_USER, "email", "email='$email' AND status = 0")) {
    $fun->jsonResponse(false, "This email already exist", "400");
  }

  if($db->countRows(TBL_USER, "phone", "phone='$phone' AND status = 0")) {
    $fun->jsonResponse(false, "This phone number already exist", "400");
  }

  $db->create(TBL_USER, "name='$name', email='$email', phone='$phone', address='$address', type=0, password='$pwd', salt='$salt', location_id='$location', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME ."', disabled_status = 0, status=0");

  $fun->jsonResponse(true, "Entry saved successfully", "200");