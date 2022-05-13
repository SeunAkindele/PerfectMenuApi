<?php
  // error_reporting(0);

  require "db.php";
  require "../constants/index.php";
  require "functions.php";
  require "user.php";
  require "item.php";
  require "itemCategory.php";
  require "price.php";
  require "ingredient.php";

  require "../vendor/firebase/php-jwt/src/JWT.php";

  use \Firebase\JWT\JWT;
  
  $db = new Db; 
  $fun = new Functions;
  $usr = new Users;
  $itm = new Items;
  $itmCat = new ItemCategory;
  $prc = new Price;
  $ing = new Ingredients;
  $db->connect();

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  $inputs = file_get_contents('php://input');
  
  $secret_key = "coder";
  
  $jwt = $fun->getBearerToken();
  
  function verifyBearerToken() {
    global $jwt, $secret_key;
    
    if (!empty($jwt)) {
      try {
        $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
        // Access is granted. Add code of the operation here
        http_response_code(200);
        return json_encode(array(
          "message" => "Access Granted",
          "data" => $decoded->data,
        ));
      } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array(
          "message" => "Access denied.",
          "data" => $e->getMessage(),
        ));exit;
      }
    }
  }
  
  $data = verifyBearerToken();
  $header = json_decode($data, true);
  $body = json_decode($inputs, true);

  define("ID", $header['data']['id']);
  define("LOCATION", $header['data']['location_id']);
  define("TYPE", $header['data']['type']);
  define("EMAIL", $header['data']['email']);