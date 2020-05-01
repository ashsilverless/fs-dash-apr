<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*
ini_set ("display_errors", "1");	error_reporting(E_ALL);
    */

$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = $_SESSION['fs_client_featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


     //    Get the general products data for Client   ///


  $query = "SELECT * FROM tbl_fsusers where fs_client_code LIKE '$client_code' AND bl_live = 1;";
	debug($query);

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $user_name = $row['user_name'];
  }


     //    Get the products   ///

  $query = "SELECT DISTINCT fs_product_type FROM `tbl_fs_transactions` where fs_client_code LIKE '$client_code' AND bl_live = 1;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $products[] = $row;
  }

    //    Get the funds   ///

  $query = "SELECT DISTINCT fs_isin_code FROM `tbl_fs_transactions` where fs_client_code LIKE '$client_code' AND bl_live = 1;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $funds[] = $row;
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
        <div class="border-box main-content daily-data">
            <div class="main-content__head">
                <h1 class="heading heading__1">Daily Valuation Data</h1>
                <p>Data accurate as at <?= date('j M y',strtotime($last_date));?></p>
                <div class="button button__raised data-toggle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.59 19.59"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M0,9.79A9.84,9.84,0,0,1,9.79,0a9.85,9.85,0,0,1,9.8,9.79,9.85,9.85,0,0,1-9.8,9.8A9.85,9.85,0,0,1,0,9.79Zm15.48,6.38L9.61,10.41a.7.7,0,0,1-.22-.56V1.28a8.53,8.53,0,1,0,6.09,14.89ZM17.1,5.38a8.53,8.53,0,0,0-6.67-4.09v7.9Zm-.89,10.05A8.54,8.54,0,0,0,17.58,6.3l-6.7,3.84Z"/></g></g></svg> View Charts</div>

            </div>
            <h2 class="heading heading__2"><?=$user_name;?></h2>

            <div class="data-section tables">

                <div class="data-table">
                    <div class="data-table__head">
                        <div>
                            <h3 class="heading heading__4">Account Name</h3>
                        </div>
                        <div>
                            <h3 class="heading heading__4">Invested</h3>
                        </div>
                        <div>
                            <h3 class="heading heading__4">Value</h3>
                        </div>
                        <div>
                            <h3 class="heading heading__4">Gain(£)</h3>
                        </div>
                        <div>
                            <h3 class="heading heading__4">Gain(%)</h3>
                        </div>
                    </div>
                    <?php
                        // Connect and create the PDO object
                          $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
                          $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
                          foreach ($products as $product):
                              $the_product = $product['fs_product_type'];
                              $inv_ammount = $value = $total_shares_qty = 0;
                              $shares = array(); $shares_per = array();  $fund_name = array();  $invested_in_fund = array();
                              $query = "SELECT * FROM `tbl_fs_transactions` where fs_deal_type NOT LIKE 'Periodic Advisor Charge' AND fs_product_type LIKE '$the_product' AND fs_client_code LIKE '$client_code' AND bl_live = 1 ORDER BY fs_transaction_date ASC;";
                              debug($query);

                              $result = $conn->prepare($query);
                              $result->execute();
                              debug('Record Count = '+$result->rowCount());
                                // Parse returned data
                                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    $account_name = $user_name." - ".$the_product;
                                    $inv_ammount += $row['fs_iam'];
                                    $isin = $row['fs_isin_code'];
                                    //$latest_price = get_current_price($row['fs_isin_code']);
                                    $shares[$isin] += $row['fs_shares'];
                                    $fund_name[$isin] = $row['fs_fund_name'];
                                    $invested_in_fund[$isin] += $row['fs_iam'];
                                    $cur = $row['fs_currency_code'];
                                }
                    debug(count($invested_in_fund));
                    foreach($shares as $isin => $shares_qty) {
                        $value += $shares_qty * get_current_price("$isin");
                        $total_shares_qty += $shares_qty;
                        $classname = str_replace(" ","",$account_name);
                      }
                    ?>
                    <div class="data-table__account-wrapper">
                        <div class="data-table__body">
                            <div>
                                <p class="heading heading__4"><?=$account_name;?></p>
                            </div>
                            <div>
                                <p class="heading heading__4"><?=$cur_code[$cur] . number_format($inv_ammount,2);?></p>
                            </div>
                            <div>
                                <p class="heading heading__4"><?=$cur_code[$cur] . number_format(($value),2);?></p>
                            </div>
                            <div>
                                <p class="heading heading__4"><?=$cur_code[$cur] . number_format($value - $inv_ammount,2);?></p>
                            </div>
                            <div>
                                <p class="heading heading__4"><?=number_format(100*($value/$inv_ammount)-100,4);?></p>
                            </div>
                            <div>
                                <div class="toggle button button__raised button__toggle"><i class="fas fa-caret-down arrow"></i></div>
                            </div>
                        </div>
                        <div class="toggle-section">
                            <div class="data-table__extended titles">
                                <div></div>
                                <div class="split">
                                    <div><h4 class="heading heading__5">Holding</h4></div>
                                    <div><h4 class="heading heading__5">Invested</h4></div>
                                </div>
                                <div class="split">
                                    <div><h4 class="heading heading__5">Book Cost</h4></div>
                                    <div><h4 class="heading heading__5">Value</h4></div>
                                </div>
                                <div class="split">
                                    <div><h4 class="heading heading__5">Growth(£)</h4></div>
                                    <div><h4 class="heading heading__5">Growth(%)</h4></div>
                                </div>
                                <div>
                                    <h4 class="heading heading__5">Benchmark</h4>
                                </div>
                            </div>
                            <?php
                            foreach($shares as $isin => $shares_qty) {
                                $shares_per[$isin] = ($shares_qty / $total_shares_qty) * 100;
                                $inv = $invested_in_fund[$isin];
                                $val = $shares_qty * get_current_price("$isin");
                                $growth = $val - $inv;
                                $growth_percent = ($growth/$inv) * 100;

                                if($shares_per[$isin]>0){
                                ?>

                                <div class="data-table__extended">
                                    <div><?=$fund_name[$isin];?>-<?=$isin;?></div>
                                    <div class="split">
                                        <div><h4 class="heading heading__5"><?=round($shares_per[$isin],1);?>%</h4></div>
                                        <div><h4 class="heading heading__5"><?=$cur_code[$cur] . number_format($inv,2);?></h4></div>
                                    </div>
                                    <div class="split">
                                        <div><h4 class="heading heading__5">xxx</h4></div>
                                        <div><h4 class="heading heading__5"><?=$cur_code[$cur] . number_format($val,2);?></h4></div>
                                    </div>
                                    <div class="split">
                                        <div><h4 class="heading heading__5"><?=$cur_code[$cur] . number_format($growth,2);?></h4></div>
                                        <div><h4 class="heading heading__5"><?=number_format($growth_percent,2);?>&percnt;</h4></div>
                                    </div>
                                    <div>
                                        <h4 class="heading heading__5"><?=number_format(get_benchmark("$isin"),2);?>&percnt;</h4>
                                    </div>
                                </div>
                            <?php } }?>
                        </div>
                    </div><!--account wrapper-->
                    <?php endforeach; $conn = null; // Disconnect?>
                </div><!--data-table-->

            </div><!--data section-->



