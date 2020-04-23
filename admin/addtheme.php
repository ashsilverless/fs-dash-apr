<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$theme_title = sanSlash($_POST['theme_title']);
$theme_narrative = sanSlash($_POST['theme_narrative']);
$icon_file = sanSlash($_POST['icon_file']);

$_POST['theme_type_steady'] != "" ? $steady = 1 : $steady = 0;
$_POST['theme_type_serious'] != "" ? $serious = 1 : $serious = 0;
$_POST['theme_type_sensible'] != "" ? $sensible = 1 : $sensible = 0;


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);



    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `tbl_fs_themes` (`fs_theme_title`, `fs_theme_narrative`, `fs_theme_icon`, `confirmed_by`, `confirmed_date`, `bl_live`, `fs_theme_steady`, `fs_theme_serious`, `fs_theme_sensible`) VALUES ('".$theme_title."', '$theme_narrative', '$icon_file', '".$name."', '".$str_date."', '1', '$steady', '$serious', '$sensible')";

    $conn->exec($sql);

$conn = null;



header("location:themes.php");
?>