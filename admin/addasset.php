<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$asset_name = sanSlash($_POST['asset_name']);
$asset_narrative = sanSlash($_POST['asset_narrative']);
$growth_steady = onlyNum($_POST['growth_steady']);
$growth_sensible = onlyNum($_POST['growth_sensible']);
$growth_serious = onlyNum($_POST['growth_serious']);
$cat_new = sanSlash($_POST['cat_new']);
$cat_ids = explode("|",$_POST['cat_ids']);
$cat_id = $_POST['cat'];

$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `tbl_fs_assets` (`fs_asset_name`, `fs_asset_narrative`, `fs_growth_steady`, `fs_growth_sensible`, `fs_growth_serious`, `confirmed_by`, `confirmed_date`, `cat_id`) VALUES ('$asset_name', '$asset_narrative', '$growth_steady', '$growth_sensible', '$growth_serious', '$name', '$str_date','$cat_id')";

    $conn->exec($sql);
	$lastId = $conn->lastInsertId();

	//   Now do the categories   //

	for($a=0;$a<count($cat_ids);$a++){
		$id = $cat_ids[$a];
		if($_POST['cat'.$id]!=''){
			$sql_cat = "INSERT INTO `tbl_fs_asset_cats` (`fs_asset_id`, `fs_cat_id`) VALUES ('$lastId', '$id')";
			$conn->exec($sql_cat);
		}
	}

	if($cat_new!=''){
		$sql_new_cat = "INSERT INTO `tbl_fs_categories` (`cat_name`, `confirmed_by`, `confirmed_date`) VALUES ('$cat_new', '$name', '$str_date')";
		$conn->exec($sql_new_cat);
		$lastCatId = $conn->lastInsertId();

		$sql_new_catid = "UPDATE `tbl_fs_assets` SET `cat_id` =  $lastCatId WHERE id = $lastId;";
		$conn->exec($sql_new_catid);
	}

$conn = null;

header("location:assets.php");
?>
