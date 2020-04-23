<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$id = $_GET['id'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM `tbl_fs_transactions` WHERE id = '$id' ;";

    $conn->exec($sql);

$conn = null;



header("location:home.php?nu=1");
?>