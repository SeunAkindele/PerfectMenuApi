<?php  
  //error_reporting(0);
  $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
  $page = @end(explode('/', $path));

  switch ($page) { 
    case 'register':
      require 'register/index.php';
      break;
    case 'login':
      require 'login/index.php';
      break;
    case 'item':
      require 'item/index.php';
      break;
    case 'cart':
      require 'cart/index.php';
      break;
    case 'itemCategory':
      require 'itemCategory/index.php';
      break;
    case 'order':
      require 'order/index.php';
      break;
    default:
      break;
  }