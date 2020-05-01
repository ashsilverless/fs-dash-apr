<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


     //    Get the products   ///

  $query = "SELECT DISTINCT isin_code FROM `tbl_fs_fund` where bl_live = 1;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $isincodes[] = $row['isin_code'];
  }

  $conn = null;        // Disconnect

}


catch(PDOException $e) {
  echo $e->getMessage();
}

$initialDate = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
?>

<div class="container">
    <div class="border-box main-content daily-data">
<!--<a href="#" class="button button__raised button__inline">Add Fund</a>-->
<h1 class="heading heading__2">Daily & Historical Prices</h1>

<div class="prices-table">

<div class="prices-table__head">
    <div>
        <h3 class="heading heading__4">Fund Name</h3>
    </div>
    <div>
        <h3 class="heading heading__4">ISIN Code</h3>
    </div>
    <div>
        <h3 class="heading heading__4">Fund SEDOL</h3>
    </div>
    <div>
        <h3 class="heading heading__4">Benchmark</h3>
    </div>
    <div>
        <h3 class="heading heading__4">Current Price</h3>
        <div class="split">
            <div><h4 class="heading heading__4">Price</h4></div>
            <div><h4 class="heading heading__4">As At</h4></div>
        </div>
    </div>
    <div>
        <h3 class="heading heading__4">Add New Price</h3>
        <div class="split">
            <div><h4 class="heading heading__4">Price</h4></div>
            <div><h4 class="heading heading__4">Date</h4></div>
        </div>
    </div>
    <div></div>
</div>

<div class="recess-box">
    <?php
    try {
      // Connect and create the PDO object
      $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
      $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

      $codes = array();
         //    Get the funds   //

        foreach($isincodes as $code) {
            $query = "SELECT *  FROM `tbl_fs_fund` where isin_code LIKE '$code' AND bl_live = 1 ORDER BY correct_at DESC LIMIT 1;";

            $result = $conn->prepare($query);
            $result->execute();

              // Parse returned data
              while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $codes[] = $row['isin_code'];
                $as_at = date('j M y',strtotime($row['correct_at'])); ?>
    <form method="post" name="form<?=$row['isin_code'];?>" id="form<?=$row['isin_code'];?>" class="fund">
        <div class="prices-table__account">
        <div class="fund-toggle">
            <h3 class="heading heading__4"><?= $row['fund_name'];?></h3>
            <span><i class="fas fa-sort-down"></i></span>
        </div>
        <div>
            <h3 class="heading heading__4"><?= $row['isin_code'];?></h3>
        </div>
        <div>
            <h3 class="heading heading__4"><?= $row['fund_sedol'];?></h3>
        </div>
        <div>
            <h3 class="heading heading__4"><?= $row['benchmark'];?></h3>
        </div>
        <div>
            <div class="split">
                <div><h4 class="heading heading__4"><?= $row['current_price'];?></h4></div>
                <div><h4 class="heading heading__4"><?= $as_at;?></h4></div>
            </div>
        </div>
        <div>
            <div class="split">
                <div class="narrow"><input name="price<?=$row['isin_code'];?>" type="text" id="price<?=$row['isin_code'];?>" title="price" value="0.00" size="4"></div>
                <div class="narrow"><input name="pricedate<?=$row['isin_code'];?>" type="text" id="pricedate<?=$row['isin_code'];?>" title="pricedate" value="" size="6"></div>
            </div>
        </div>
        <div>





                <button type="submit" class="button button__raised">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    	 viewBox="0 0 21.8 21.8" style="enable-background:new 0 0 21.8 21.8;" xml:space="preserve">
                    <style type="text/css">
                    	.st0{fill:#96E8C4;}
                    </style>
                    <g id="Layer_2_1_">
                    	<g id="Layer_1-2">
                    		<path class="st0" d="M7.7,19.4c-0.1-0.1-0.3-0.2-0.5-0.2H4.9c-1.6,0-2.3-0.7-2.3-2.3v-2.3c0-0.2-0.1-0.4-0.2-0.5l-1.6-1.6
                    			c-0.9-0.7-1.1-1.9-0.4-2.8c0.1-0.1,0.2-0.3,0.4-0.4l1.6-1.6c0.1-0.1,0.2-0.3,0.2-0.5V4.9c0-1.6,0.7-2.3,2.3-2.3h2.3
                    			c0.2,0,0.4-0.1,0.5-0.2l1.6-1.6c0.6-0.9,1.8-1.1,2.7-0.5c0.2,0.1,0.4,0.3,0.5,0.5l1.6,1.6c0.1,0.1,0.3,0.2,0.5,0.2h2.3
                    			c1.6,0,2.3,0.7,2.3,2.3v2.2c0,0.2,0.1,0.4,0.2,0.5L21,9.3c0.9,0.7,1.1,1.9,0.4,2.8c-0.1,0.1-0.2,0.3-0.4,0.4l-1.6,1.6
                    			c-0.2,0.1-0.2,0.3-0.2,0.5v2.3c0,1.6-0.7,2.3-2.3,2.3h-2.3c-0.2,0-0.4,0.1-0.5,0.2L12.5,21c-0.6,0.9-1.8,1.1-2.7,0.5
                    			c-0.2-0.1-0.3-0.3-0.5-0.5L7.7,19.4z M11.7,20.1l1.9-1.9c0.2-0.2,0.4-0.3,0.7-0.3H17c0.9,0,1.1-0.2,1.1-1.1v-2.7
                    			c0-0.3,0.1-0.5,0.3-0.7l1.9-1.9c0.6-0.6,0.6-0.9,0-1.5l-1.9-1.9C18.1,8.1,18,7.8,18,7.6V4.9c0-0.9-0.2-1.1-1.1-1.1h-2.7
                    			c-0.3,0-0.5-0.1-0.7-0.3l-1.9-1.9C11,1,10.8,1,10.1,1.7L8.3,3.5C8.1,3.7,7.8,3.9,7.6,3.8H4.9C4,3.8,3.8,4,3.8,4.9v2.7
                    			c0,0.3-0.1,0.5-0.3,0.7l-1.9,1.9C1,10.8,1,11,1.7,11.7l1.9,1.9c0.2,0.2,0.3,0.4,0.3,0.7v2.7C3.8,17.8,4,18,4.9,18h2.7
                    			c0.3,0,0.5,0.1,0.7,0.3l1.9,1.9C10.8,20.8,11,20.8,11.7,20.1L11.7,20.1z M8.9,15.4l-3.2-3.6c-0.1-0.1-0.2-0.3-0.2-0.4
                    			c0-0.4,0.3-0.6,0.7-0.6c0.2,0,0.3,0.1,0.4,0.2l2.7,3l5.1-7.2c0.2-0.3,0.6-0.4,0.9-0.2c0.2,0.1,0.3,0.3,0.3,0.5
                    			c0,0.1-0.1,0.3-0.1,0.4l-5.6,7.9c-0.1,0.2-0.3,0.2-0.5,0.2C9.2,15.5,9,15.5,8.9,15.4L8.9,15.4z"/>
                    	</g>
                    </g>
                    </svg>
Save
              </button>

        </div>
    </div>
    <div class="fund-table-wrapper">
        <table class="funds-table">
        <tr class="<?=$row['isin_code'];?>">
        <td align="center" colspan="10" id="daily_prices<?= $row['isin_code'];?>"></td>
        </tr>
        <tr class="<?=$row['isin_code'];?>"><td colspan="10" align="center"><!--  #Delete Fund    <a href="#" data-href="deletefund.php?ic=<?= $row['isin_code'];?>" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger" style="font-size:0.85em; font-weight:bold;">Delete Fund</a> --></td></tr>
    </table>
    </div>
    </form>
    <?php }
      }
    $conn = null;        // Disconnect

    }

    catch(PDOException $e) {
    echo $e->getMessage();
    }
    ?>

