<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "UPDATE `tbl_fs_transactions` SET bl_live = 1, confirmed_by = '$name', confirmed_date = '$str_date' WHERE bl_live LIKE '2' ;";

    $conn->exec($sql);

$conn = null;



header("location:upload_files.php");
?>