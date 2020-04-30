<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
require_once '../googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();

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
		$user_prefix = $row['user_prefix'];
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
		$destruct_date = $row['destruct_date'];
		$email_address = $row['email_address'];
		$telephone = $row['telephone'];
		$strategy = $row['strategy'];
		$linked_accounts = $row['linked_accounts'];
		$desc = $row['fs_client_desc'];
		$googlecode = $row['googlecode'];
	}


  $query = "SELECT * FROM `tbl_fs_client_products` where CAST(fs_client_code AS UNSIGNED) = '$fs_client_code' AND bl_live = 1;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $products[] = $row;
  }




 //    List of Client Products
  $query = "SELECT * FROM `tbl_fs_client_products` where bl_live = 1 GROUP BY fs_client_code ASC ;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $links[] = $row;
  }



 //Get the ISIN Code list
	 $query = "SELECT * FROM `tbl_fs_client_products` where bl_live = 1 GROUP BY fs_isin_code ASC ;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $fs_isin_code[] = $row['fs_isin_code'];
  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

$qrCodeUrl 	= $ga->getQRCodeGoogleUrl($email_address, $googlecode,'www.featherstone.co.uk');

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
                    <div class="item prefix mb1">
                        <label>Prefix</label>
                        <div class="select-wrapper">
                            <select name="user_prefix" id="user_prefix" class="select-css">
                                <option value="Mr" <?php if($user_prefix=='Mr'){?>  selected="selected"<?php }?>>Mr</option>
          					  <option value="Mrs" <?php if($user_prefix=='Mrs'){?>  selected="selected"<?php }?>>Mrs</option>
          					  <option value="Miss" <?php if($user_prefix=='Miss'){?>  selected="selected"<?php }?>>Miss</option>
          					  <option value="Dr" <?php if($user_prefix=='Dr'){?>  selected="selected"<?php }?>>Dr</option>
                            </select>
                    </div><!--sel-->
                    </div>
                    <div class="item first-name">
                        <label>First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?= $first_name;?>">
                    </div>
                    <div class="item second-name">
                        <label>Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?= $last_name;?>">
                    </div>
                    <div class="item user-name mb1">
                        <label>User Name</label>
                        <input type="text" id="user_name" name="user_name" value="<?= $user_name;?>">
                    </div>
                    <div class="item email mb1">
                        <label>Client Email</label>
                        <input type="text" id="client_email" name="client_email" value="<?=$email_address;?>">
                    </div>
                    <div class="item user-id">
                        <label>User ID</label>
                        <div class="">
                            <?=$fs_client_code;?>
							<input name="fs_client_code" type="hidden" id="fs_client_code" value="<?=$fs_client_code;?>">
                        </div>
                    </div>
                    <div class="item mb1">
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
                    <div class="item">
                        <label>Expires</label>
                        <input name="destruct_date" type="text" id="destruct_date" title="destruct_date" value="<?=$destruct_date;?>">
                    </div>
                    <div class="item">
                        <label>QR Code</label>
                        <img src='<?php echo $qrCodeUrl; ?>'/>
                    </div>
                </div><!--pers details-->

                <div class="client__pers-accounts">
                    <!--call accounts-->
                    <h3 class="heading heading__2">Accounts</h3>
                    <div class="recess-box">
                        <div class="account-table">
                            <div class="account-table__head">
                                <label>Client Code</label>
                                <label>ISIN Code</label>
                                <label>Designator</label>
                                <label>Type</label>
                                <label>Display Name</label>
                                <label>Delete</label>
                            </div><!--head-->
                            <?php $pid = ''; foreach($products as $product) { ?>
                            <div class="account-table__body">
                                <p><?=(int)$product['fs_client_code'];?></p>
                                <p><?=$product['fs_isin_code'];?></p>
                                <p><?=$product['fs_designation'];?></p>
                                <p><?=$product['fs_product_type'];?></p>
                                <p><?=$product['fs_client_name'] . ' ' . $product['fs_product_type'];?></p>
                                <input name="del<?=$product['id'];?>" type="checkbox" id="del<?=$product['id'];?>" value="1">
                            </div><!--body-->
    			            <?php $pid .= $product['id'].'|'; } ?>
    					    <input name="product_ids" type="hidden" id="product_ids" value="<?=$pid;?>">
                        </div><!--account table-->
                    </div>

                    <!--add accounts-->
                    <h3 class="heading heading__4">Add Accounts</h3>
                    <div class="add-account">
                        <div class="add-account__existing">
                            <h3 class="heading heading__5">Add Existing Account</h3>
                            <label>ISIN Code</label>
                            <select name="fs_isin_code" id="fs_isin_code">
                                <option value="" selected="selected">Existing ISIN Code</option>
                                <?php foreach($fs_isin_code as $code) { ?>
                                    <option value="<?=$code;?>"><?=$code;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="add-account__new">
                            <h3 class="heading heading__5">Add New Account</h3>

                            <label>ISIN Code</label>
                            <input type="text" id="new_fs_isin_code" name="new_fs_isin_code" value="">
                            <label>Fund SEDOL</label>
                            <input type="text" id="fs_fund_sedol" name="fs_fund_sedol" value="">
                            <label>Product Type</label>
                            <select name="fs_product_type" id="fs_product_type">
                                	<option value="ISA" selected="selected">ISA</option>
                                	<option value="JISA">JISA</option>
                                	<option value="PIA">PIA</option>
                                	<option value="SIPP">SIPP</option>
                                	<option value="Unwrapped">Unwrapped</option>
                            </select>

                            <label>Fund Name</label>
                        	<input type="text" id="fs_fund_name" name="fs_fund_name" value="">

                            <label>Designation</label>
                        	<input type="text" id="fs_designation" name="fs_designation" value="">

                        </div>
                    </div><!--add account-->
                </div><!--client pers accounts-->

                <div class="client__linked-accounts">
                    <h3 class="heading heading__2">Linked Accounts</h3>

                    <?php if($linked_accounts!=''){ $lnk_array = explode('|',$linked_accounts); $lnkList = '';?>

                    	<?php for($b=0;$b<count($lnk_array);$b++){
                             if($lnk_array[$b]!=''){  $lnkList .= $lnk_array[$b].'|';?>
                            <div class="client-account-wrapper">

                                <div class="head">
                                    <h3 class="heading heading__4">Linked Account Holder: <?=getUserName((int)$lnk_array[$b])?></h3>
                            		    <label>Remove Account</label>
                                    <input name="dellink<?=(int)$lnk_array[$b];?>" type="checkbox" id="dellink<?=(int)$lnk_array[$b];?>" value="1">
                                </div><!--head-->

                                <div class="recess-box">
                                    <div class="account-table">
                                        <div class="account-table__head">
                                            <label>Client Code</label>
                                            <label>ISIN Code</label>
                                            <label>Designator</label>
                                            <label>Type</label>
                                            <label>Display Name</label>
                                        </div><!--account-table__head-->

                                        <?php // Connect and create the PDO object
                                        $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
                                        $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
                                        $query = "SELECT * FROM `tbl_fs_client_products` where fs_client_code LIKE '$lnk_array[$b]' AND bl_live = 1;";
                                        $result = $conn->prepare($query);
                                        $result->execute();
                                        // Parse returned data
                                        while($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>

                                        <div class="account-table__body">
                                            <p><?=(int)$lnk_array[$b];?></p>
                                            <p><?=$row['fs_isin_code'];?></p>
                                            <p><?=$row['fs_designation'];?></p>
                                            <p><?=$row['fs_product_type'];?></p>
                                            <p><?=getUserName($lnk_array[$b]) . ' ' . $row['fs_product_type'];?></p>
                                        </div><!--account-table__body-->

                                        <?php } $conn = null;        // Disconnect
                                        ?>
                                    </div><!--account-table-->
                                </div><!--recess-box-->

                            </div><!--client-account-wrapper-->
                        <?php }  }?>
                    <?php } ?>

                    <!--<input name="linked_accounts" type="hidden" id="linked_accounts" value="<?=$lnkList;?>">-->
                    <h4 class="heading heading__4">Add Linked Account</h4>
                    <div class="link-account">
                        <label>Account</label>
                        <select name="linked_account" id="linked_account">
                            <option value="" selected="selected">Select Account to Link</option>
                            <?php foreach($links as $link) { ?>
                                <option value="<?=$link['fs_client_code']?>"><?=$link['fs_client_name']?></option>
                              <?php } ?>
                        </select>
                    </div>
                </div><!--client linked accounts-->
				
				
				
				
				
				
            </div><!--content-->

            <div class="control">
                <h3 class="heading heading__2">Account Actions</h3>
                <input type="submit" class="button button__raised" value="Save Changes">
            </div><!--control-->

        </form>

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
