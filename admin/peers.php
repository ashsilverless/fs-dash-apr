<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


//    Get the user details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT * FROM `tbl_fs_peers` where bl_live > 0 ORDER BY id ASC;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$peerGroup[] = $row;
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
require_once('page-sections/header-elements.php');
?>

<div class="container">
    <div class="border-box main-content">
<h1 class="heading heading__2">Peer Comparison</h1>

<div class="peer-table">
    <div class="peer-table__head">
        <h3 class="heading heading__4">Peer</h3>
        <h3 class="heading heading__4">Return</h3>
        <h3 class="heading heading__4">Volatility</h3>
        <h3 class="heading heading__4">Trend Line</h3>
    </div>

    <div class="recess-box">
    <?php foreach($peerGroup as $peer) {?>

    <div class="peer-table__item">
        <h3 class="heading heading__4"><?= $peer['fs_peer_name'];?></h3>
        <p><?= $peer['fs_peer_return'];?></p>
        <p><?= $peer['fs_peer_volatility'];?></p>

        <a href="edittrend.php?id=<?= $peer['id'];?>&tl=<?=$peer['fs_trend_line'];?>" class="trend-line-indicator" style="font-size:0.8em; font-weight:bold;"><?php $peer['fs_trend_line'] == '0' ? $trendLine = '' : $trendLine = include('images/star.php');?></a>
        <a href="edit_peer.php?id=<?= $peer['id'];?>" class="button button__raised">Edit</a>
        <a href="#" data-href="deletepeer.php?id=<?= $peer['id'];?>" data-toggle="modal" data-target="#confirm-delete" class="button button__raised button__danger">Delete</a>
    </div><!--item-->
<?php } ?>
</div>
</div><!--table-->

</div>
</div>
      </div>
    </div>

    <?php require_once('page-sections/footer-elements.php');
    require_once('modals/delete.php');
    require_once('modals/logout.php');
    require_once(__ROOT__.'/global-scripts.php');?>

	<!-- Colour Picker -->
	<script src="js/jscolor.js"></script>

    <script>

	$(document).ready(function() {

		feather.replace()

		$(".table").tablesorter();

		$(".edit").click(function(e){
          e.preventDefault();
		  var peer_id = getParameterByName('id',$(this).attr('href'));
		  $("#peer").load("edit_peer.php?id="+peer_id);
		});

		$('#confirm-delete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});

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
