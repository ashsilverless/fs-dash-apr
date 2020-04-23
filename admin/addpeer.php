<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$fs_peer_name = sanSlash($_POST['fs_peer_name']);
$fs_peer_return = onlyNum($_POST['fs_peer_return']);
$_POST['fs_trend_line'] != "" ? $fs_trend_line = 1 : $fs_trend_line = 0;
$fs_peer_volatility = onlyNum($_POST['fs_peer_volatility']);
$fs_peer_color = sanSlash($_POST['fs_peer_color']);


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);



    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `tbl_fs_peers` (`fs_peer_name`, `fs_peer_return`, `fs_peer_volatility`, `fs_peer_color`, `confirmed_by`, `confirmed_date`, `fs_trend_line`) VALUES ('$fs_peer_name', '$fs_peer_return', '$fs_peer_volatility', '$fs_peer_color', '$name', '$str_date', '$fs_trend_line')";

    $conn->exec($sql);

$conn = null;



header("location:peers.php");
?>