</div>




</div>
</div>
</div>
</div>

<?php require_once('page-sections/footer-elements.php');?>


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
          <a class="btn btn-primary" href="index.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

<?php require_once('page-sections/footer-elements.php');
require_once('modals/delete.php');
require_once('modals/logout.php');
require_once(__ROOT__.'/global-scripts.php');?>
<!-- Bootstrap Edit in Place -->
	<link href="be/css/bootstrap-editable.css" rel="stylesheet" type="text/css">
	<script src="be/js/bootstrap-editable.js"></script>
	<script src="be/js/moment.min.js"></script>
    <script>

		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});

		$(".addfund").click(function(e){
          e.preventDefault();
		  $("#funddetails").load("add_fund.php");
		});

		$('#confirm-delete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});


	function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

	<?php for($a=0;$a<count($codes);$a++){ ?>


		$('#pricedate<?=$codes[$a]?>').datepicker({  format: "yyyy-mm-dd" , todayHighlight: true });

		$("#form<?=$codes[$a]?>").submit(function(e) {
			e.preventDefault(); // avoid to execute the actual submit of the form.
			var form = $(this);
			$.ajax({
				   type: "POST",
				   url: 'addfundprice.php?ic=<?=$codes[$a]?>',
				   data: form.serialize(), // serializes the form's elements.
				   success: function(data){ $("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt=<?= $initialDate ;?>&ic=<?=$codes[$a]?>"); }
				 });
		});

		$("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt=<?= $initialDate ;?>&ic=<?=$codes[$a]?>");

		$(document).on('click', '.monthback<?=$codes[$a]?>', function(e) {
            e.preventDefault();
            var dt = getParameterByName('dt',$(this).attr('href'));
            $("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt="+dt+"-01&ic=<?=$codes[$a]?>");
        });

        $(document).on('click', '.monthnext<?=$codes[$a]?>', function(e) {
            e.preventDefault();
            var dt = getParameterByName('dt',$(this).attr('href'));
            $("#daily_prices<?=$codes[$a]?>").load("getrcalendarprices.php?dt="+dt+"-01&ic=<?=$codes[$a]?>");
        });
	<?php } ?>

    </script>
  </body>
</html>
