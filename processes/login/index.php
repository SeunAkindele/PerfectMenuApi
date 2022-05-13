<?php

  require "../classes/init.php";
  
  $email = !empty($body['request']['email']) ? $db->escape($body['request']['email']) : $_REQUEST['email'];
  $password = !empty($body['request']['password']) ? $db->escape($body['request']['password']) : $_REQUEST['password'];

  if($fun->checkEmptyInput([$email, $password])) {
    $fun->jsonResponse(false, "None of the fields must be empty", "400");
  }
  
  // authenticating user
  $usr->authenticateUser($email, $password);