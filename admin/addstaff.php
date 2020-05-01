<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
require_once '../googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();
$secretish = $ga->createSecret();

foreach($_POST as $key => $data) {
	if($key!="button"){
		echo ('$'.$key.' = $_POST["'.$key.'"]   :   '.$data.'<br>');
	}
}

$user_prefix = $_POST["user_prefix"];
$staff_first_name = sanSlash($_POST["staff_first_name"]);
$staff_last_name = sanSlash($_POST["staff_last_name"]);
$staff_user_name = sanSlash($_POST["staff_user_name"]);
$staff_email = sanSlash($_POST["staff_email"]);
$staff_password = sanSlash($_POST["staff_password"]);
$agent_level = $_POST["agent_level"];
$staff_destruct_date = $_POST["staff_destruct_date"];
$staff_phone = sanSlash($_POST["staff_phone"]);

$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);



    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `tbl_fsadmin` (`user_prefix`, `first_name`, `last_name`, `user_name`, `password`, `email_address`, `telephone`, `agent_level`, `destruct_date`, `verification_code`, `confirmed_by`, `confirmed_date`) VALUES ('$user_prefix', '$staff_first_name', '$staff_last_name', '$staff_user_name', '$staff_password', '$staff_email', '$staff_phone', '$agent_level', '$staff_destruct_date', '$secretish', '$name', '$str_date')";

    $conn->exec($sql);

$conn = null;

header("location:staff.php");

?>
