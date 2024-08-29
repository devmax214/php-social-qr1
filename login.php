<?php 
require_once('functions.php');

/** Login Api
 *
 * params: 
 * 		uid: user id
 *		pasword: user password
 */
if($_SERVER['REQUEST_METHOD'] != 'POST') {
	exit();
}

$request = json_decode(file_get_contents('php://input'));

$email = $request->email;
$password = $request->password;

$user = get_userinfo($email, md5($password));
$response = array();

if(count($user) > 0) {
	$response['result'] = 'success';
	$response['data'] = $user;
}
else {
	$response['result'] = 'fail';
	$response['data'] = 'Not found!';
}

exit(json_encode($response));