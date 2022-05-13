<?php
  require "../classes/init.php";

  $name = !empty($body['request']['name']) ? $db->escape($body['request']['name']) : $_REQUEST['name'];
  $email = !empty($body['request']['email']) ? $db->escape($body['request']['email']) : $_REQUEST['email'];
  $phone = !empty($body['request']['phone']) ? $db->escape($body['request']['phone']) : $_REQUEST['phone'];
  $password = !empty($body['request']['password']) ? $db->escape($body['request']['password']) : $_REQUEST['password'];

  // checking for empty inputs
  if($fun->checkEmptyInput([$name, $email, $phone, $password])) {
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

  $db->create(TBL_USER, "name='$name', email='$email', phone='$phone', type=0, password='$pwd', salt='$salt', location_id=1, date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME ."', status=0");

  $fun->jsonResponse(true, "Entry saved successfully", "200");