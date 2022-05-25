<?php

  class Users {
    
    public function userRows($fields="*", $query="", $column="") {
      global $db;
      
      $queries = !empty($query) ? "$query AND" : "";
      return $db->fetch(TBL_USER, $fields, "$queries status=0", $column);
    }

    public function getUser($fields="*", $conditions="", $col="") {
      $con = !empty($conditions) ? "$conditions" : "";
      return $this->userRows($fields, $con, $col);
    }

    public function compareUserLoginDetails($email, $password) {
      global $db;
      
      $salt = $db->getSalt($email);
      $pwd = $db->hashPass($password, $salt);
      return $this->getUser("*", "email='$email' AND password='$pwd'");
    }

    public function generateToken($res) {
      global $jwt;

      $dataArray = array("id" => $res['id'], "type" => $res['type'], "email" => $res['email'], "location_id" => $res['location_id']);
	    return $jwt->generateJWT($dataArray);
    }
  
    public function updateUserToken($id, $token) {
      global $db;
      
      $db->update(TBL_USER, "token='$token'", "id='$id' AND status = 0");
    }

    public function validateUser($response) {
      global $fun;

      if(!empty($response) && $response != null) {
        $res = $response[0];
        $token = $this->generateToken($res);
        // updating user record with token
        $this->updateUserToken($res['id'], $token);

        if($res["disabled_status"] == 0) {
          $user = ["id" => $res['id'], "name" => $res['name'], "type" => $res['type'], "email" => $res['email'], "phone" => $res['phone'], "location_id" => $res['location_id'], "token" => $token];
          $fun->jsonResponse(true, $user, "200");
        } else {
          $fun->jsonResponse(false, "Oops, your account has been blocked, contact the administrator", "400");
        }
      }
      $fun->jsonResponse(false, "Invalid login details", "400");
    }

    public function getUserName($id) {
      return $this->getUser("name", "id='$id'", "name");
    }

    public function getUserEmail($id) {
      return $this->getUser("email", "id='$id'", "email");
    }

    public function getUserPhone($id) {
      return $this->getUser("phone", "id='$id'", "phone");
    }
  
    public function authenticateUser($email, $password) {
      $response = $this->compareUserLoginDetails($email, $password);
      return $this->validateUser($response);
    }

    public function createStaff($name, $email, $phone, $type, $location) {
      global $db;
      
      $salt = $db->salt();
      $pwd = $db->hashPass("pm", $salt);
      
      $db->create(TBL_USER, "name='$name', email='$email', phone='$phone', type='$type', password='$pwd', salt='$salt', location_id='$location', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME ."', status=0");
      
    }

  }