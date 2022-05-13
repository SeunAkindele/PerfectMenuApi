<?php
  // error_reporting(0);

  require "db.php";
  require "../constants/index.php";
  require "functions.php";
  require "user.php";
  require "../vendor/firebase/php-jwt/src/JWT.php";

  use \Firebase\JWT\JWT;
  
  $db = new Db; 
  $fun = new Functions;
  $usr = new Users;
  $jwt = new JWT;
  $db->connect();

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  $json = file_get_contents('php://input');
  $body = json_decode($json, true);