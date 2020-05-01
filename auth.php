<?php
include("connection.php");

$my_t=getdate(date("U"));
$str_date=$my_t['year']."-".$my_t['mon']."-".$my_t['mday']." ".$my_t['hours'].":".$my_t['minutes'].":".$my_t['seconds'];

$checkResult="";
if($_POST['code']){
$code=$connect->real_escape_string($_POST['code']);
$secret = $_SESSION['fs_client_secret'];

require_once 'googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();
$checkResult = $ga->verifyCode($secret, $code, 2);    // 2 = 2*30sec clock tolerance


if ($checkResult){
	$_SESSION['fs_client_googleCode']	= $code;
	$_SESSION['fs_client_loggedin'] = TRUE;

	$mysql = db_query("UPDATE tbl_fsusers set last_logged_in = '".$str_date."' WHERE id = ".$_SESSION['fs_client_user_id'].";");

	header("location:client/home.php");
    exit;

}
else{
	$_SESSION['fs_client_loggedin'] = FALSE;
	header("location:device_confirmations.php");
    exit;
}

}

?>
