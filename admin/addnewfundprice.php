<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$fieldName = str_replace($_POST['pk'],'',$_POST['name']);
$priceDate = date('Y-m-d', strtotime($_POST['pk']));
$price = onlyNum($_POST['value']);
$isin_code = $_GET['ic'];

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
		$fund_name = $row['fund_name'];
		$fund_description = $row['$fund_description'];
		$fund_type = $row['fund_type'];
		$fund_sedol = $row['fund_sedol'];
		$benchmark = $row['benchmark'];
		
     }
	
	
	$sql = "INSERT INTO `tbl_fs_fund` (`fund_name`, `fund_description`, `fund_type`, `isin_code`, `fund_sedol`, `benchmark`, `current_price`, `correct_at`, `fs_file_name`, `confirmed_by`, `confirmed_date`) VALUES ('".$fund_name."', '$fund_description', '$fund_type', '".$isin_code."', '".$fund_sedol."', '$benchmark', '$price', '$priceDate', 'manual_addition', '$name', '$str_date')";


	$conn->exec($sql);

	

    $conn = null;        // Disconnect

    }

  catch(PDOException $e) {
    echo $e->getMessage();
  }


die('{"id" : "#ts'.$recordID.'", "val" : "'.$price.'"}');


?>