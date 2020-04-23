<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$file_name = $_GET['fn'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM `tbl_fs_transactions` WHERE bl_live LIKE '2' ;";

    $conn->exec($sql);

$conn = null;



header("location:home.php");
?>