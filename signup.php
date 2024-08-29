<?php 
require_once('functions.php');

/* Sign Up Api
 *
 * params: 
 *		uid: user id
 *		pasword: user password
 */
if($_SERVER['REQUEST_METHOD'] != 'POST') {
	exit();
}

$request = json_decode(file_get_contents('php://input'));

$name = $request->name;
$email = $request->email;
$password = $request->password;

$result = array();

if(user_exist($email)) {
	$result['result'] = "exist";
}
else {
	$inserts = array('name'=>$name, 'email'=>$email, 'password'=>md5($password), 'created' => date('Y-m-d H:i:s'));
	if(user_add('users', $inserts)) {
		$result['result'] = "success";
	} 
	else {
		$result['result'] = "fail";
	}
}
exit(json_encode($result));
