<?php
include 'db.class.php';
$config = parse_ini_file('config.ini');
$uid = $_REQUEST['uid'];
$uname = $_REQUEST['uname'];
$utype = $_REQUEST['utype'];
$db = new db();
$usersQry = "SELECT * FROM users where user_type = '".$utype."' AND uid = '".$uid."'";
$usersRes = $db->getAll($usersQry);
if (count($usersRes) == 0) {
	$insertQry = "INSERT INTO users (uid, user_name, user_type) VALUES ('".$uid."', '".$uname."', '".$utype."')";
	$db->execute($insertQry);
	$userQry = "SELECT MAX(user_id) AS last_user_id FROM users";
	$userRes = $db->getOne($userQry);
	echo $userRes['last_user_id'];
	//echo 1;
} else {
	$updateQry = "UPDATE users SET user_name = '".$uname."' WHERE user_type = '".$utype."' AND uid = ".$uid;
	$db->execute($updateQry);
	echo $usersRes[0]['user_id'];
}
?>