<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*
ini_set ("display_errors", "1");
error_reporting(E_ALL);
    */
$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = $_SESSION['fs_client_featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


     //    Get the Peer Group Data   ///

  $query = "SELECT * FROM tbl_fs_peers WHERE bl_live = 1 AND fs_trend_line = '0' ;";
  $peer_data = $peer_colour = $peer_name = '';

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $peer_data .= "{ x: ".$row['fs_peer_return'].", y:".$row['fs_peer_volatility'].", n:'".$row['fs_peer_name']."'},";
	  $peer_colour .= '"'.$row['fs_peer_color'].'",';
	  $peer_name .= '"'.$row['fs_peer_name'].'",';
	  //$peer_data .= "[ ".$row['fs_peer_return'].",".$row['fs_peer_volatility'].", '".$row['fs_peer_name']."', 'point { size: 4; fill-color: ".$row['fs_peer_color']."; }','".$row['fs_peer_volatility']."% Volatility'],";
  }


$query = "SELECT * FROM tbl_fs_peers WHERE bl_live = 1 AND fs_trend_line = '1' ;";
  $peer_data_line = '';

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $peer_data_line .= "{ x: ".$row['fs_peer_return'].", y:".$row['fs_peer_volatility'].", n:'".$row['fs_peer_name']."'},";
	  $peer_colour_line .= '"'.$row['fs_peer_color'].'",';
	  $peer_name_line .= '"'.$row['fs_peer_name'].'",';
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
                  <h1 class="heading heading__1">Peer Group Comparison</h1>
                  <p class="mb3">Data accurate as at <?= date('j M y',strtotime($last_date));?></p>
              </div>

<div class="chart-wrapper">
    <div class="chart-wrapper__x-axis">
        <?php //create x axis values
        $sum = 0;
        for($i = 1; $i<=11; $i++) {?>
            <div class="x-axis-values" style="left:<?php echo $sum * 10;?>%;"><?php echo $sum;?></div>
        <?php $sum = $sum + 1;
        }?>
    </div>
    <div class="chart-wrapper__y-axis">
        <?php //create y axis values
        $sum = 10;
        for($i=10; $i>=0; $i--){?>
            <div class="y-axis-values" style="bottom:<?php echo $sum * 10;?>%;"><?php echo $sum;?></div>
            <?php $sum = $sum - 1;
            }?>
    </div>
    <div class="chart-wrapper__y-label">Annualised Return (%)</div>
    <div class="chart-wrapper__x-label">Annualised Volatility (%)</div>
    <div class="chart-wrapper__inner">
        <?php //create chart inner
        $sum = 0;
        for($i = 1; $i<=11; $i++) {?>
            <div class="x-axis" style="left:<?php echo $sum * 10;?>%;"></div>
            <div class="y-axis" style="top:<?php echo $sum *10;?>%;"></div>
        <?php $sum = $sum + 1;
        }?>

        <?php //create data points
        try {
          // Connect and create the PDO object
          $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
          $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

            $query = "SELECT *  FROM `tbl_fs_peers` where bl_live = 1;";

            $result = $conn->prepare($query);
            $result->execute();

                  // Parse returned data
                  while($row = $result->fetch(PDO::FETCH_ASSOC)) {  ?>

                    <div class="data-point trendline<?= $row['fs_trend_line'];?>" style="bottom:<?= $row['fs_peer_volatility'] * 10;?>%; left:<?= $row['fs_peer_return'] * 10;?>%;"><?= $row['fs_peer_name'];?></div>

                  <?php }
                  $conn = null;        // Disconnect
              }
              catch(PDOException $e) {
              echo $e->getMessage();
        }?>

        <svg id="trendline" width='100%' height='100%' viewBox="0 0 100 100" preserveAspectRatio="none">

            <polyline points="
            <?php //create trendline
            try {
              // Connect and create the PDO object
              $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
              $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

                $query = "SELECT *  FROM `tbl_fs_peers` where bl_live = 1;";

                $result = $conn->prepare($query);
                $result->execute();

                      // Parse returned data
                      while($row = $result->fetch(PDO::FETCH_ASSOC)) {

                    if($row['fs_trend_line'] == 1) {
                        $trendVol = $row['fs_peer_volatility'] * 10;
                        $trendRet = $row['fs_peer_return'] * 10;
                        echo $trendRet. ',' .$trendVol. ' ';
                        }

                    }
                      $conn = null;        // Disconnect
                  }
                  catch(PDOException $e) {
                  echo $e->getMessage();
            }?>

            "fill="none"/>
        </svg>

    </div>
</div><!--chart wrapper-->
              <!--<canvas class="chartjs-render-monitor" id="scatterchart"></canvas>-->
        </div>
    </div>
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

    <script type="text/javascript">




    </script>
  </body>
</html>
