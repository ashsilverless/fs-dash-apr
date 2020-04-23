<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


/*     
ini_set ("display_errors", "1");
error_reporting(E_ALL);
    */


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
				<a class="btn btn-admin shadow-sm active" href="home.php">Dashboard</a>
				<a class="btn btn-admin shadow-sm" href="funds.php">Funds</a>
				<a class="btn btn-admin shadow-sm" href="assets.php">Asset Allocation &amp; Holdings</a>
				<a class="btn btn-admin shadow-sm" href="themes.php">Themes</a>
				<a class="btn btn-admin shadow-sm " href="peers.php">Peers</a>
				<a class="btn btn-admin shadow-sm" href="clients.php">Clients</a>
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

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">

        <h1 class="h2">Upload Files</h1>
			<p><strong>Transaction File</strong></p>
			
			<div class="col-md-4 mb-3">
				<div id="transfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="transcontainer"><a id="picktrans" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div><input type="text" id="trans_file" name="trans_file" readonly>
			</div>

			<div id="result" class="col-md-12 mb-3"><div id="data_info" class="col-md-12 text-center"></div></div>
			
		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>
            
            
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
    <script>
      feather.replace()
    </script>

   
      
    <script>
      // Transaction File Upload
	var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'picktrans',
	container: document.getElementById('transcontainer'),
	url : 'upload.php',
	flash_swf_url : 'js/plupload/Moxie.swf',
	silverlight_xap_url : '.js/plupload/Moxie.xap',
	unique_names : true,
	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Data files", extensions : "txt,csv"}
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('transfilelist').innerHTML = '';
		},

		FilesAdded: function(up, files) {
            uploader.start();
		},

		UploadProgress: function(up, file) {
			$('#data_info').html('<strong>Uploading & Parsing Datafile</strong><br>Please wait.....<br><br><img src="images/animated_progress.gif">');
		},
        
        FileUploaded: function(up, file, info) {
            var myData;
				try {
					myData = eval(info.response);
				} catch(err) {
					myData = eval('(' + info.response + ')');
				}
           $('#data_info').html(''); 
           $( "#trans_file" ).val(myData.result);  
		   $("#result").load("showdata.php"); 

        },


		Error: function(up, err) {
			console.log("\nError #" + err.code + ": " + err.message);
		}
	}
});
		
		uploader.init();

    </script>
  </body>
</html>
