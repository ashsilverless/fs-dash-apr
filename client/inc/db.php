<?php
session_start();


if(!$_SESSION['loggedin']){
    header("location:../index.php");
}

if($_GET['err']!=""){
	ini_set ("display_errors", "1");	error_reporting(E_ALL);
}

	##################      LIVE SERVER     ###########################

	$host = "localhost";
	$user = "FeatherStoneDashboard";
	$pass = "FSD>Login-1";
	$db	 = "featherstone_db";
	$charset = 'utf8mb4';

	##################     / LIVE SERVER     ##########################

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('bytes', 'Kb', 'Mb', 'Gb', 'Tb');

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}




function getTable($tbl,$orderFld = "id",$status = 'bl_live = 1'){
	global $host,$user, $pass, $db, $charset;
	try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
debug("SELECT * FROM $tbl WHERE $status ORDER BY $orderFld ASC ");
	  $result = $conn->prepare("SELECT * FROM $tbl WHERE $status ORDER BY $orderFld ASC ");
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $return[] = $row;
	  }

	  $conn = null;        // Disconnect
	  return $return;

	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}
}

function getLastDate($tbl,$retfield,$orderFld = "id",$param){
	global $host,$user, $pass, $db, $charset;
	try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $result = $conn->prepare("SELECT $retfield FROM $tbl WHERE $param AND bl_live >0 ORDER BY $orderFld DESC LIMIT 1; ");
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $return = $row[$retfield];
	  }

	  $conn = null;        // Disconnect
	  return $return;

	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}
}

function get_current_price($code){
    global $host,$user, $pass, $db, $charset;
	try {
        // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

        $query = "SELECT * FROM tbl_fs_fund WHERE isin_code LIKE '$code' AND bl_live = 1 ORDER BY correct_at DESC LIMIT 1;";

        debug ($query);

	  $result = $conn->prepare($query);
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $return = $row['current_price'];
	  }

	  $conn = null;        // Disconnect
	  return $return;

	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}
}

function get_benchmark($code){
    global $host,$user, $pass, $db, $charset;
	try {
        // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

        $query = "SELECT * FROM tbl_fs_fund WHERE isin_code LIKE '$code' AND bl_live = 1 ORDER BY correct_at DESC LIMIT 1;";

        debug ($query);

	  $result = $conn->prepare($query);
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $return = $row['benchmark'];
	  }

	  $conn = null;        // Disconnect
	  return $return;

	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}
}

function getTblCount($tbl){
    global $host,$user, $pass, $db, $charset;
	try {
	  // Connect and create the PDO object
	  $countconn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $countconn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $countresult = $countconn->prepare("SELECT * FROM $tbl WHERE id > 0 AND bl_live >0;");
	  $countresult->execute();
      $count = $countresult->rowCount();

	  $countconn = null;        // Disconnect
	  return $count;

	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}
}


function getFields($tbl,$srch,$param,$condition = '='){
	global $host,$user, $pass, $db, $charset;
	try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

       // debug("SELECT * FROM $tbl WHERE $srch $condition '$param' AND bl_live > 0;");

	  $result = $conn->prepare("SELECT * FROM $tbl WHERE $srch $condition '$param' AND bl_live > 0;");
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $return[] = $row;
	  }

	  $conn = null;        // Disconnect
	  return $return;

	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}
}

function getField($tbl,$fld,$srch,$param){
	global $host,$user, $pass, $db, $charset;
	try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $result = $conn->prepare("SELECT * FROM $tbl WHERE $srch = '$param';");
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $return = $row[$fld];
	  }

	  $conn = null;        // Disconnect
	  return $return;

	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}
}



function sps($string, $min='', $max='')
{
  $string = str_replace("£","&pound;",$string);
  $string = str_replace("?","&#63;",$string);
  $string = str_replace("'","''",$string);
  $string = str_replace("&","and",$string);
  $string = preg_replace("/[^a-zA-Z0-9.,;:'@#=!_ \n|\r()-]/", "", $string);
  $len = strlen($string);
  if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
    return FALSE;
  return $string;
}

function spparanoid($string, $min='', $max='')
{
  $string = preg_replace("/[^a-zA-Z0-9_-]/", "", $string);
  $len = strlen($string);
  if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
    return FALSE;
  return $string;
}

function cleanArray($array){
	if(is_array($array)){
		foreach($array as $key=>$value){

			$value = str_replace("script","scrip t",$value); //no easy javascript injection
			$value = str_replace("union","uni on",$value); //no easy common mysql temper

			$value = str_replace("'","''",$value); //no single quotes

			$value = htmlentities($value, ENT_QUOTES); //encodes the string nicely
			$value = addslashes($value); //mysql_real_escape_string() //htmlentities

			$array[$key] = $value;
		}
	}else{
		return false;
	}

	return $array;
}
function sanSlash($string){
	$string = htmlentities($string, ENT_QUOTES); //encodes the string nicely
	$string = addslashes($string); //mysql_real_escape_string() //htmlentities
	return $string;
}

function onlyNum($string, $min='', $max='')
{
  $string = preg_replace("/[^0-9.]/", "", $string);
  $len = strlen($string);
  if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
    return FALSE;
  return $string;
}

function doDate($what){
	if($what != ""){
		return date("D jS M Y", strtotime($what));
	}
}

function convertToHoursMins($time, $format = '%02d:%02d') {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}


function debug($string){
	echo ('<script> console.log("'.$string.'");</script>');
}

function chk($what,$fld,$cs){
	if($what == $fld){
		return $cs.'="'.$cs.'"';
	}
}


######################################################################

$cur_code = array('GBP' => '&pound;','EUR' => '&euro;');

$my_t=getdate(date("U"));

$my_t['mon'] < 10 ? $theMonth = "0".$my_t['mon'] : $theMonth = $my_t['mon'];
$my_t['mday'] < 10 ? $theDay = "0".$my_t['mday'] : $theDay = $my_t['mday'];

$today=$my_t['year']."-".$theMonth."-".$theDay;

$svrdata = "";
$str_date=$my_t['year']."-".$my_t['mon']."-".$my_t['mday']." ".$my_t['hours'].":".$my_t['minutes'].":".$my_t['seconds'];
$str_time=$my_t['hours'].":".$my_t['minutes'].":".$my_t['seconds'];
$str_ipaddress=$_SERVER['REMOTE_ADDR'];
$str_path=$_SERVER['SCRIPT_NAME'];
$str_pagename = str_replace("","",$str_path);
$ref = getenv("HTTP_REFERER");
cleanArray($_POST);
foreach($_POST as $key => $data) {
	if($key!="button"){
		$svrdata .= $key."=".$data ."   ";
	}
}
$svrdata .= $_SERVER['QUERY_STRING'];
$svrdata = sanSlash($svrdata);

#####################################################################

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $user_id = $_SESSION['user_id'];
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_fshits (str_ip,dt_date,str_page,str_querystring,str_ref,int_user_id) VALUES('$str_ipaddress','$str_date','$str_path','$svrdata','$ref','$user_id')";
    $conn->exec($sql);

$conn = null;

?>
