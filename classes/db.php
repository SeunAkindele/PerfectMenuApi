<?php
  class Db {

    private $db_host = "localhost";
    private $db_user = "root";
    private $db_pass = "";
    private $db_name = "perfect_menu";

    private $con = false;
    private $myconn = "";
    private $result = array();
    private $myQuery = "";
    private $numResults = "";

  // Function to make connection to database
    public function connect() {
      if (!$this->con) {
        $this->myconn = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name); // mysql_connect() with variables defined at the start of Database class
        if ($this->myconn->connect_errno > 0) {
          array_push($this->result, $this->myconn->connect_error);
          return false; // Problem selecting database return FALSE
        } else {
          $this->con = true;
          return true; // Connection has been made return TRUE
        }
      } else {
        return true; // Connection has already been made return TRUE
      }
    }

  // Function to disconnect from the database
    public function disconnect() {
  // If there is a connection to the database
      if ($this->con) {
  // We have found a connection, try to close it
        if ($this->myconn->close()) {
          // We have successfully closed the connection, set the connection variable to false
          $this->con = false;
          // Return true that we have closed the connection
          return true;
        } else {
          // We could not close the connection, return false
          return false;
        }
      }
    }

    public function create($table, $fields) {
      return $this->sql("INSERT INTO " . $table . "  SET " . $fields);
    }

    public function delete($table, $conditions) {
      return $this->sql("DELETE FROM " . $table . "  WHERE " . $conditions);
    }

    public function countRows($table, $field = '*', $conditions) {
      $fields = trim($field);
      $this->sql("SELECT " . $fields . " FROM " . $table . "  WHERE " . $conditions);
      return $this->numRows();
    }

    public function fetch($table, $field = '*', $conditions, $column = '') {
      $fields = trim($field);
      $rt = $this->sql("SELECT " . $fields . " FROM " . $table . "  WHERE " . $conditions);
      $rlt = array_filter($this->getResult());
      $i = "";
      if (empty($rlt)) {
        unset($rlt);
      } else {
        $i = !empty($column) ? $rlt[0][$column] : $rlt;
      }
      return $i;
    }

    public function update($table, $fields, $conditions) {
      return $this->sql("UPDATE " . $table . "  SET " . $fields . " WHERE " . $conditions);
    }

    public function sql($sql) {
      //var_dump($sql); exit;
      $query = $this->myconn->query($sql);

      $this->myQuery = $sql; // Pass back the SQL
      if ($query) {
  // If the query returns >= 1 assign the number of rows to numResults
        $this->numResults = @$query->num_rows;
  // Loop through the query results by the number of rows returned
        for ($i = 0; $i < $this->numResults; $i++) {
          $r = $query->fetch_array();
          $key = array_keys($r);
          for ($x = 0; $x < count($key); $x++) {
            // Sanitizes keys so only alphavalues are allowed
            if (!is_int($key[$x])) {
              if ($query->num_rows >= 1) {
                $this->result[$i][$key[$x]] = $r[$key[$x]];
              } else {
                $this->result = "";
              }

            }
          }
        }
        return true; // Query was successful
      } else {
        array_push($this->result, $this->myconn->error);
        return false; // No rows where returned
      }
    }

  // Public function to return the data to the user
    public function getResult() {
      $val = $this->result;
      $this->result = array();
      return $val;
    }

  //Pass the SQL back for debugging
    public function getSql() {
      $val = $this->myQuery;
      $this->myQuery = array();
      echo $val;
    }

  //Pass the number of rows back
    public function numRows() {
      $val = $this->numResults;
      $this->numResults = array();
      return $val;
    }

    public function getSalt($email) {
      return $this->fetch(TBL_USER, 'salt', "email='$email'", 'salt');
    }

    public function escape($data) {
      return strtolower(trim(addslashes($this->myconn->real_escape_string($data))));
    }

    public function hashPass($password, $salt) {
      $hash_password = hash("SHA512", base64_encode(str_rot13(hash("SHA512", str_rot13($salt . $password)))));
      return $hash_password;
    }

    public function salt() {
      $length = 45;
      return base64_encode(random_bytes(ceil(0.75 * $length)));
    }

    //just to check array structure
    public function arrayPrinter($array) {
      echo "<pre>";
      print_r($array);
      echo "</pre>";
    }
  

  }