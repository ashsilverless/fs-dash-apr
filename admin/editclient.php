<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$id = $_GET['id'];

$strategy = sanSlash($_POST['strategy']);
$fs_client_code = onlyNum($_POST['fs_client_code']);


$name = $_SESSION['fs_admin_name'];

//      Client Details
$user_prefix = sanSlash($_POST['user_prefix']);
$first_name = sanSlash($_POST['first_name']);
$last_name = sanSlash($_POST['last_name']);
$destruct_date = sanSlash($_POST['destruct_date']);
$client_email= sanSlash($_POST['client_email']);
$strategy= sanSlash($_POST['strategy']);
$fs_client_desc= sanSlash($_POST['fs_client_desc']);
$telephone= sanSlash($_POST['telephone']);

//      Accounts
$del_array = explode('|',$_POST['product_ids']);     //     del123=1

//      New Account
$_POST['fs_isin_code'] !='' ? $fs_isin_code = sanSlash($_POST['fs_isin_code']) : $fs_isin_code = sanSlash($_POST['new_fs_isin_code']);
$fs_fund_sedol= sanSlash($_POST['fs_fund_sedol']);
$fs_product_type= sanSlash($_POST['fs_product_type']);
$fs_fund_name= sanSlash($_POST['fs_fund_name']);
$fs_designation= sanSlash($_POST['fs_designation']);

//      Linked Accounts

$linked_account= onlyNum($_POST['linked_account']);

$linked_account != '' ? $linked_accounts= sanSlash($_POST['linked_accounts']).'|'.$linked_account : $linked_accounts= sanSlash($_POST['linked_accounts']);

$del_linkarray = explode('|',$linked_accounts);     //     dellink123=1

foreach($del_linkarray as $del_ac) {
    if($_POST['dellink'.$del_ac] == '1'){
        $linked_accounts = str_replace($del_ac.'|','',$linked_accounts);
    }
}


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Update Client Deets

	$sql = "UPDATE `tbl_fsusers` SET `user_prefix`='$user_prefix', `first_name`='$first_name', `last_name`='$last_name', `email_address`='$client_email', `telephone`='$telephone', `strategy`='$strategy', `bl_live`='1', `destruct_date`='$destruct_date', `linked_accounts`='$linked_accounts',`fs_client_desc` = '$fs_client_desc' WHERE (`id`='$id')";

	$conn->exec($sql);


	foreach($del_array as $del) {
		if($_POST['del'.$del] == '1'){
			$del_sql = "UPDATE `tbl_fs_client_products` SET `bl_live`='0' WHERE (`id`='$del')";
			$conn->exec($del_sql);
		}
	}

	if ($fs_isin_code!=''){
		$product_sql = "INSERT INTO `tbl_fs_client_products` (`fs_isin_code`, `fs_fund_sedol`, `fs_fund_name`, `fs_client_desc`, `fs_client_code`, `fs_client_name`, `fs_designation`, `fs_product_type`, `confirmed_by`, `confirmed_date`, `fs_client_id`) VALUES ('$fs_isin_code', '$fs_fund_sedol', '$fs_fund_name', '$fs_client_desc', '$fs_client_code', '$user_name', '$fs_designation', '$fs_product_type', '$name', '$str_date', '$user_id')";

		$conn->exec($product_sql);
	}


$conn = null;
/*
foreach($_POST as $key => $data) {
	if($key!="button"){
		echo ( $key."=".$data ."  <br> ");
	}
}
*/
header("location:clients.php");

?>
