<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$fieldName = str_replace($_POST['pk'],'',$_POST['name']);
$recordID = $_POST['pk'];
$price = onlyNum($_POST['value']);


//debug($fieldName. '   :   '.$recordID. '   :   '.$fieldData);


$name = $_SESSION['fs_admin_name'];
$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	//     Update the Funds Table    //
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "UPDATE `tbl_fs_fund` SET `current_price`='$price',`fs_file_name`='manual_update',`confirmed_by`='$name',`confirmed_date`='$str_date' WHERE (`id` = '$recordID')";

	$conn->exec($sql);

	

$conn = null;        // Disconnect


die('{"id" : "#ts'.$recordID.'", "val" : "'.$price.'"}');
?>