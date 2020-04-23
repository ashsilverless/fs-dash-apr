<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$id = $_GET['id'];


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "UPDATE `tbl_fs_peers` SET bl_live = 0, confirmed_by = '$name', confirmed_date = '$str_date' WHERE id LIKE '$id' ;";

    $conn->exec($sql);

$conn = null;



header("location:peers.php");
?>