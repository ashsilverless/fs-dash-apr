<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$client_id = $_GET['id'];

//    Get the user details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


	$query = "SELECT *  FROM `tbl_fsusers` where id = $client_id;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {

		$fs_client_code = $row['fs_client_code'];
		$user_name = $row['user_name'];
		$email_address = $row['email_address'];
		$telephone = $row['telephone'];
		$strategy = $row['strategy'];
		$linked_accounts = $row['linked_accounts'];
		$desc = $row['fs_client_desc'];
	}


	 $query = "SELECT * FROM `tbl_fs_client_products` where fs_client_code LIKE '$fs_client_code' AND bl_live = 1;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $products[] = $row;
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
        <h1 class="heading heading__2">Client Details</h1>
		<form action="editclient.php?id=<?=$client_id;?>" method="post" id="editclient" name="editclient" class="asset-form">
            <div class="content client">
                <div class="client__pers-details">
                    <div class="item half mb1">
                        <label>Client Name</label>
                        <input type="text" id="client_name" name="client_name" value="<?=$user_name;?>">
                    </div>
                    <div class="item half">
                        <label>Client Email</label>
                        <input type="text" id="client_email" name="client_email" value="<?=$email_address;?>">
                    </div>
                    <div class="item mb1">
                        <label>User ID</label>
                            <input type="text" id="fs_client_code" name="fs_client_code" value="<?=$fs_client_code;?>">
                    </div>
                    <div class="item">
                        <label>Strategy</label>
                            <div class="select-wrapper">
                            <select name="strategy" id="strategy" class="select-css">
                              <option value="Sensible" <?php if(strtolower ($strategy)=='sensible'){?>selected = 'selected' <?php }?>>Sensible</option>
                              <option value="Steady" <?php if(strtolower ($strategy)=='steady'){?>selected = 'selected' <?php }?>>Steady</option>
                              <option value="Serious" <?php if(strtolower ($strategy)=='serious'){?>selected = 'selected' <?php }?>>Serious</option>
                            </select>
                            <i class="fas fa-sort-down"></i>
                        </div>
                    </div>
                    <div class="item">
                        <label>Client Type</label>
                        <div class="select-wrapper">
                            <select name="fs_client_desc" id="fs_client_desc" class="select-css">
                              <option value="Private Client" <?php if(strtolower ($desc)=='private client'){?>selected = 'selected' <?php }?>>Private</option>
                              <option value="Corporate Client" <?php if(strtolower ($desc)=='corporate client'){?>selected = 'selected' <?php }?>>Corporate</option>
                            </select>
                            <i class="fas fa-sort-down"></i>
                        </div>

                    </div>
                    <div></div>
                </div><!--pers details-->
                <div class="client__pers-accounts">
                    <h3 class="heading heading__2">Accounts</h3>
                    <a href="#" class="addasset button button__raised button__inline">Add Account</a>
                    <div class="recess-box">
                    <div class="account-table">
                        <div class="account-table__head">
                            <label>Client Code</label>
                            <label>ISIN Code</label>
                            <label>Designator</label>
                            <label>Type</label>
                            <label>Display Name</label>
                        </div>
                        <?php foreach($products as $product) { ?>
                        <div class="account-table__body">
                            <input type="text" id="fs_client_code<?=$product['id'];?>" name="fs_client_code<?=$product['id'];?>" value="<?=$product['fs_client_code'];?>" readonly>

                            <input type="text" id="fs_isin_code<?=$product['id'];?>" name="fs_isin_code<?=$product['id'];?>" value="<?=$product['fs_isin_code'];?>" readonly>

                            <input type="text" id="designator<?=$product['id'];?>" name="designator<?=$product['id'];?>" value="<?=$product['fs_designation'];?>" readonly>

                            <select name="product_type<?=$product['id'];?>" id="product_type<?=$product['id'];?>">
                                <option value="ISA" <?php if(strtolower ($product['fs_product_type'])=='isa'){?>selected = 'selected' <?php }?>>ISA</option>
                                <option value="JISA" <?php if(strtolower ($product['fs_product_type'])=='jisa'){?>selected = 'selected' <?php }?>>JISA</option>
                                <option value="PIA" <?php if(strtolower ($product['fs_product_type'])=='pia'){?>selected = 'selected' <?php }?>>PIA</option>
                                <option value="SIPP" <?php if(strtolower ($product['fs_product_type'])=='sipp'){?>selected = 'selected' <?php }?>>SIPP</option>
                                <option value="Unwrapped" <?php if(strtolower ($product['fs_product_type'])=='unwrapped'){?>selected = 'selected' <?php }?>>Unwrapped</option>
                            </select>

                            <input type="text" id="display_name<?=$product['id'];?>" name="display_name<?=$product['id'];?>" value="<?=$product['fs_client_name'] . ' ' . $product['fs_product_type'];?>" readonly>

                        </div>
                         <?php } ?>
                    </div>
                </div>
                </div>
                <div class="client__linked-accounts">
                    <h3 class="heading heading__2">Linked Accounts</h3>
                    <a href="#" class="addasset button button__raised button__inline">Add Linked Account</a>
                    <div class="account-table">
                        <?php if($linked_accounts!=''){ $lnk_array = explode('|',$linked_accounts);?>
                        <?php for($b=0;$b<count($lnk_array);$b++){
                             if($lnk_array[$b]!=''){  ?>
                        <h3 class="heading heading__4">Linked Account Holder: Name Name <?=getUserName($lnk_array[$b])?></h3>
                        <div class="recess-box">
                        <div class="account-table__head">
                            <label>Client Code</label>
                            <label>ISIN Code</label>
                            <label>Designator</label>
                            <label>Type</label>
                            <label>Display Name</label>
                        </div><!--head-->
						<?php
						  // Connect and create the PDO object
						  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
						  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

						  $query = "SELECT * FROM `tbl_fs_client_products` where fs_client_code LIKE '$lnk_array[$b]' AND bl_live = 1;";

						  $result = $conn->prepare($query);
						  $result->execute();

						  // Parse returned data
						  while($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                            <!--For each row, fetch this-->
                            <div class="account-table__body">
    							<p><?=$lnk_array[$b];?></p>
                                <p><?=$row['fs_isin_code'];?></p>
                                <p><?=$row['fs_designation'];?></p>
                                <p><?=$row['fs_product_type'];?></p>
                                <p><?=getUserName($lnk_array[$b]) . ' ' . $row['fs_product_type'];?></p>
                            </div><!--body-->
						  <?php }
						  $conn = null; // Disconnect
						}?>
                    </div>
                    <?php  }?>
                    <?php }	?>
                </div>
                </div>
            </div><!--content-->
            <div class="control">
                <h3 class="heading heading__2">Account Actions</h3>
                <input type="submit" class="button button__raised" value="Save Changes">
            </div>
        </form>

    </div>
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
      feather.replace()
    </script>

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
