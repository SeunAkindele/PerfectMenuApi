<?php

  class Users {
    public $name = "";
    public $email = "";
    public $type = "";

    public function userRows($fields="*", $query="", $column="") {
      global $db;
      
      $queries = !empty($query) ? "$query AND" : "";
      return $db->fetch(TBL_USER, $fields, "$queries status=0", $column);
    }

    public function getUser($conditions="", $col="") {
      global $db;
      
      $con = !empty($conditions) ? "AND $conditions" : "";
      return $this->userRows("*", "location_id='" . LOCATION . "' $con", $col);
    }

    public function compareUserLoginDetails($email, $password) {
      global $db;
      
      $salt = $db->getSalt($email);
      $pwd = $db->hashPass($password, $salt);
      return $this->userRows("*", "email='$email' AND password='$pwd'");
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

        if($res["status"] == 0) {
          $user = ["id" => $res['id'], "name" => $res['name'], "type" => $res['type'], "email" => $res['email'], "phone" => $res['phone'], "location_id" => $res['location_id'], "token" => $token];
          $fun->jsonResponse(true, $user, "200");
        } else {
          $fun->jsonResponse(false, "Oops, your account has been blocked, contact the customer care", "400");
        }
      }
      $fun->jsonResponse(false, "Invalid login details", "400");
    }
  
    public function authenticateUser($email, $password) {
      $response = $this->compareUserLoginDetails($email, $password);
      return $this->validateUser($response);
    }

  }