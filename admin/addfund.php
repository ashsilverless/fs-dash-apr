<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$fund_name = sanSlash($_POST['fund_name']);
$isin_code = sanSlash($_POST['isin_code']);
$fund_sedol = sanSlash($_POST['fund_sedol']);
$benchmark = sanSlash($_POST['benchmark']);
$currency = sanSlash($_POST['currency']);


$name = $_SESSION['fs_admin_name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$query = "SELECT * FROM `tbl_fs_fund` where isin_code LIKE '$isin_code' AND fund_name LIKE '$fund_name' AND bl_live = 1;";

	$result = $conn->prepare($query); 
	$result->execute();
	$recordcount = $result->rowCount();

    if($recordcount!=0){
        $sql = "UPDATE `tbl_fs_fund` SET fund_name = '$fund_name', isin_code = '$isin_code', fund_sedol = '$fund_sedol', benchmark = '$benchmark', fund_currency = '$currency', bl_live = 1, confirmed_by = '$name', confirmed_date = '$str_date' WHERE bl_live LIKE '2' ;";
    }else{
        $sql = "INSERT INTO `tbl_fs_fund` (`fund_name`, `fund_description`, `fund_type`, `isin_code`, `fund_sedol`, `benchmark`, `current_price`, `fund_currency`, `fs_file_name`, `confirmed_by`, `confirmed_date`, `bl_live`) VALUES ('".$fund_name."', '$fund_description', '$fund_type', '".$isin_code."', '".$fund_sedol."', '$benchmark', '$price', '$currency', 'manual_addition', '', '', '1')";
    }




	

    $conn->exec($sql);

$conn = null;



header("location:funds.php");
?>