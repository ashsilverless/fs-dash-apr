<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*

ini_set ("display_errors", "1");
	error_reporting(E_ALL);
		*/

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$targetDir = 'dataupload/';


$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds

$my_t=getdate(date("U"));
$fdate=str_replace("20","",$my_t['year']).$my_t['mon'].$my_t['mday'].$my_t['hours'].$my_t['minutes'].$my_t['seconds'];

// 5 minutes execution time
@set_time_limit(5 * 60);

// Get parameters
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

// Clean the fileName for security reasons
$fileName = preg_replace('/[^\w\.]+/', '_', $fileName);

// Make sure the fileName is unique but only if chunking is disabled
if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
	$ext = strrpos($fileName, '.');
	$fileName_a = substr($fileName, 0, $ext);
	$fileName_b = substr($fileName, $ext);

	$count = 1;
	while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
		$count++;

	$fileName = $fileName_a . '_' . $count . $fileName_b;
}

$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

// Create target dir
if (!file_exists($targetDir))
	@mkdir($targetDir);

// Remove old temp files	
if ($cleanupTargetDir) {
	if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
		while (($file = readdir($dir)) !== false) {
			$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

			// Remove temp file if it is older than the max age and is not the current file
			if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
				@unlink($tmpfilePath);
			}
		}
		closedir($dir);
	} else {
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
	}
}	

// Look for the content type header
if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
	$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

if (isset($_SERVER["CONTENT_TYPE"]))
	$contentType = $_SERVER["CONTENT_TYPE"];

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
if (strpos($contentType, "multipart") !== false) {
	if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
		// Open temp file
		$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = @fopen($_FILES['file']['tmp_name'], "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			@fclose($in);
			@fclose($out);
			@unlink($_FILES['file']['tmp_name']);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
} else {
	// Open temp file
	$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
	if ($out) {
		// Read binary input stream and append it to temp file
		$in = @fopen("php://input", "rb");

		if ($in) {
			while ($buff = fread($in, 4096))
				fwrite($out, $buff);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

		@fclose($in);
		@fclose($out);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
	// Strip the temp .part suffix off 
	rename("{$filePath}.part", $filePath);

    /* ########################################    PARSE THE DATA     ############################ */
	$fcontents = file("dataupload/".$fileName); 

	$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	for($i=0; $i<sizeof($fcontents); $i++) { 
		$line = trim($fcontents[$i]); 
		$arr = explode(",", $line);
		
		
		$t_date = explode('/',$arr[1]);
		
		$correct_at_date = $t_date[2]."-".$t_date[1]."-".$t_date[0];
		
		if(validateDate($correct_at_date)){
					
			#fund_name    isin_code        fund_sedol         current_price
			$fund_description = $fund_type = $benchmark = "";
			$fund_name = $arr[0];     $isin_code = $arr[2];     $fund_sedol = $arr[3];     $price = $arr[4];
			
			$query = "SELECT * FROM `tbl_fs_fund` where isin_code LIKE '$isin_code' AND correct_at LIKE '$theDate' AND bl_live = 1;";

			$result = $conn->prepare($query); 
			$result->execute();
			$recordcount = $result->rowCount();
			
			if($recordcount!=0){
				$sql = "UPDATE `tbl_fs_fund` SET `current_price`='$price',`fs_file_name`=CONCAT('update from - ',fs_file_name),`confirmed_by`='$name',`confirmed_date`='$str_date' WHERE (`isin_code` LIKE '$isin_code' AND `correct_at` LIKE '$theDate')";
			}else{
				$sql = "INSERT INTO `tbl_fs_fund` (`fund_name`, `fund_description`, `fund_type`, `isin_code`, `fund_sedol`, `benchmark`, `current_price`, `correct_at`, `fs_file_name`, `confirmed_by`, `confirmed_date`, `bl_live`) VALUES ('".$fund_name."', '$fund_description', '$fund_type', '".$isin_code."', '".$fund_sedol."', '$benchmark', '$price', '$correct_at_date', '$fileName', '', '', '2')";
			}

	

			$conn->exec($sql);
			
		}



	}
	
  //    Count the new items   ///

  $query = "SELECT * FROM `tbl_fs_fund` where bl_live = 2;";
    
  $result = $conn->prepare($query); 
  $result->execute();
  $recordcount = $result->rowCount();

	
	$conn = null; 
	
}

die('{"jsonrpc" : "2.0", "result" : "'.$fileName.'", "datacount" : "'.$recordcount.'"}');
?>