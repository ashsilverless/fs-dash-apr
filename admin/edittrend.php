<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$id = $_GET['id'];
$_GET['tl'] == '0' ? $fs_trend_line = '1' : $fs_trend_line = '0';

$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);



    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "UPDATE `tbl_fs_peers` SET `fs_trend_line`='$fs_trend_line' WHERE (`id`='$id')";

    $conn->exec($sql);

$conn = null;



header("location:peers.php");
?>