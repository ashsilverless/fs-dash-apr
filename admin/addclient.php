<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
require_once '../googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();
$secretish = $ga->createSecret();

$user_prefix = sanSlash($_POST['user_prefix']);
$first_name = sanSlash($_POST['first_name']);
$last_name = sanSlash($_POST['last_name']);
$destruct_date = sanSlash($_POST['destruct_date']);
$user_name = $user_prefix.' '.$first_name.' '.$last_name;
$password = sanSlash($_POST['password']);
$client_email = sanSlash($_POST['client_email']);
$telephone = sanSlash($_POST['telephone']);
$strategy = sanSlash($_POST['strategy']);
$fs_client_code = onlyNum($_POST['fs_client_code']);
$fs_client_desc = sanSlash($_POST['fs_client_desc']);

$telephone = onlyNum($_POST['telephone']);


$_POST['fs_isin_code'] !='' ? $fs_isin_code = sanSlash($_POST['fs_isin_code']) : $fs_isin_code = sanSlash($_POST['new_fs_isin_code']);

$fs_fund_sedol = sanSlash($_POST['fs_fund_sedol']);
$fs_product_type = sanSlash($_POST['fs_product_type']);
$fs_fund_name = sanSlash($_POST['fs_fund_name']);
$fs_designation = sanSlash($_POST['fs_designation']);


$name = $_SESSION['fs_admin_name'];


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);



    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `tbl_fsusers` (`fs_client_code`, `user_type`, `user_prefix`, `first_name`, `last_name`, `user_name`, `password`, `email_address`, `telephone`, `strategy`, `agent_level`, `destruct_date`, `googlecode`, `fs_client_desc`) VALUES ('$fs_client_code', 'user', '$user_prefix', '$first_name', '$last_name', '$user_name', '$password', '$client_email', '$telephone', '$strategy', '1', '$destruct_date', '$secretish', '$fs_client_desc')";

    $conn->exec($sql);

	$user_id = $conn->lastInsertId();

	if ($fs_isin_code!=''){
		$product_sql = "INSERT INTO `tbl_fs_client_products` (`fs_isin_code`, `fs_fund_sedol`, `fs_fund_name`, `fs_client_desc`, `fs_client_code`, `fs_client_name`, `fs_designation`, `fs_product_type`, `confirmed_by`, `confirmed_date`, `fs_client_id`) VALUES ('$fs_isin_code', '$fs_fund_sedol', '$fs_fund_name', '$fs_client_desc', '$fs_client_code', '$user_name', '$fs_designation', '$fs_product_type', '$name', '$str_date', '$user_id')";

		$conn->exec($product_sql);
	}

$conn = null;



header("location:clients.php");

?>
