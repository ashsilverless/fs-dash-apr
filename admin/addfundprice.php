<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$isin_code = $_GET['ic'];
$price = $_POST['price'.$isin_code];
$pricedate = $_POST['pricedate'.$isin_code];
$theDate = date('Y-m-d', strtotime($pricedate));

$name = $_SESSION['fs_admin_name'];

try {
    // Connect and create the PDO object
    $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
    $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

     //    Get the products   ///

    $query = "SELECT *  FROM `tbl_fs_fund` where isin_code LIKE '$isin_code' AND bl_live = 1 GROUP BY isin_code ORDER BY correct_at DESC;";

    $result = $conn->prepare($query); 
    $result->execute();

    // Parse returned data
    while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
    	$codes[] = $row['isin_code'];
		
		
		$fund_name = $row['fund_name'];
		$fund_description = $row['$fund_description'];
		$fund_type = $row['fund_type'];
		$fund_sedol = $row['fund_sedol'];
		$benchmark = $row['benchmark'];
		
     }
	
	
	//     Does a record exist for this date ?   //
	
	$query = "SELECT * FROM `tbl_fs_fund` where isin_code LIKE '$isin_code' AND correct_at LIKE '$theDate' AND bl_live = 1;";

    $result = $conn->prepare($query); 
    $result->execute();
    $recordcount = $result->rowCount();
	
	//     Update the Funds Table    //
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if($recordcount!=0){
		$sql = "UPDATE `tbl_fs_fund` SET `current_price`='$price',`fs_file_name`='manual_update',`confirmed_by`='$name',`confirmed_date`='$str_date' WHERE (`isin_code` LIKE '$isin_code' AND `correct_at` LIKE '$theDate')";
	}else{
		$sql = "INSERT INTO `tbl_fs_fund` (`fund_name`, `fund_description`, `fund_type`, `isin_code`, `fund_sedol`, `benchmark`, `current_price`, `correct_at`, `fs_file_name`, `confirmed_by`, `confirmed_date`) VALUES ('".$fund_name."', '$fund_description', '$fund_type', '".$isin_code."', '".$fund_sedol."', '$benchmark', '$price', '$theDate', 'manual_addition', '$name', '$str_date')";
	}

	$conn->exec($sql);

	

    $conn = null;        // Disconnect

    }

    catch(PDOException $e) {
    echo $e->getMessage();
    }

?>