<?php
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, X-PINGOTHER, viewerTZOffset");
// header("Access-Control-Allow-Credentials: true");

function db_connection(){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "white_estate_db";
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    //die("Connection failed: " . $conn->connect_error);
	    return false;
	} 
	//echo "Connected successfully";
	
	return $conn;
}

function close_db_connection($link){
	$link->close();
}

function user_exist($email){
	$users = array();
	$conn = db_connection();
	$query = "SELECT * FROM users WHERE email='{$email}'";
	$result = $conn->query($query);
	
	if (!$result) {
	    die('Could not query:' . mysql_error());
	}
	else {
		while ($row = $result->fetch_assoc()) {
		    $users[] = $row;
		}
	}
	close_db_connection($conn);

	if(count($users) > 0) {
		return true;
	}
	else {
		return false;
	}
}

function get_userinfo($email, $password){
	$user = array();
	$conn = db_connection();
	$query = "SELECT user_id, name, email FROM users WHERE email='{$email}' AND password='{$password}'";
	$result = $conn->query($query);
	
	if (!$result) {
	    die('Could not query:' . mysql_error());
	}
	else {
		if ($row = $result->fetch_assoc()) {
		    $user = $row;
		}
	}
	close_db_connection($conn);
	return $user;
	
}

function user_add($table, $inserts) {
	$conn = db_connection();
    $values = array_map(array($conn, 'real_escape_string'), array_values($inserts));
    
    $keys = array_keys($inserts);
    $query = 'INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')';
    $return = $conn->query($query);
    close_db_connection($conn);
    return $return;
}

function user_update($table, $updates, $where) {
	$conn = db_connection();
    $values = array_map(array($conn, 'real_escape_string'), array_values($updates));
    $keys = array_keys($updates);
    $fields = "";
    foreach($updates as $key=>$value) {
    	if($fields != "") $fields .= ',';
    	$fields .= '`'. $key. '`="'. $value. '"';
    }
    $query = 'UPDATE `'.$table.'` SET '. $fields. ' WHERE '. $where;
    $return = $conn->query($query);
    close_db_connection($conn);
    return $return;
}
