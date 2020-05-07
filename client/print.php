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
$testVar = 'test';
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

<?php
$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = $_SESSION['fs_client_featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');
$confirmed_date = $row['confirmed_date']= date('d M Y');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));

$strategy = 'fs_growth_'.strtolower(getField('tbl_fsusers','strategy','id',$_SESSION['fs_client_user_id']));
//REMOVE NEXT LINE WHEN PUSHING
$strategy = 'fs_growth_'.strtolower(getField('tbl_fsusers','strategy','id','5'));

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

<?php

$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = $_SESSION['fs_client_featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');
$confirmed_date = $row['confirmed_date']= date('d M Y');
$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
     //    Get the general products data for Client   ///
  $query = "SELECT * FROM tbl_fsusers where id = '$user_id' AND bl_live = 1;";
	debug($query);
  $result = $conn->prepare($query);
  $result->execute();
  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  $user_name = $row['user_name'];
	  $strategy = $row['strategy'];
  }

  switch ($strategy) {
    case 'Sensible':
        $strategy_str = 'fs_theme_sensible';
        break;
    case 'Steady':
        $strategy_str = 'fs_theme_steady';
        break;
    case 'Serious':
        $strategy_str = 'fs_theme_serious';
        break;
  }
  $conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}
?>
        <div class="col-md-9">
            <div class="border-box main-content">
                <div class="main-content__head">
                    <h1 class="heading heading__1">Current Investment Themes</h1>
                    <p class="mb3">Data accurate as at <?= $confirmed_date;?></p>
                </div>

				<div class="container">

                    <div class="recess-box">
                        <div class="themes-table front">
                	<?php
                	try {
                	  // Connect and create the PDO object
                	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
                	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
                		$query = "SELECT *  FROM `tbl_fs_themes` where $strategy_str = '1' AND bl_live = 1;";

                		debug($query);
                		$result = $conn->prepare($query);
                		$result->execute();
                			  // Parse returned data
                			  while($row = $result->fetch(PDO::FETCH_ASSOC)) {  ?>
                    		<div class="themes-table__item">
                    			<img src="../icons_folder/<?= $row['fs_theme_icon'];?>">
                                <h3 class="heading heading__4"><?= $row['fs_theme_title'];?></h3>
                    			<p><?= substr($row['fs_theme_narrative'],0,385);?>...</p>
                    		</div>
                	<?php }
                	$conn = null;        // Disconnect
                	}
                	catch(PDOException $e) {
                	echo $e->getMessage();
                	}?>
                        </div>
                    </div>

                </div>
		    </div><!--9-->
		</div>
    </div>
</div>

<?php
$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = $_SESSION['fs_client_featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));
$confirmed_date = $row['confirmed_date']= date('d M Y');
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

    <div class="col-md-9">

        <div class="border-box main-content">
              <div class="main-content__head">
                  <h1 class="heading heading__1">Peer Group Comparison</h1>
                  <p class="mb3">Data accurate as at <?= $confirmed_date;?></p>
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
                        // first coord is multiplied by 10
                        // second coord is multiplied by 100 and removed from 100
                        //54,37 76,30
                    if($row['fs_trend_line'] == 1) {
                        $trendRet = ($row['fs_peer_return'] * 10);
                        $trendVol = 100 - ($row['fs_peer_volatility'] * 10);
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
