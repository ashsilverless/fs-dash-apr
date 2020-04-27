<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$user_type = array("1"=>"Admin", "2"=>"Super Admin", "999"=>"<i style='color:red;font-weight:bold;font-size:0.9em;'>! Temporary Block !</i>");


//    Get the user details
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	 $query = "SELECT * FROM `tbl_fs_client_products` where bl_live = 1 GROUP BY fs_isin_code ASC ;";

  $result = $conn->prepare($query);
  $result->execute();

  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $data_array[$row['fs_isin_code']] = $row['fs_fund_sedol'];
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
        <h1 class="heading heading__2">Add New Client</h1>
		<form action="addclient.php" method="post" id="addclient" name="addclient" class="asset-form">
            <div class="content client">
                <div class="client__pers-details">
                    <div class="item half mb1">
                        <label>Client Name</label>
                        <input type="text" id="user_name" name="user_name" value="">
                    </div>
                    <div class="item half">
                        <label>Client Email</label>
                        <input type="text" id="client_email" name="client_email" value="">
                    </div>
                    <div class="item half push-right mb2">
                        <label>Password</label>
                        <input type="password" id="password" name="password" value="">
                        <input type="text" id="passwordview" name="passwordview" value="">
                        <a href="#" id="genpass" style="margin-left:15px; font-size:0.8em; font-style:italic;">Generate</a> <a href="#" id="viewpass" style="margin-left:15px; font-size:0.8em; font-style:italic;">View</a>
                    </div>
                    <div class="item mb1">
                        <label>User ID</label>
                            <input type="text" id="fs_client_code" name="fs_client_code" value="">
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
                    <a href="#" class="addasset button button__raised button__inline"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.82 16.22"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M7.25,15.57V8.78H.66a.67.67,0,0,1,0-1.33H7.25V.65a.66.66,0,0,1,1.32,0v6.8h6.6a.67.67,0,0,1,0,1.33H8.57v6.79a.66.66,0,0,1-1.32,0Z"/></g></g></svg> Add Account</a>
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
                    <a href="#" class="addasset button button__raised button__inline"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.82 16.22"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M7.25,15.57V8.78H.66a.67.67,0,0,1,0-1.33H7.25V.65a.66.66,0,0,1,1.32,0v6.8h6.6a.67.67,0,0,1,0,1.33H8.57v6.79a.66.66,0,0,1-1.32,0Z"/></g></g></svg> Add Linked Account</a>
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

<!--
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 mb-5">

        <h1 class="h2">Details</h1>

		<form action="addclient.php" method="post" id="addclient" name="addclient" class="mt-5">
		<div class="col-md-9" style="float:left;">

			<div class="col-md-2" style="float:left;">
				<p>Prefix<br>
					<select name="user_prefix" id="user_prefix">
					  <option value="Mr">Mr</option>
					  <option value="Mrs">Mrs</option>
					  <option value="Miss">Miss</option>
					  <option value="Dr">Dr</option>
					</select>
					</p>
			</div>

			<div class="col-md-3" style="float:left;">
				<p>First Name<br>
					<input type="text" id="first_name" name="first_name" style="width:90%" value=""></p>
			</div>

			<div class="col-md-4" style="float:left;">
				<p>Surname<br>
					<input type="text" id="last_name" name="last_name" style="width:90%" value=""></p>
			</div>

			<div class="col-md-3" style="float:left;">
				<p>Expires<br>
					<input name="destruct_date" type="text" id="destruct_date" title="destruct_date" value="" size="6" style="width:90%" ></p>
			</div>



			<div class="col-md-4" style="float:left;">
				<p>Client User Name<br>
					<input type="text" id="user_name" name="user_name" style="width:90%" value=""></p>
			</div>

			<div class="col-md-4" style="float:left;">
				<p>Client Email<br>
					<input type="text" id="client_email" name="client_email" style="width:90%" value=""></p>
			</div>

			<div class="col-md-4" style="float:left;">
				<p>Password <a href="#" id="genpass" style="margin-left:15px; font-size:0.8em; font-style:italic;">Generate</a> <a href="#" id="viewpass" style="margin-left:15px; font-size:0.8em; font-style:italic;">View</a><br>
					<input type="password" id="password" name="password" style="width:90%" value=""><input type="text" id="passwordview" name="passwordview" style="width:90%" value=""></p>
			</div>



			<div class="col-md-2a" style="float:left;">
				<p>Client Code<br>
					<input type="text" id="fs_client_code" name="fs_client_code" style="width:90%" value=""></p>
			</div>

			<div class="col-md-2a" style="float:left;">
				<p>Strategy<br>
					<select name="strategy" id="strategy">
					  <option value="Sensible">Sensible</option>
					  <option value="Steady">Steady</option>
					  <option value="Serious">Serious</option>
					</select>
			   </p>
			</div>

			<div class="col-md-2a" style="float:left;">
				<p>Client Type<br>
					<select name="fs_client_desc" id="fs_client_desc">
					  <option value="Private Client">Private</option>
					  <option value="Corporate Client">Corporate</option>
					</select>
				</p>
			</div>

			<div class="col-md-2a" style="float:left;">
				<p>Designator<br>
					<input type="text" id="designator" name="designator" style="width:90%" value=""></p>
			</div>

			<div class="col-md-2a" style="float:left;">
				<p>Mobile Phone<br>
					<input type="text" id="telephone" name="telephone" style="width:90%" value=""></p>
			</div>




			<h4 class="mt-5">Add Account</h4>
