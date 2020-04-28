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
              <canvas class="chartjs-render-monitor" id="scatterchart"></canvas>
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

	function drawChart() {
	 //Chart.defaults.global.legend.display = false;
     var ctx = document.getElementById('scatterchart');

		Chart.pluginService.register({
		  beforeRender: function(chart) {
			if (chart.config.options.showAllTooltips) {
			  // create an array of tooltips
			  // we can't use the chart tooltip because there is only one tooltip per chart
			  chart.pluginTooltips = [];
			  chart.config.data.datasets.forEach(function(dataset, i) {
				chart.getDatasetMeta(i).data.forEach(function(sector, j) {
				  chart.pluginTooltips.push(new Chart.Tooltip({
					_chart: chart.chart,
					_chartInstance: chart,
					_data: chart.data,
					_options: chart.options.tooltips,
					_active: [sector]
				  }, chart));
				});
			  });

			  // turn off normal tooltips
			  chart.options.tooltips.enabled = false;
			}
		  },
		  afterDraw: function(chart, easing) {
			if (chart.config.options.showAllTooltips) {
			  // we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
			  if (!chart.allTooltipsOnce) {
				if (easing !== 1)
				  return;
				chart.allTooltipsOnce = true;
			  }

			  // turn on tooltips
			  chart.options.tooltips.enabled = true;
			  Chart.helpers.each(chart.pluginTooltips, function(tooltip) {
				tooltip.initialize();
				tooltip.update();
				// we don't actually need this since we are not animating tooltips
				tooltip.pivot();
				tooltip.transition(easing).draw();
			  });
			  chart.options.tooltips.enabled = false;
			}
		  }
		});

		var chart = new Chart(ctx, {
		   type: 'scatter',
		   labels: 'Peer Groups',
		   data: {
			  datasets: [{
				 label: [<?=substr($peer_name_line, 0, -1);?>],
				 data: [<?=substr($peer_data_line, 0, -1);?>],
				 borderColor: '#629FD6',
				 borderWidth: 1,
				 pointBackgroundColor:'#629FD6',
				 pointBorderColor:'#629FD6',
				 pointRadius: 3,
				 pointHoverRadius: 3,
				 fill: false,
				 tension: 0,
				 showLine: true
			  	 }, {
				 label: [<?=substr($peer_name, 0, -1);?>],
				 data: [<?=substr($peer_data, 0, -1);?>],
				 pointBackgroundColor: '#849db3',
				 pointBorderColor: '#849db3',
				 pointRadius: 3,
				 pointHoverRadius: 3
			  }]
		   },
		   options: {
			  legend: {
				display: false
			 },
			  showAllTooltips: true,
			  tooltips: {
				 backgroundColor: 'rgba(255, 255, 255, 0)',
				 bodyFontColor: '#FFF',
				 displayColors: false,
                 defaultFontFamily: 'mr-eaves-modern, sans-serif',
				 callbacks: {
					label: function(tooltipItem, data) {
					   var tLabel = data.datasets[tooltipItem.datasetIndex].label[tooltipItem.index];
					   var yLabel = tooltipItem.yLabel;
					   return tLabel;
					}
				 }
			  },
			 scales: {

				yAxes: [{
					scaleLabel: {
					  display: true,
					  labelString: 'Annualised Return(%)',
                      defaultFontFamily: 'mr-eaves-modern, sans-serif',
                      fontStyle: 200,
					},
					gridLines: {
					  display: true ,
					  color: "rgba(255, 255, 255, 0.15)"
					}
				}],
				xAxes: [{
					scaleLabel: {
					  display: true,
					  labelString: 'Annualised Volatility(%)',
                      defaultFontFamily: 'mr-eaves-modern, sans-serif',
                      fontStyle: 200,
                      defaultFontSize:25,
					},
					gridLines: {
					  display: true ,
					  color: "rgba(255, 255, 255, 0.15)"
					}
				}]
        }

		   }
		});

	};
/*

tooltips: {
  callbacks: {
    label: function(tooltipItem, data) {
      var item = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
      return item.y  + ' ' + item.value;
    }
  }
}

*/

	$( document ).ready(function() {
		drawChart();
    });


    </script>
  </body>
</html>
