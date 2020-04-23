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

                    <!--<svg width="100%" height="100%" viewBox="0 0 42 42" class="donut" aria-labelledby="beers-title beers-desc" role="img" style="transform:rotate(-90deg);">
                          <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff" role="presentation"></circle>
                          <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#d2d3d4" stroke-width="3" role="presentation"></circle>

                          <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="blue" stroke-width="4" stroke-dasharray=" 50 50" stroke-dashoffset="70" aria-labelledby="donut-segment-1-title donut-segment-1-desc"></circle>
                           <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#ce4b99" stroke-width="4" stroke-dasharray="30 70" stroke-dashoffset="0" aria-labelledby="donut-segment-1-title donut-segment-1-desc"></circle>


                       </svg>-->

                      <div class="container">
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
                          </div><!--row-->
                      </div>

                </div>



			</div><!--9-->


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



    <script>
      $(".toggler").click(function(e){
        e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});


Chart.defaults.global.legend.display = false;

/* ##########################################       PIE CHART     ################################################## */

     var ctx = document.getElementById('piechart');

      var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
        labels: [<?=substr($assetsName, 0, -1);?>],
        options: { legend: {display: true}, tooltips: {enabled: true}},
        datasets: [{
            data: [<?=substr($assetsData, 0, -1);?>],
			ids: [<?=substr($assetsID, 0, -1);?>],
            backgroundColor: ['#82C2A7','#D64E4E','#D5C661','#5889B4','#63678A'],
            borderWidth: 0
        	}]
    	},
      });

	$("#piechart").click(
        function(evt){
            var activePoints = myChart.getElementsAtEvent(evt);
			var clickedElementindex = activePoints[0]["_index"];
			var valueID = myChart.data.datasets[0].ids[clickedElementindex];

			$('.'+valueID).toggle();
            $('.head'+valueID).toggleClass( "highlight normal" );
            $('.arrow'+valueID, this).toggleClass("fa-caret-up fa-caret-down");
        });

    </script>
  </body>
</html>