<div class="data-section chart">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart" height="400"></canvas>

            </div>
        </div>

    </div>
</div>



</div>

</div>

</div>

</div>

	<!-- Footer -->
      <footer class="col-md-12 mt-5">

            <span>Copyright &copy; Featherstone 2020</span>

      </footer>
      <!-- End of Footer -->

<?php define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/modals/logout.php');
require_once(__ROOT__.'/modals/time-out.php');
require_once(__ROOT__.'/global-scripts.php');?>

   <script>

		Chart.defaults.global.legend.display = false;

/* ##########################################       LINE CHART     ################################################## */

		<?php
		try {
		  // Connect and create the PDO object
		  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
		  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

			// Latest Date
			$monthago = Date("Y-m-d", strtotime("2020-01-23 -60 days"));

		//    Get the price data for Client Graph   ///

		  $query = "SELECT * FROM `tbl_fs_transactions` where fs_deal_type NOT LIKE 'Periodic Advisor Charge' AND fs_product_type LIKE 'ISA' AND fs_client_code LIKE '$client_code' AND bl_live = 1 AND fs_transaction_date > '$monthago' ORDER BY fs_transaction_date ASC;";

		  $result = $conn->prepare($query);
		  $result->execute();

		  // Parse returned data
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $data1 .= $row['fs_t_price']*$row['fs_shares'].',';
			  $labels1 .= "'".$row['fs_transaction_date']."',";
		  }

		}

		catch(PDOException $e) {
		  echo $e->getMessage();
		}
		?>

		var ctxline = document.getElementById('linechart');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:.3,
					borderColor:['rgba(0, 0, 0, 1)'],
					borderWidth:1,
                    color: ['rgba(253, 0, 0, 0.95)'],
					label:'Performance Data',
					data:[<?=$data1;?>],
				}],
				labels: [<?=$labels1;?>]
			},

			options: { tooltips: {enabled: true}}
		});
    </script>
  </body>
</html>