<!--
  `fs_deal_ref` varchar(250) DEFAULT NULL,
  `fs_deal_type` varchar(250) DEFAULT NULL,
  													`fs_isin_code` varchar(250) DEFAULT NULL,
  													`fs_fund_sedol` varchar(100) DEFAULT NULL,
  													`fs_fund_name` varchar(250) DEFAULT NULL,
  `fs_currency_code` varchar(50) DEFAULT NULL,
  `fs_aui` varchar(150) DEFAULT 'Accumulation',
  `fs_client_desc` varchar(150) DEFAULT NULL,
  `fs_client_code` varchar(50) DEFAULT NULL,
  `fs_client_name` varchar(250) DEFAULT NULL,
  													`fs_designation` varchar(250) DEFAULT NULL,
  													`fs_product_type` varchar(100) DEFAULT NULL,
  `fs_shares` decimal(10,3) DEFAULT NULL,

  `fs_client_id` int(10) NOT NULL,
-->
		<!--	<div class="col-md-8" style="float:left;">
				<p>ISIN Code<br>
					<select name="fs_isin_code" id="fs_isin_code">
						<option value="" selected="selected">Existing ISIN Code</option>
						<option value="GB0009346486">GB0009346486</option>
						<option value="GB00B1LB2Z79">GB00B1LB2Z79</option>
						<option value="GB00BJQWRN41">GB00BJQWRN41</option>
					</select>




					Or New : <input type="text" id="new_fs_isin_code" name="new_fs_isin_code" style="width:50%" value=""></p>
			</div>

			<div class="col-md-4" style="float:left;">
				<p>Fund Sedol<br>
					<input type="text" id="fs_fund_sedol" name="fs_fund_sedol" style="width:90%" value=""></p>
			</div>

			<div class="col-md-4" style="float:left;">
				<p>Product Type<br>
					<select name="fs_product_type" id="fs_product_type">
						<option value="ISA" selected="selected">ISA</option>
						<option value="JISA">JISA</option>
						<option value="PIA">PIA</option>
						<option value="SIPP">SIPP</option>
						<option value="Unwrapped">Unwrapped</option>
					</select></p>
			</div>

			<div class="col-md-4" style="float:left;">
				<p>Fund Name<br>
					<input type="text" id="fs_fund_name" name="fs_fund_name" style="width:90%" value=""></p>
			</div>

			<div class="col-md-4" style="float:left;">
				<p>Designation<br>
					<input type="text" id="fs_designation" name="fs_designation" style="width:90%" value=""></p>
			</div>




			<!--
			<div class="col-md-12  table-responsive mt-5">
			  <table class="table table-sm table-striped">
			    <tbody>
					<tr>
				      <td width="15%" bgcolor="#FFFFFF"><strong>Client Code</strong></td>
					  <td width="20%" bgcolor="#FFFFFF"><strong>ISIN Code</strong></td>
					  <td width="20%" bgcolor="#FFFFFF"><strong>Designator</strong></td>
					  <td width="15%" bgcolor="#FFFFFF"><strong>Type</strong></td>
					  <td width="30%" bgcolor="#FFFFFF"><strong>Display Name</strong></td>
				  </tr>

			<?php foreach($products as $product) { ?>
				<tr>
					<td><input type="text" id="fs_client_code<?=$product['id'];?>" name="fs_client_code<?=$product['id'];?>" style="width:90%" value="<?=$product['fs_client_code'];?>" readonly></td>
					<td><input type="text" id="fs_isin_code<?=$product['id'];?>" name="fs_isin_code<?=$product['id'];?>" style="width:90%" value="<?=$product['fs_isin_code'];?>" readonly></td>
					<td><input type="text" id="designator<?=$product['id'];?>" name="designator<?=$product['id'];?>" style="width:90%" value="<?=$product['fs_designation'];?>" readonly></td>
					<td><select name="product_type<?=$product['id'];?>" id="product_type<?=$product['id'];?>">
							<option value="ISA" <?php if(strtolower ($product['fs_product_type'])=='isa'){?>selected = 'selected' <?php }?>>ISA</option>
							<option value="JISA" <?php if(strtolower ($product['fs_product_type'])=='jisa'){?>selected = 'selected' <?php }?>>JISA</option>
							<option value="PIA" <?php if(strtolower ($product['fs_product_type'])=='pia'){?>selected = 'selected' <?php }?>>PIA</option>
							<option value="SIPP" <?php if(strtolower ($product['fs_product_type'])=='sipp'){?>selected = 'selected' <?php }?>>SIPP</option>
							<option value="Unwrapped" <?php if(strtolower ($product['fs_product_type'])=='unwrapped'){?>selected = 'selected' <?php }?>>Unwrapped</option>
						</select></td>
					<td><input type="text" id="display_name<?=$product['id'];?>" name="display_name<?=$product['id'];?>" style="width:90%" value="<?=$product['fs_client_name'] . ' ' . $product['fs_product_type'];?>" readonly></td>
				</tr>
			  <?php } ?>
			   </tbody>
			  </table>
			</div>
			-->


