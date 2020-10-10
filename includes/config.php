<?php 

date_default_timezone_set("Asia/Karachi");
session_start();

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
  $link = "https://$_SERVER[HTTP_HOST]/formulier/"; 
else
  $link = "http://$_SERVER[HTTP_HOST]/formulier/"; 

define('URL', $link);


/* DATABASE SETTINGS */
define('SERVERNAME', 'localhost\SQLEXPRESS');
define('DB_USER', 'formapplication');
define('DB_PASSWORD', '');
define('DB_NAME', '');
/* ../ DATABASE SETTINGS */

if (isset($_GET['logout'])) {
	session_destroy();
	session_unset();

	header('Location: ' . URL);
	exit;
}

function clean_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function vd($variable, $exit = true) {
	echo '<pre>';
	var_dump($variable);
	echo '</pre>';
	if ($exit) {
		exit;
	}
}

class DB {
	public $conn;
	
	function __construct() {
		$serverName = "localhost\SQLEXPRESS";
	  $connectionOptions = array(
	      "Database" => "formapplication",
	      "Uid" => "",
	      "PWD" => ""
	  );
		$this->conn = sqlsrv_connect($serverName, $connectionOptions)
			or die("<h1>Database connection failed</h1>");
	}

	function query($conn, $sql) {
		return $results = mysqli_query($conn, $sql);
	}

	function FormatErrors( $errors ) {
    /* Display errors. */
    echo "Error information: ";

    foreach ( $errors as $error )
    {
        echo "SQLSTATE: ".$error['SQLSTATE']."<br>";
        echo "Code: ".$error['code']."<br>";
        echo "Message: ".$error['message']."<br>";
    }
	}

	function single_row($sql) {
		$getResults= sqlsrv_query($this->conn, $sql);
		if ($getResults == FALSE) {
			die($this->FormatErrors(sqlsrv_errors()));
		}
  	return sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
	}

	function multiple_row($sql) {
		$getResults = sqlsrv_query($this->conn, $sql);
		if ($getResults == FALSE) die($this->FormatErrors(sqlsrv_errors()));
  	$data = Array();
		while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
		  array_push($data, $row);
		}
		return $data;
	}
	// table , column name, value
	function insert($table, $array) {
		$q1 = "insert into $table ";
		$i =0;
		$col = '';
		$val = '';
		$params = Array();
		foreach($array as $k=>$v){
			$col .= $k;
			$val .= '?,';
			array_push($params, $v);

			if($i< count($array)-1){
				$col .=', ';
			}
			$i++;
		}
		$tsql  = $q1."(".$col.") values (".rtrim($val, ',').")";
		$getResults= sqlsrv_query($this->conn, $tsql, $params);
		if ($getResults == FALSE) {
			die($this->FormatErrors(sqlsrv_errors()));
		} else {
			return true;
		}
	}

	function update ($table, $array, $conditions) { // give value as ["id" => 3, "name" => "qaisar"] array format
		$sql = "UPDATE $table SET";
		$array_length = count($array);
		$params = Array();
		if (count($array) > 0) {
      foreach ($array as $key => $value) {
        $updates[] = "$key = ?";
        array_push($params, $value);
      }
    }
    $implode_updates_Array = implode(', ', $updates);
    if (count($conditions) > 0) {
    	foreach ($conditions as $key => $value) {
    		$conditions_array[] = "$key = ?";
    		array_push($params, $value);
    	}
    }
    $implode_conditions_Array = implode(' AND ', $conditions_array);
    $sql = "UPDATE $table SET $implode_updates_Array WHERE $implode_conditions_Array";
    $getResults= sqlsrv_query($this->conn, $sql, $params);
		if ($getResults == FALSE) {
			die($this->FormatErrors(sqlsrv_errors()));
		} else {
			return true;
		}
	}

	function delete($table, $array) {
		if (count($array) > 0) {
      foreach ($array as $key => $value) {
        $value = "'$value'";
        $conditions[] = "$key = $value";
      }
    }
    $imploded_array = implode(' AND ', $conditions);
		$sql = "DELETE FROM $table WHERE $imploded_array";
    $getResults= sqlsrv_query($this->conn, $sql);
		if ($getResults == FALSE) {
			die($this->FormatErrors(sqlsrv_errors()));
		} else {
			return true;
		}
	}

	function delete_table($table) {
		$tsql = "DROP TABLE {$table}";
		$getResults= sqlsrv_query($this->conn, $tsql);
		if ($getResults == FALSE) {
			die($this->FormatErrors(sqlsrv_errors()));
		}
	}

	function create_table($query) {
		$getResults= sqlsrv_query($this->conn, $query);
		if ($getResults == FALSE) {
			die($this->FormatErrors(sqlsrv_errors()));
		}
	}
}

$db = new DB;