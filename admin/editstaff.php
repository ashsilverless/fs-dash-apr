<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$staff_id = $_GET['id'];

$user_prefix = $_POST['user_prefix'];
$staff_first_name = sanSlash($_POST['staff_first_name']);
$staff_last_name =sanSlash($_POST['staff_last_name']);
$staff_email = sanSlash($_POST['staff_email']);
$staff_mobile = sanSlash($_POST['staff_mobile']);
$agent_level = $_POST['agent_level'];

$staff_user_name = sanSlash($_POST['staff_user_name']);
$staff_password = sanSlash($_POST['staff_password']);
$staff_destruct_date = date('Y-m-d', strtotime($_POST['staff_destruct_date']));


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if($_SESSION['agent_level'] <2 && $agent_level>1){
		$sql = "UPDATE `tbl_fsadmin` SET `user_prefix`='$user_prefix', `first_name`='$staff_first_name', `last_name`='$staff_last_name', `user_name`='$staff_user_name', `email_address`='$staff_email', `telephone`='$staff_mobile', `agent_level`='$agent_level', `confirmed_by`='$name', `confirmed_date`='$str_date' WHERE (`id`='$staff_id')";
	}else{
		$sql = "UPDATE `tbl_fsadmin` SET `user_prefix`='$user_prefix', `first_name`='$staff_first_name', `last_name`='$staff_last_name', `user_name`='$staff_user_name', `password`='$staff_password', `email_address`='$staff_email', `telephone`='$staff_mobile', `agent_level`='$agent_level', `destruct_date`='$staff_destruct_date', `confirmed_by`='$name', `confirmed_date`='$str_date' WHERE (`id`='$staff_id')";
	}


	

    $conn->exec($sql);

$conn = null;



header("location:staff.php");
?>