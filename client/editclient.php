<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$user_prefix = sanSlash($_POST['user_prefix']);
$first_name = sanSlash($_POST['first_name']);
$last_name = sanSlash($_POST['last_name']);
$user_name = sanSlash($_POST['user_name']);
$email_address = sanSlash($_POST['email_address']);
$telephone = sanSlash($_POST['telephone']);

$newpassword = sanSlash($_POST['newpassword']);
$confirmpassword = sanSlash($_POST['confirmpassword']);
$hashToStoreInDb = password_hash($confirmpassword, PASSWORD_DEFAULT);

$client_code =$_POST['client_code'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if($confirmpassword!=""){
		$sql = "UPDATE `tbl_fsusers` SET `user_prefix`='$user_prefix', `first_name`='$first_name', `last_name`='$last_name', `user_name`='$user_name', `email_address`='$email_address', `telephone`='$telephone' , `password`='$confirmpassword'  , `password_hash`='$hashToStoreInDb' WHERE (`fs_client_code`='$client_code')";
	}else{
		$sql = "UPDATE `tbl_fsusers` SET `user_prefix`='$user_prefix', `first_name`='$first_name', `last_name`='$last_name', `user_name`='$user_name', `email_address`='$email_address', `telephone`='$telephone' WHERE (`fs_client_code`='$client_code')";
	}

    $conn->exec($sql);

$conn = null;

$_SESSION['name'] = $first_name;
$_SESSION['username'] = $user_name;

header("location:settings.php?msg=updated");
?>
