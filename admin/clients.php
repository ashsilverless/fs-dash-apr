<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

//  Record per page
if($_GET['rpp']!=""){
	$_SESSION["rpp"] = $_GET['rpp'];
}

if($_GET['page']!=""){
	$page=$_GET['page'];
}



if($page==""){
	$page = 0;
}

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){
	$recordsPerPage = 10;
}


//    Get the user details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT id FROM `tbl_fsusers` where user_type LIKE 'user' AND bl_live = 1 ORDER BY fs_client_code ASC;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $row;
	}

	$num_rows = count($rows);

	$totalPageNumber = ceil($num_rows / $recordsPerPage);
	$offset = $page*$recordsPerPage;

	debug($num_rows);

	$query = "SELECT *  FROM `tbl_fsusers` where user_type LIKE 'user' AND bl_live = 1 ORDER BY fs_client_code ASC LIMIT $offset,$recordsPerPage;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$userData[] = $row;
	}

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

$rspaging = '<div style="margin:auto; padding:15px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;"><strong>'.$num_rows.'</strong> results in <strong>'.$totalPageNumber.'</strong> pages.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page : ';

if($page<3){
	$start=1;
	$end=7;
}else{
	$start=$page-2;
	$end=$page+4;
}

if($end >= $totalPageNumber){
  $endnotifier = "";
  $end = $totalPageNumber;
}else{
  $endnotifier = "...";
}

$frst = '<a href="?page=0'.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>';
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=10">10</a>&nbsp;|&nbsp;<a href="?rpp=30">30</a>&nbsp;|&nbsp;<a href="?rpp=50">50</a>&nbsp;|&nbsp;<a href="?rpp=999"><strong>All</strong></a></span>';

$rspaging .= $endnotifier.$last.$ipp.'</div>';

?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
?>

<div class="container">
    <div class="border-box main-content">
		<a href="add_client.php" class="button button__raised button__inline">Add New Client</a>
<h1 class="heading heading__2">Clients</h1>

<div class="clients-table">
    <div class="clients-table__head">
        <h3 class="heading heading__4">Client Name</h3>
        <h3 class="heading heading__4">User ID</h3>
        <h3 class="heading heading__4">Strategy</h3>
        <h3 class="heading heading__4">Linked Account Names</h3>
    </div>

    <div class="recess-box">

		<?php
		foreach($userData as $client) {
			$linkedNames = '';
			if($client['linked_accounts']!=''){
				$lnk_array = explode('|',$client['linked_accounts']);
				for($b=0;$b<count($lnk_array);$b++){
					if($lnk_array[$b]!=''){
						$linkedNames .= getUserName($lnk_array[$b]).'<br>';
					};
				}
			}
			?>
			<div class="clients-table__item">
				<h3 class="heading heading__4"><?= $client['user_name'];?></h4>
				<p><?= $client['fs_client_code'];?></p>
				<p class="strategy <?php $clientClass = strtolower($client['strategy']); echo $clientClass;?>"><?= $client['strategy'];?></p>
				<p><?=$linkedNames;?></p>
				<a href="edit_client.php?id=<?= $client['id'];?>" class="button button__raised">Edit</a>
			</div><!--item-->
<?php } ?>


</div>
<?=$rspaging;?>

        <!--<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 mb-5">

        <h1 class="h2">Clients</h1>
			<a href="add_client.php"><i data-feather="plus-square"></i> Add New Client</a>

			<div class="col-md-10  table-responsive mt-5">
			  <table class="table table-sm table-striped">
			    <thead>
					<tr>
				      <th width="30%" bgcolor="#FFFFFF"><strong>Client Name <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
					  <th width="20%" bgcolor="#FFFFFF"><strong>User ID <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
					  <th width="20%" bgcolor="#FFFFFF"><strong>Strategy <i data-feather="maximize-2" style="transform: rotate(-45deg)"></i></strong></th>
					  <th width="25%" bgcolor="#FFFFFF"><strong>Linked Account Names</strong></th>
					  <th width="5%" bgcolor="#FFFFFF"></td>
				  </tr>
				</thead>
				<tbody>
					<?php
					foreach($userData as $client) {
						$linkedNames = '';
						if($client['linked_accounts']!=''){
							$lnk_array = explode('|',$client['linked_accounts']);
							for($b=0;$b<count($lnk_array);$b++){
								if($lnk_array[$b]!=''){
									$linkedNames .= getUserName($lnk_array[$b]).'<br>';
								};
							}
						}
						?>
								<tr>
								  <td style="border-right:1px dashed #999;"><?= $client['user_name'];?></td>
								  <td style="border-right:1px dashed #999;"><?= $client['fs_client_code'];?></td>
								  <td style="border-right:1px dashed #999;"><span class='<?= $client['strategy'];?>'><?= $client['strategy'];?></span></td>
								  <td style="font-size:0.85em;"><?=$linkedNames;?></td>
								  <td><a href="edit_client.php?id=<?= $client['id'];?>" class="btn btn-admin" style="font-size:0.8em; font-weight:bold;">Edit</a></td>
							  </tr>
						<?php } ?>
			      </tbody>
				</table>


		  </div>

			<?=$rspaging;?>



		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>

		<div id="assetdetails" class="col-md-12 mt-5"></div>

	</main>-->
      </div>
    </div>

<?php require_once('page-sections/footer-elements.php');
require_once('modals/delete.php');
require_once('modals/logout.php');
require_once(__ROOT__.'/global-scripts.php');?>

    <script>

		$( document ).ready(function() {

			$(".table").tablesorter();

		});

		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});

		$(".addclient").click(function(e){
          e.preventDefault();
		  $("#clientdetails").load("add_client.php");
		});

		$(".editasset").click(function(e){
          e.preventDefault();
		  var theme_id = getParameterByName('id',$(this).attr('href'));
			console.log(theme_id);
		  $("#assetdetails").load("edit_asset.php?id="+theme_id);
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



    </script>
  </body>
</html>
