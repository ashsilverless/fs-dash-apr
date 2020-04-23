<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$id = $_GET['id'];
$theme_title = sanSlash($_POST['theme_title']);
$theme_narrative = sanSlash($_POST['theme_narrative']);
$icon_file = sanSlash($_POST['icon_file']);

$_POST['theme_type_steady'] != "" ? $steady = 1 : $steady = 0;
$_POST['theme_type_serious'] != "" ? $serious = 1 : $serious = 0;
$_POST['theme_type_sensible'] != "" ? $sensible = 1 : $sensible = 0;


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "UPDATE `tbl_fs_themes` SET bl_live = 1, fs_theme_title = '$theme_title', fs_theme_narrative = '$theme_narrative', fs_theme_icon = '$icon_file', confirmed_by = '$name', confirmed_date = '$str_date', fs_theme_steady = '$steady', fs_theme_serious = '$serious', fs_theme_sensible = '$sensible' WHERE id LIKE '$id' ;";

    $conn->exec($sql);

$conn = null;



header("location:themes.php");
?>