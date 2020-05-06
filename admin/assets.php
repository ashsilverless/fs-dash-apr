<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
?>

<div class="container">
    <div class="border-box main-content daily-data">
<a href="#" class="addasset button button__raised button__inline">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.82 16.22"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M7.25,15.57V8.78H.66a.67.67,0,0,1,0-1.33H7.25V.65a.66.66,0,0,1,1.32,0v6.8h6.6a.67.67,0,0,1,0,1.33H8.57v6.79a.66.66,0,0,1-1.32,0Z"/></g></g></svg>
    Add Asset</a>
<div id="assetdetails" class="expand-panel newasset"></div>
<div id="editasset" class="expand-panel editasset-target"></div>

<h1 class="heading heading__2">Asset Allocation & Holdings</h1>

<div class="asset-table">
    <div class="asset-table__head">
        <h3 class="heading heading__4">Asset Name</h3>
        <h3 class="heading heading__4">Category</h3>
        <h3 class="heading heading__4">Steady</h3>
        <h3 class="heading heading__4">Sensible</h3>
        <h3 class="heading heading__4">Serious</h3>
    </div>

    <div class="recess-box">

        <?php
        try {
          // Connect and create the PDO object
          $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
          $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

            $query = "SELECT *  FROM `tbl_fs_assets` where bl_live = 1;";

            $result = $conn->prepare($query);
            $result->execute();

                  // Parse returned data
                  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $row['fs_growth_steady'] == '0' ? $show_steady = '' : $show_steady = $row['fs_growth_steady'].'%';
                    $row['fs_growth_sensible'] == '0' ? $show_sensible = '' : $show_sensible = $row['fs_growth_sensible'].'%';
                    $row['fs_growth_serious'] == '0' ? $show_serious = '' : $show_serious = $row['fs_growth_serious'].'%';
                  ?>

        <div class="asset-table__item">
            <p><?= $row['fs_asset_name'];?></p>
            <p><?= getField('tbl_fs_categories','cat_name','id',$row['cat_id']);?></p>
            <p><?= $show_steady;?></p>
            <p><?= $show_sensible;?></p>
            <p><?= $show_serious;?></p>
            <p><a href="#?id=<?=$row['id'];?>" class="editasset-trigger button button__raised">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20.77 20.77"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M3.69,9.72a.66.66,0,0,1,0,1.32h-3a.66.66,0,1,1,0-1.32ZM5.2,14.65a.64.64,0,0,1,.92,0,.66.66,0,0,1,0,.93L4,17.71a.67.67,0,0,1-.93,0,.66.66,0,0,1,0-.93ZM3.07,4A.65.65,0,1,1,4,3.07L6.12,5.21a.64.64,0,0,1,0,.92.65.65,0,0,1-.92,0Zm6.2,6.61a.9.9,0,0,1,0-1.26.87.87,0,0,1,1.25,0l9.35,9.38a.91.91,0,0,1,0,1.26.88.88,0,0,1-1.26,0Zm3.92,2.26L10.27,9.93c-.16-.16-.32-.19-.47-.06a.31.31,0,0,0,0,.47l2.91,2.93ZM11,3.68a.66.66,0,1,1-1.31,0v-3A.66.66,0,0,1,11,.65Zm0,16.43a.66.66,0,1,1-1.31,0v-3a.66.66,0,1,1,1.31,0Zm5.74-17a.65.65,0,0,1,.93,0,.67.67,0,0,1,0,.93L15.57,6.13a.65.65,0,0,1-.93,0,.64.64,0,0,1,0-.92Zm.31,8a.66.66,0,1,1,0-1.32h3a.66.66,0,0,1,0,1.32Z"/></g></g></svg>
                Edit Asset</a></p>
        </div>
        <?php
            $steady += $row['fs_growth_steady'];      $sensible += $row['fs_growth_sensible'];      $serious += $row['fs_growth_serious'];
        }

        $conn = null;        // Disconnect
        $steady < 100 ? $steadyStyle = 'warning' : $steadyStyle = '';
        $sensible < 100 ? $sensibleStyle = 'warning' : $sensibleStyle = '';
        $$serious < 100 ? $seriousStyle = 'warning' : $seriousStyle = '';
        }

        catch(PDOException $e) {
        echo $e->getMessage();
        }
        ?>


    </div>
    <div class="asset-table">
        <div class="asset-table__foot">
            <h3 class="heading heading__4">Totals</h3>
            <p class="<?= $steadyStyle ;?>"><?= $steady ;?>%</p>
            <p class="<?= $sensibleStyle ;?>"><?= $sensible ;?>%</p>
            <p class="<?= $seriousStyle ;?>"><?= $serious ;?>%</p>
        </div>
    </div><!--asset table-->
</div>

        </div>
    </div>

<?php
require_once('page-sections/footer-elements.php');
require_once('modals/delete.php');
require_once('modals/logout.php');
require_once('modals/delete-cat.php');
require_once(__ROOT__.'/global-scripts.php');?>

    <script>

		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});

		$(".addasset").click(function(e){
          e.preventDefault();
		  $("#assetdetails").load("add_asset.php");
          $('.expand-panel.newasset').addClass('open');
          $('.expand-panel__cancel-button').addClass('visible');
		});

        $(".expand-panel__cancel-button").click(function(e){
          e.preventDefault();
          $('.expand-panel').removeClass('open');
          $('.expand-panel__cancel-button').removeClass('visible');
          $('.addasset.button').show();
		});

		$(".editasset-trigger").click(function(e){
            e.preventDefault();
            var theme_id = getParameterByName('id',$(this).attr('href'));
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $("#editasset").load("edit_asset.php?id="+theme_id);
            $('.expand-panel.editasset-target').addClass('open');
            $('.expand-panel__cancel-button').addClass('visible');
            $('.addasset.button').hide();
            //$('.expand-panel__cancel-button').hide();
		});

		$('#confirm-delete, #confirm-catdelete').on('show.bs.modal', function(e) {
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

    </script>
  </body>
</html>
