<?php 
require_once('functions.php');

/* Facebook Sign Up Api
 *
 * params: 
 *		email: email
 *		fb_id: facebook id
 */
if($_SERVER['REQUEST_METHOD'] != 'POST') {
	exit();
}

$request = json_decode(file_get_contents('php://input'));

$name = $request->name;
$email = $request->email;
$fb_id = $request->fb_id;

$result = array();

if(user_exist($email)) {
	$updates = array('name'=>$name, 'fb_id'=>$fb_id);
	if(user_update('users', $updates, '`email`="'. $email. '"')) {
		$result['result'] = "success";
	} 
	else {
		$result['result'] = "fail";
	}
}
else {
	$inserts = array('name'=>$name, 'email'=>$email, 'fb_id'=>$fb_id, 'created' => date('Y-m-d H:i:s'));
	if(user_add('users', $inserts)) {
		$result['result'] = "success";
	} 
	else {
		$result['result'] = "fail";
	}
}
exit(json_encode($result));
