<?php
require "../classes/token_init.php";
  
$page = !empty($body['request']['page']) ? $body['request']['page'] : $_REQUEST['page'];

if($page == "getLocations") {
  $locations = $loc->getLocation();
  $fun->jsonResponse(true, $locations, "200");
}