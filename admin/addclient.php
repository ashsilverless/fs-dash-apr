<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

/*
tbl_fsusers
  `id` int(10) NOT NULL AUTO_INCREMENT,
Y  `` varchar(100) DEFAULT NULL,

Y  `` varchar(50) DEFAULT NULL,
Y  `` varchar(250) DEFAULT NULL,
Y  `` varchar(250) DEFAULT NULL,
Y  `` varchar(250) DEFAULT NULL,
Y  `` varchar(250) DEFAULT NULL,
Y  `` varchar(250) DEFAULT NULL,
Y  `` varchar(100) DEFAULT NULL,
Y  `` varchar(20) DEFAULT '0',


  `` date DEFAULT NULL,

  `linked_accounts` varchar(100) DEFAULT NULL,
Y  `` varchar(100) DEFAULT NULL,

*/

$user_prefix = sanSlash($_POST['user_prefix']);
$first_name = sanSlash($_POST['first_name']);
$last_name = sanSlash($_POST['last_name']);
$user_name = sanSlash($_POST['user_name']);
$password = sanSlash($_POST['password']);
$email_address = sanSlash($_POST['email_address']);
$telephone = sanSlash($_POST['telephone']);
$strategy = sanSlash($_POST['strategy']);
$fs_client_code = sanSlash($_POST['fs_client_code']);
$destruct_date = sanSlash($_POST['destruct_date']);
$designator = sanSlash($_POST['designator']);


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);



    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `tbl_fsusers` (`fs_peer_name`, `fs_peer_return`, `fs_peer_volatility`, `fs_peer_color`, `confirmed_by`, `confirmed_date`, `fs_trend_line`) VALUES ('$fs_peer_name', '$fs_peer_return', '$fs_peer_volatility', '$fs_peer_color', '$name', '$str_date', '$fs_trend_line')";

    $conn->exec($sql);

$conn = null;



header("location:clients.php");
?>