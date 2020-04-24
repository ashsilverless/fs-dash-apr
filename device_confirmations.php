<?php include 'header.php';

include('connection.php');

$secret = $_SESSION['secret'];
$user 	= $_SESSION['email'];

require_once 'googleLib/GoogleAuthenticator.php';
$ga 		= new GoogleAuthenticator();
$qrCodeUrl 	= $ga->getQRCodeGoogleUrl($user, $secret,'www.featherstone.co.uk');


?>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Row -->
          <div class="row">
            <div class="clearfix"></div>
            <div class="col-6 offset-3 login">
						<div class="text-center border-box login__inner">
								<h1 id="loginlogo" class="logo">
                                    <?php include 'client/images/fs-logo.php'; ?>
                                </h1>
							
							<div class="form-input">
								<?php if ($_SESSION['newregister']==1){?>
                                <h2 class="heading heading__3">Create Secure Code</h2>
								<p>You will need a unique code to log into your Featherstone Partners dashboard.  It's simple, and you will only have to complete this set up process once.</p>
								<h2 class="heading heading__2">Step 1 - Get Secure App</h2>
								<p>You will need a secure token app to continue.  Please download one of the following apps:</p> 
								
								<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en_GB" target="_blank">Google Authenticator for Android</a>
								<br/>
								<a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank">Google Authenticator for iPhone</a>
								<h2 class="heading heading__2" style="margin:1rem 0 0;">Step 2 - Link App to Dashboard</h2>
								<p style="margin:0 0 1rem;">Once your have the app, scan this QR to link the app to your dashboard account.</p>
                                <form name="reg" action="auth.php" method="POST">
									
                                    <div class="form-group">
										<img src='<?php echo $qrCodeUrl; ?>'/>
                                    </div>
									
                                    <div class="form-group">
										<h2 class="heading heading__2" style="margin:1rem 0 0;">Step 3 - Enter Your Code</h2>
										<p style="margin:0 0 1rem;">The app will now display a unique six-figure code. Simply enter that code in the box below.</p>
										<?php } else {?>
									<form name="reg" action="auth.php" method="POST">	
										<div class="form-group">
										<label>Enter your Secure Code</label>
										<p><a>How do I get my code?</a></p>
										<?php }?>
										<input type="text" name="code" id="code" autocomplete="off" value="" required>
                                        
                                    </div>
                                    
                                    <div class="silverless-button">
                                        <input  id="go" type="submit" value="Log in">
                                    </div>
									
                                </form>
                            </div>


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
