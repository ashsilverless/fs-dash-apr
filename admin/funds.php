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
            <a href="#" class="button button__raised">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.82 21.82"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M7.71,19.39a.71.71,0,0,0-.54-.22H4.91c-1.57,0-2.26-.69-2.26-2.26V14.65a.67.67,0,0,0-.23-.53L.83,12.5a2,2,0,0,1,0-3.19l1.59-1.6a.72.72,0,0,0,.23-.54V4.92c0-1.59.69-2.27,2.26-2.27H7.17a.73.73,0,0,0,.54-.22L9.31.83a1.94,1.94,0,0,1,3.19,0l1.61,1.6a.71.71,0,0,0,.54.22h2.26c1.57,0,2.26.69,2.26,2.27V7.17a.72.72,0,0,0,.23.54L21,9.31a2,2,0,0,1,0,3.19L19.4,14.12a.67.67,0,0,0-.23.53v2.26c0,1.57-.69,2.26-2.26,2.26H14.65a.71.71,0,0,0-.54.22L12.5,21a1.94,1.94,0,0,1-3.18,0Zm4,.76,1.87-1.88a.89.89,0,0,1,.7-.29h2.67c.89,0,1.07-.17,1.07-1.07V14.23a1,1,0,0,1,.28-.69l1.89-1.87c.63-.64.63-.87,0-1.52L18.26,8.28a.94.94,0,0,1-.28-.7V4.92c0-.9-.18-1.08-1.07-1.08H14.24a.89.89,0,0,1-.7-.29L11.67,1.67C11,1,10.79,1,10.15,1.67L8.28,3.55a.89.89,0,0,1-.7.29H4.91C4,3.84,3.84,4,3.84,4.92V7.58a.94.94,0,0,1-.28.7L1.67,10.15c-.63.65-.63.88,0,1.52l1.89,1.87a1,1,0,0,1,.28.69v2.68c0,.9.17,1.07,1.07,1.07H7.58a.89.89,0,0,1,.7.29l1.87,1.88C10.79,20.79,11,20.79,11.67,20.15Zm-2.8-4.77L5.68,11.76a.55.55,0,0,1-.18-.41.64.64,0,0,1,1.11-.41l2.72,3,5.12-7.25A.63.63,0,0,1,15.61,7a.77.77,0,0,1-.14.4l-5.63,7.9a.6.6,0,0,1-.5.23A.64.64,0,0,1,8.87,15.38Z"/></g></g></svg>
                Save</a>
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


		$('#pricedate<?=$codes[$a]?>').datepicker({  format: "dd mm yy" , todayHighlight: true });

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
