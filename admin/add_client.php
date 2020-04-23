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
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
	
	<!-- Upload script -->
	<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

  </head>

  <body>

	<nav class="navbar navbar-dark sticky-top bg-white flex-md-nowrap p-0">
		<div id="logo" class="col-md-2"><img src="images/fs_logo1.jpg" alt="" height="110" align="left"/></div>
		<div id="righthandside" class="col-md-10">
			<div id="title" style="cleath:both;"><h2><strong>Client Portal Admin Area</strong></h2></div>
			<div id="menuitems" class="mt-4">
				<a class="btn btn-admin shadow-sm " href="home.php">Dashboard</a>
				<a class="btn btn-admin shadow-sm " href="funds.php">Funds</a>
				<a class="btn btn-admin shadow-sm " href="assets.php">Asset Allocation &amp; Holdings</a>
				<a class="btn btn-admin shadow-sm " href="themes.php">Themes</a>
				<a class="btn btn-admin shadow-sm " href="peers.php">Peers</a>
				<a class="btn btn-admin shadow-sm active" href="clients.php">Clients</a>
				<a class="btn btn-admin shadow-sm" href="staff.php">Staff</a>
				
				<span style="float:right;"><a class="btn btn-grey shadow-sm" href="#" data-toggle="modal" data-target="#logoutModal">Log Out</a></span>
			</div>
		</div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
          <div class="sidebar-sticky mt-115 ml-3">
			  <h4>Hello <?=$_SESSION['username'];?></h4>
			  <p>Last Login:<br>1:24pm on Tue 12 Dec 19.</p>
			  <p><a href="#">Not You? Click here</a></p>
			  <a class="btn btn-admin shadow-sm" href="#">Account Settings</a>
			  <a class="btn btn-admin shadow-sm" href="#">Help &amp; Support</a>
          </div>
        </nav>

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
			
			<!-- #################################### -->
			
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
			
			<!-- #################################### -->
			
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
			
			<!-- #################################### -->
			

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
			<div class="col-md-8" style="float:left;">
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

	  
<!-- Delete Modal-->
  <div class="modal deletefund" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModal">Delete this Asset?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Continue" below if you are ready to<br>delete this Asset.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-danger btn-ok">Delete</a>
        </div>
      </div>
    </div>
  </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js"></script>
      
     <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <!-- Date Picker -->	  
	<link rel="stylesheet" href="css/bootstrap-datepicker3.css">
	<script src="js/bootstrap-datepicker.min.js"></script>
	  
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
