<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$isin_code = $_GET['ic'];


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "UPDATE `tbl_fs_fund` SET bl_live = 0, fs_file_name = CONCAT('deleted - ',fs_file_name), confirmed_by = '$name', confirmed_date = '$str_date' WHERE isin_code LIKE '$isin_code' ;";

    $conn->exec($sql);

$conn = null;



header("location:funds.php");
?>