<!--
			<h4 class="mt-5">Linked Accounts</h4>


			<?php if($linked_accounts!=''){ $lnk_array = explode('|',$linked_accounts);?>

				<?php for($b=0;$b<count($lnk_array);$b++){
                     if($lnk_array[$b]!=''){  ?>
					<p><strong>Linked Account Holder :</strong> <?=getUserName($lnk_array[$b])?></p>
					<table class="table table-sm table-striped">
						<tbody>
							<tr>
							  <td width="15%" bgcolor="#FFFFFF"><strong>Client Code</strong></td>
							  <td width="20%" bgcolor="#FFFFFF"><strong>ISIN Code</strong></td>
							  <td width="20%" bgcolor="#FFFFFF"><strong>Designator</strong></td>
							  <td width="15%" bgcolor="#FFFFFF"><strong>Type</strong></td>
							  <td width="30%" bgcolor="#FFFFFF"><strong>Display Name</strong></td>
							</tr>

						  <?php

						  // Connect and create the PDO object
						  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
						  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

						  $query = "SELECT * FROM `tbl_fs_client_products` where fs_client_code LIKE '$lnk_array[$b]' AND bl_live = 1;";

						  $result = $conn->prepare($query);
						  $result->execute();

						  // Parse returned data
						  while($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
							<tr>
								<td><input type="text" id="fs_client_code" name="fs_client_code" style="width:90%" value="<?=$lnk_array[$b];?>" readonly></td>
								<td><input type="text" id="fs_isin_code" name="fs_isin_code" style="width:90%" value="<?=$row['fs_isin_code'];?>" readonly></td>
								<td><input type="text" id="fs_designation" name="fs_designation" style="width:90%" value="<?=$row['fs_designation'];?>" readonly>
								<td><input type="text" id="fs_client_code" name="fs_client_code" style="width:90%" value="<?=$row['fs_product_type'];?>" readonly></td>
								<td><input type="text" id="fs_client_code" name="fs_client_code" style="width:90%" value="<?=getUserName($lnk_array[$b]) . ' ' . $row['fs_product_type'];?>" readonly></td>
							</tr>
						  <?php }

						  $conn = null;        // Disconnect

						}?>
						</tbody>
			  </table>
                  <?php  }?>

            <?php }	?>

		</div>

        <div class="col-md-3" style="float:left;">
            <h5>Client Actions</h5>
            <input type="submit" class="btn btn-grey" value="Save Changes">
        </div>

</form>




		<div id="assetdetails" class="col-md-12 mt-5"></div>

        </main>
      </div>
    </div>
-->

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

	function randomPassword(length) {  // Super quick and dirty password generator
		var chars = "abcdefghijklmnopqrstuvwxyz@#$%-+<>-_!*ABCDEFGHIJKLMNOP1234567890";
		var pass = "";
		for (var x = 0; x < length; x++) {
			var i = Math.floor(Math.random() * chars.length);
			pass += chars.charAt(i);
		}
		return pass;
	}

	function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

	$( document ).ready(function() {

        $("#passwordview").hide();

		$('#destruct_date').datepicker({  format: "yyyy-mm-dd" , todayHighlight: true });

		 $("#passwordview").keyup(function( event ) {
		  	newPass =  $("#passwordview").val();
			$("#password").val(newPass);

			}).keydown(function( event ) {
			  if ( event.which == 13 ) {
				event.preventDefault();
			  }
		});

		$("#password").keyup(function( event ) {
		  	newPass =  $("#password").val();
			$("#passwordview").val(newPass);

			}).keydown(function( event ) {
			  if ( event.which == 13 ) {
				event.preventDefault();
			  }
		});

		$('#genpass').click(function (e){
		  e.preventDefault();
			newPass = randomPassword(10);
			$("#password").val(newPass);
			$("#passwordview").val(newPass);
		});

		$('#viewpass').click(function (e){
		  e.preventDefault();
			$("#password").toggle();
			$("#passwordview").toggle();
		});

    });

    </script>
  </body>
</html>
