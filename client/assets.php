<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

/*
ini_set ("display_errors", "1");
error_reporting(E_ALL);
    */

$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = $_SESSION['fs_client_featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');
$confirmed_date = $row['confirmed_date']= date('d M Y');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));

$strategy = 'fs_growth_'.strtolower(getField('tbl_fsusers','strategy','id',$_SESSION['fs_client_user_id']));
//REMOVE NEXT LINE WHEN PUSHING
//$strategy = 'fs_growth_'.strtolower(getField('tbl_fsusers','strategy','id','5'));

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_assets` where $strategy > 0 AND bl_live = 1;";

    $result = $conn->prepare($query);
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			 $assetData[] =  $row;

        }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once(__ROOT__.'/page-sections/header-elements.php');
require_once(__ROOT__.'/page-sections/sidebar-elements.php');
?>

		    <div class="col-md-9">

                <div class="border-box main-content">

                    <div class="main-content__head">
                        <h1 class="heading heading__1">Holdings & Asset Allocation</h1>
                        <p class="mb3">Data accurate as at <?= $confirmed_date;?></p>
                    </div>

<div class="asset-wrapper">
    <div class="asset-wrapper__chart">

        <svg width="100%" height="100%" viewBox="0 0 42 42" class="donut" aria-labelledby="" role="img" style="transform:rotate(-90deg);">
            <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#484848" role="presentation"></circle>
            <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#414141" stroke-width="10" role="presentation"></circle>
            <!--For each holding, create a segment like this
            Params =
            Stroke-dasharray: two figures.  The first is the value of the holding (ie, 30%); the second is the first value minus 100 (ie 30 - 100) therefore 70.

            Stroke-dashoffset: This is the running sum of the value of the holding, expressed as a negative value to enable positioning.
            -->
            <?php foreach($assetData as $asset) {
              $assetsData .= $asset[$strategy].',';
              $assetsID .= $asset['id'].',';
              $assetsName .= "'".$asset['fs_asset_name']."',";
              $asset_color = "".$asset['asset_color']."";
              $thisAsset = $asset[$strategy];
              $assetBalance = 100 - $thisAsset;
            ?>

               <circle id="asset<?=$asset['id'];?>" class="donut-segment <?=$asset['id'];?> <?=$asset['fs_asset_name'];?> asset<?=$asset['id'];?>" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="<?= $asset_color;?>" stroke-width="10" stroke-dasharray="<?=$thisAsset;?> <?=$assetBalance;?>" stroke-dashoffset="-<?=$assetTotal;?>"></circle>
               <text x="22" y="22" text-anchor="middle" alignment-baseline="middle" class="asset<?=$asset['id'];?>"><?=$thisAsset;?>%</text>
               <?php $assetTotal = $thisAsset += $assetTotal;?>
           <?php }?>
        </svg>
        <div class="key border-box">
            <?php foreach($assetData as $asset) {
              $assetsData .= $asset[$strategy].',';
              $assetsID .= $asset['id'].',';
              $assetsName .= "'".$asset['fs_asset_name']."',";
              $asset_color = "".$asset['asset_color']."";
              $thisAsset = $asset[$strategy];
              $assetBalance = 100 - $thisAsset;
            ?>
            <div class="key__item">
                <div class="color" style="background-color:<?= $asset_color;?>;"></div>
                <h4 class="heading heading__4"><?=$asset['fs_asset_name'];?></h4>
            </div>
            <?php }?>
        </div>
    </div>
    <div class="asset-wrapper__table">
        <div class="head">
            <h4 class="heading heading__4">Fund</h4>
            <h4 class="heading heading__4">Growth Rate</h4>
        </div>
        <?php foreach($assetData as $asset) {
          $assetsData .= $asset[$strategy].',';
          $assetsID .= $asset['id'].',';
          $assetsName .= "'".$asset['fs_asset_name']."',";
          $asset_color = "".$asset['asset_color']."";
          $thisAsset = $asset[$strategy];
          $assetBalance = 100 - $thisAsset;
        ?>
        <div id="asset<?=$asset['id'];?>" class="item asset<?=$asset['id'];?>" data-asset="asset<?=$asset['id'];?>">
            <h4 class="heading heading__4"><?=$asset['fs_asset_name'];?></h4>
            <h4 class="heading heading__4"><?=$asset[$strategy];?></h4>
            <div class="toggle button button__raised button__toggle">
                <i class="fas fa-caret-down arrow"></i>
            </div>
            <p><?=$asset['fs_asset_narrative'];?></p>
        </div>
        <?php }?>
    </div>
</div>
    </div>
</div>


<!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="../index.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

<!--    Logged Out  -->
    <div class="modal fade" id="loggedout" tabindex="-1" role="dialog" aria-labelledby="LoggedOut" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Your Session has Timed Out</h5>
        </div>
        <div class="modal-body">Select "Login" below if you want to continue your session.</div>
        <div class="modal-footer">
		  <a class="btn btn-primary" href="../index.php">Login</a>
          <a class="btn btn-secondary quit" href="">Quit</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/global-scripts.php');
require_once('../page-sections/footer-elements.php');?>

  </body>
</html>
