<?php
include("connection.php");

$checkResult="";
if($_POST['code']){
$code=$connect->real_escape_string($_POST['code']);	
$secret = $_SESSION['secret'];

require_once 'googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();
$checkResult = $ga->verifyCode($secret, $code, 2);    // 2 = 2*30sec clock tolerance


if ($checkResult){
	$_SESSION['googleCode']	= $code;
	$_SESSION['loggedin'] = TRUE;
	header("location:client/home.php");
    exit;

} 
else{
	$_SESSION['loggedin'] = FALSE;
	header("location:device_confirmations.php");
    exit;
}

}

?>
