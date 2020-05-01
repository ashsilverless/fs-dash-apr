<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$msg = $_GET['msg'];
$user_id = $_SESSION['fs_client_featherstone_uid'];
$client_code = $_SESSION['fs_client_featherstone_cc'];
$last_date = getLastDate('tbl_fs_transactions','fs_transaction_date','fs_transaction_date','fs_client_code = "'.$client_code.'"');

$lastlogin = date('g:ia \o\n D jS M y',strtotime(getLastDate('tbl_fsusers','last_logged_in','last_logged_in','id = "'.$_SESSION['fs_client_user_id'].'"')));

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8


	$query = "SELECT * FROM tbl_fsusers where fs_client_code LIKE '$client_code' AND bl_live = 1;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {

		$clientData[] = $row;
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
require_once(__ROOT__.'/page-sections/header-elements.php');
require_once(__ROOT__.'/page-sections/sidebar-elements.php');
?>

    <div class="col-md-9">
        <div class="border-box main-content">
            <div class="main-content__head">
                <h1 class="heading heading__1">Account Settings</h1>
            </div>

		    <form action="editclient.php" method="post" id="editclient" name="editclient" class="settings">

                <div class="fixed-details">
                    <h2 class="heading heading__2">Details</h2>
                    <label for="user_name" id="userlabel" >User Name</label>
                    <input type="text" id="user_name" name="user_name" value="<?=$clientData[0]['user_name'];?>" class="mb1">
                    <label for="email_address" id="emaillabel" >Email</label>
                    <input type="text" id="email_address" name="email_address" value="<?=$clientData[0]['email_address'];?>">
                </div>

                <div class="variable-details">
                    <h2 class="heading heading__2">Change Password</h2>
                    <label for="password" id="currentpasswordlabel" >Current Password</label>
                    <input type="password" id="password" name="password" value="" class="mb1">

                    <label for="newpassword" id="newpasswordlabel" >New Password</label>
                    <input type="password" id="newpassword" name="newpassword" value="" class="mb1">

                    <label for="confirmpassword" id="confirmpasswordlabel" >Confirm Password</label>
                    <input type="password" id="confirmpassword" name="confirmpassword" value="" class="mb1">
                </div>

                <div class="confirm-message">
                    <span id="message"></span>
                </div>
                <!-- ##########################		     Client Settings    ####################### -->
                <input name="client_code" type="hidden" id="client_code" value="<?=$client_code?>">

                <input id="submit" type="submit" name="submit" value="Save Changes" />

            </form>

        </div><!--border box-->

		<?php if($msg=='updated'){?>
		  <fieldset class="whtbrdr">
			<div id='updated'>
			<h3>Account Settings Successfully Updated.</h3>
			</div>
		</fieldset>
		<?php } ?>

    </div>
  </div>
</div>


<!-- Footer -->
<div class="auto-LogOut"></div>
<footer class="col-md-12 mt-5">
<div class="container my-auto">
  <div class="copyright text-center my-auto">
    <span>Copyright &copy; Featherstone 2020 <?=$user_id;?></span>
  </div>
</div>
</footer>
<!-- End of Footer -->

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
          <a class="btn btn-primary" href="../index.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

<!--    Logged Out  -->
    <div class="modal fade" id="loggedout" tabindex="-1" role="dialog" aria-labelledby="LoggedOut" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Your Session has Timed Out</h5>
        </div>
        <div class="modal-body">Select "Login" below if you want to continue your session.</div>
        <div class="modal-footer">
		  <a class="btn btn-primary" href="../index.php">Login</a>
          <a class="btn btn-secondary quit" href="">Quit</a>
        </div>
      </div>
    </div>
  </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js"></script>

    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
	<!-- Custom Scripts -->
    <script src="js/custom.js"></script>
    <script>
      feather.replace()
    </script>

    <script type="text/javascript">

		$('#newpassword, #confirmpassword').on('keyup', function() {
		  if ($('#newpassword').val() == $('#confirmpassword').val()) {
			$('#message').html('Matching').css('color', 'green');
			$('#submit').prop('disabled', false);
		  } else {
			$('#message').html('Not Matching').css('color', 'red');
			$('#submit').prop('disabled', true);
		  }
		});

    </script>
  </body>
</html>
