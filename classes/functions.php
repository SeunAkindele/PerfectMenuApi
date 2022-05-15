<?php

class Functions {

  public function jsonResponse($success, $data, $status, $token = "") {
		$res['success'] = $success;
		$res['data'] = $data;
		$res['statusCode'] = $status;
		
		print_r(json_encode($res));
		
		exit;
	}

  // validate empty inputs
  public function checkEmptyInput($params){
    for($i = 0; $i < sizeof($params); $i++) {
      if($params[$i] === null || empty($params[$i])) {
        return true;
      }
    }
    return false;
  }

  public function getAuthorizationHeader() {
		$headers = null;
		if (isset($_SERVER['Authorization'])) {
			$headers = trim($_SERVER["Authorization"]);
		} else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
			//Nginx or fast CGI
			$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
		} elseif (function_exists('apache_request_headers')) {
			$requestHeaders = apache_request_headers();
			// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
			$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
			//print_r($requestHeaders);
			if (isset($requestHeaders['Authorization'])) {
				$headers = trim($requestHeaders['Authorization']);
			}
		}
		return $headers;
	}

	public function getBearerToken() {
		$headers = $this->getAuthorizationHeader();
		// HEADER: Get the access token from the header
		if (!empty($headers)) {
			if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
				return $matches[1];
			}
		}
		return null;
	}

	public function calculateVatValue($vatStatus, $vatValue, $amount){
		if($vatStatus > 0) {
			$value = ($vatValue / 100 ) * $amount;
			return $value;
		} else {
			return 0;
		}
	}

	public function token() {
		global $db;

		$count = $db->countRows(TBL_TOKEN, "*", "user_id='" . ID . "' AND location_id='" . LOCATION . "' AND date='" . CURRENT_DATE . "' AND status = 0");

		$d = explode("-", CURRENT_DATE);
		$dates = $d[2] . $d[1] . $d[0];
		$token = "PM" . $dates . ID . LOCATION . $count;
		
		return $token;
	}

	public function saveToken($token) {
		global $db;

		$db->create(TBL_TOKEN, "token='$token', location_id='" . LOCATION . "', user_id='" . ID . "', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME . "', status = 0");
	}
}