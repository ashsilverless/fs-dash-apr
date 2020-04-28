<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

/*
ini_set ("display_errors", "1");
error_reporting(E_ALL);
    */

$user_id = $_SESSION['featherstone_uid'];
$client_code = $_SESSION['featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['user_id'].'"')));

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_assets` where bl_live = 1;";

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
                        <p>Data accurate as at <?= date('j M y',strtotime($last_date));?></p>
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
              $assetsData .= $asset['fs_growth_steady'].',';
              $assetsID .= $asset['id'].',';
              $assetsName .= "'".$asset['fs_asset_name']."',";
              $asset_color = "".$asset['asset_color']."";
              $thisAsset = $asset['fs_growth_steady'];
              $assetBalance = 100 - $thisAsset;
            ?>
               <circle id="asset<?=$asset['id'];?>" class="donut-segment <?=$asset['id'];?> <?=$asset['fs_asset_name'];?>" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="<?= $asset_color;?>" stroke-width="10" stroke-dasharray="<?=$thisAsset;?> <?=$assetBalance;?>" stroke-dashoffset="-<?=$assetTotal;?>"></circle>
               <text x="22" y="22" text-anchor="middle" alignment-baseline="middle" class="asset<?=$asset['id'];?>"><?=$thisAsset;?>%</text>
               <?php $assetTotal = $thisAsset += $assetTotal;?>
           <?php }?>
        </svg>
        <div class="key border-box">
            <?php foreach($assetData as $asset) {
              $assetsData .= $asset['fs_growth_steady'].',';
              $assetsID .= $asset['id'].',';
              $assetsName .= "'".$asset['fs_asset_name']."',";
              $asset_color = "".$asset['asset_color']."";
              $thisAsset = $asset['fs_growth_steady'];
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
          $assetsData .= $asset['fs_growth_steady'].',';
          $assetsID .= $asset['id'].',';
          $assetsName .= "'".$asset['fs_asset_name']."',";
          $asset_color = "".$asset['asset_color']."";
          $thisAsset = $asset['fs_growth_steady'];
          $assetBalance = 100 - $thisAsset;
        ?>
        <div class="item asset<?=$asset['id'];?>" data-asset="asset-id-<?=$asset['id'];?>">
            <h4 class="heading heading__4"><?=$asset['fs_asset_name'];?></h4>
            <h4 class="heading heading__4"><?=$asset['fs_growth_steady'];?></h4>
            <div class="toggle button button__raised button__toggle">
                <i class="fas fa-caret-down arrow"></i>
            </div>
            <p><?=$asset['fs_asset_narrative'];?></p>
        </div>
        <?php }?>
    </div>
</div>

                      <!--<div class="container">
                          <div class="row">
                              <div class="col-md-4">
                                <canvas class="my-4 w-100 chartjs-render-monitor" id="piechart" height="286"></canvas>
                            </div>
                              <div class="col-md-8">
                              <table class="table table-sm table-striped">
                                <thead>
                                  <tr>
                                    <th>Fund</th>
                                    <th>Portfolio Weighting</th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody>

                                    <?php foreach($assetData as $asset) {
                                      $assetsData .= $asset['fs_growth_steady'].',';
                                      $assetsID .= $asset['id'].',';
                                      $assetsName .= "'".$asset['fs_asset_name']."',";
                                    ?>

                                      <tr>
                                        <td class="head<?=$asset['id'];?> normal"><?=$asset['fs_asset_name'];?></td>
                                        <td class="head<?=$asset['id'];?> normal"><?=$asset['fs_growth_steady'];?></td>
                                        <td class="head<?=$asset['id'];?> normal"><a href="#" class="toggler indicator" data-prod-name="<?=$asset['id'];?>"><i class="fas fa-caret-up arrow118"></i></a> </td>
                                      </tr>
                                    <tr class="<?=$asset['id'];?>" style="font-size:0.8em; background-color:#333; font-weight:bold; display:none;">
                                        <td colspan="3"><p><?=$asset['fs_asset_narrative'];?></p> </td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                              </table>
                          </div>
                          </div>
                      </div>
                </div>
			</div><!--9-->

    </div>
</div>


	<!-- Footer -->
      <footer class="col-md-12 mt-5">
       <div class="auto-LogOut"></div>
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Featherstone 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->


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

  <?php define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/global-scripts.php');?>



    <script>

    </script>
  </body>
</html>
