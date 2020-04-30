<?php include '../header.php';

include('../connection.php');

$secret = $_SESSION['secret'];
$user 	= $_SESSION['email'];

require_once '../googleLib/GoogleAuthenticator.php';
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
                                    <?php include '../client/images/fs-logo.php'; ?>
                                </h1>
							
							
							
							<div class="form-input">
                                <h2>Enter Code</h2>
                                <form name="reg" action="auth.php" method="POST">
									<?php if ($_SESSION['newregister']==1){?>
                                    <div class="form-group">
										<img src='<?php echo $qrCodeUrl; ?>'/>
                                    </div>
									<?php }?>
                                    <div class="form-group">
										<input type="text" name="code" id="code" autocomplete="off" value="" required>
                                        <label>Enter Google Authenticator Code</label>
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
