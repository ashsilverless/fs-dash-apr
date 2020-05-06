<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
require_once '../googleLib/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();

$staff_id = $_GET['id'];
$user_type = array("1"=>"Admin", "2"=>"Super Admin", "3"=>"User");

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fsadmin` where id = $staff_id;";

    $result = $conn->prepare($query);
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $user_prefix = $row['user_prefix'];
			  $staff_first_name = $row['first_name'];
			  $staff_last_name = $row['last_name'];
			  $staff_email = $row['email_address'];
			  $staff_mobile = $row['telephone'];
			  $agent_level = $row['agent_level'];

			  $staff_user_name = $row['user_name'];
			  $staff_password = $row['password'];
			  $staff_destruct_date = $row['destruct_date'];

			  $confirmed_by = $row['confirmed_by'];
			  $confirmed_date = $row['confirmed_date'];

			  $googlecode = $row['verification_code'];

		  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

if($_SESSION['agent_level']< '2'){
	$staff_password = '**********';
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
        <h1 class="heading heading__2">Staff Details</h1>
		<form action="editstaff.php?id=<?=$staff_id;?>" method="post" id="editstaff" name="editstaff" class="asset-form">
            <div class="content client">
                <div class="client__pers-details" style="border:none;">
                    <div class="item prefix mb1">
                        <label>Prefix</label>
                        <div class="select-wrapper">
                            <select name="user_prefix" id="user_prefix" class="select-css">
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Miss">Miss</option>
                                <option value="Dr">Dr</option>
                            </select>
                        </div>
                    </div>
                    <div class="item first-name">
                        <label>First Name</label>
                        <input type="text" id="staff_first_name" name="staff_first_name" value="<?= $staff_first_name;?>">
                    </div>
                    <div class="item second-name">
                        <label>Last Name</label>
                        <input type="text" id="staff_last_name" name="staff_last_name" value="<?= $staff_last_name;?>">
                    </div>
                    <div class="item user-name mb1">
                        <label>User Name</label>
                        <input type="text" id="staff_user_name" name="staff_user_name" value="<?= $staff_user_name;?>">
                    </div>
                    <div class="item email mb1">
                        <label>Client Email</label>
                        <input type="text" id="staff_email" name="staff_email" value="<?= $staff_email;?>">
                    </div>
                    <div class="item password">
                        <label>Password</label>
                        <input type="text" id="staff_password" name="staff_password" value="<?= $staff_password;?>" <?php if($_SESSION['agent_level']< '2' && $agent_level == '2'){ ?>readonly<?php }?>>
                    </div>

					<div class="item qrcode">
                        <label>QR Code</label>
                        <img src='<?php echo $qrCodeUrl; ?>'/>
                    </div>

                    <!--<div class="item user-id">
                        <label>User ID</label>
                        <input type="text" id="fs_client_code" name="fs_client_code" value="<?=$fs_client_code;?>">
                    </div>-->
                    <div class="item type">
                        <label>Type</label>
                        <div class="select-wrapper">
                            <select name="agent_level" id="agent_level" class="select-css">
                              <option value="999" <?php if($agent_level=='999'){?>selected = 'selected' <?php }?>>Temporary Block</option>
                              <option value="1" <?php if($agent_level=='1'){?>selected = 'selected' <?php }?>>Admin</option>
							<?php if($_SESSION['agent_level']< '2'){ $disabled = 'disabled'; };?>
                              <option value="2" <?php if($agent_level=='2'){?>selected = 'selected' <?php }?> <?=$disabled;?>>Super Admin</option>
                            </select>
                            <i class="fas fa-sort-down"></i>
                        </div>
                    </div>
                    <div class="item destruct">
                        <label>Destruct Date</label>
                        <input name="staff_destruct_date<?php if($_SESSION['agent_level']< '2' && $agent_level == '2'){ ?>readonly<?php }?>" type="text" id="staff_destruct_date<?php if($_SESSION['agent_level']< '2' && $agent_level == '2'){ ?>readonly<?php }?>" title="staff_destruct_date" value="<?= $staff_destruct_date;?>" size="12" <?php if($_SESSION['agent_level']< '2' && $agent_level == '2'){ ?>readonly<?php }?>>
                    </div>
                    <div></div>
                </div><!--pers details-->
            </div><!--content-->
            <div class="control">
                <h3 class="heading heading__2">Account Actions</h3>
    				<?php $submitAction = '';
    				if($_SESSION['user_id'] != $staff_id && $_SESSION['agent_level']< '2'){ $submitAction = 'disabled'; };
    				if($_SESSION['agent_level']< '2' && $agent_level == '2'){ $submitAction = 'disabled'; };
    				?>
                    <input type="submit" class="button button__raised" value="Save Changes" <?=$submitAction;?>>
    				<?php if($_SESSION['agent_level']>1){ ?><input href="#" data-href="deletestaff.php?id=<?= $staff_id;?>" data-toggle="modal" data-target="#confirm-delete" class="button button__raised" value="Delete Staff Member"><?php }?>
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

	function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

	$('#staff_destruct_date').datepicker({  format: "yyyy-mm-dd" , todayHighlight: true });

    </script>
  </body>
</html>
