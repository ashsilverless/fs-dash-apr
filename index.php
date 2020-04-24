<?php include 'header.php';
include('connection.php');

$csrf		=	$connect->real_escape_string($_POST["csrf"]);
if ($csrf == $_SESSION["token"]) {
	$username	= $connect->real_escape_string($_POST['username']);
	$password	= $connect->real_escape_string($_POST['password']);

	/* Check Username and Password */
	$query		= db_query("select * from tbl_fsusers where email_address='".$username."' and password='".$password."' ");
	$resuser = mysqli_num_rows($query);
	if($resuser = 1){
		$row = mysqli_fetch_array($query);
		session_regenerate_id();
		$_SESSION['email'] 	= $row['email_address'];
		$_SESSION['pass'] 	= $row['password'];
		$_SESSION['secret'] = $row['googlecode'];

		$_SESSION['name'] = $row['first_name'];
		$_SESSION['username'] = $row['user_name'];
		$_SESSION['phone'] = $row['telephone'];
		$_SESSION['user_id'] = $row['id'];
		$_SESSION['company_id'] = $row['company_id'];
		$_SESSION['agent_level'] = $row['agent_level'];
		$_SESSION['id'] = session_id();
		$_SESSION['featherstone_cc'] = $row['fs_client_code'];
		$_SESSION['featherstone_uid'] = $row['id'];

		$row['last_logged_in'] != '' ?	$_SESSION['newregister'] = 0 : $_SESSION['newregister'] = 1;

		header('Location:device_confirmations.php');
		exit();
	}else{
		$strmsg="Invalid Username or Password";
		exit();
	}

}
?>


  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Countries Row -->
          <div class="row">
            <div class="clearfix"></div>
            <div class="col-6 offset-3 login">
						<div class="text-center border-box login__inner">
								<h1 id="loginlogo" class="logo">
                                    <?php include 'client/images/fs-logo.php'; ?>
                                </h1>
                                <form name="reg" action="index.php" method="POST">
    <?=$strmsg;?>
    <input type="hidden" name="csrf" 	 value="<?php print $_SESSION["token"]; ?>" >
    <input type="hidden" name="passcode" value="<?php echo $passcode; ?>" >
    <div class="form-group">
        <label>Email Address</label>
        <input type="text" name="username" id="username" autocomplete="off" value="" required>

    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" id="password" autocomplete="off" value="" required>

    </div>
    <div class="silverless-button">
        <input  id="go" type="submit" value="Log in">
    </div>

     <div class="form-text text-right">
         <p>Forgot password? Click <a href="">here</a></p>
    </div>

</form>

						</div>
					</div>
          </div>


        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Silverless 2019</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <?php define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/footer.php');?>
