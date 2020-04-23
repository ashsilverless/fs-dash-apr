<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$_GET['nu'] == '1' ? $flag = '2' : $flag = '1';

//    Get the graph data
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT current_price,correct_at  FROM `tbl_fs_fund` where isin_code LIKE 'GB0009346486' AND bl_live = 1 ORDER BY correct_at ASC;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$GB0009346486 .= $row['current_price'].',';
		$labels1 .= "'".$row['correct_at']."',";
	}

	$query = "SELECT current_price,correct_at  FROM `tbl_fs_fund` where isin_code LIKE 'GB00B1LB2Z79' AND bl_live = 1 ORDER BY correct_at ASC;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$GB00B1LB2Z79 .= $row['current_price'].',';
		$labels2 .= "'".$row['correct_at']."',";
	}

	$query = "SELECT current_price,correct_at  FROM `tbl_fs_fund` where isin_code LIKE 'GB00BJQWRN41' AND bl_live = 1 ORDER BY correct_at ASC;";

    $result = $conn->prepare($query);
    $result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$GB00BJQWRN41 .= $row['current_price'].',';
		$labels3 .= "'".$row['correct_at']."',";
	}



  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}

$rspaging = '<div style="margin:auto; padding:15px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;"><strong>'.$num_rows.'</strong> results in <strong>'.$totalPageNumber.'</strong> pages.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page : ';

if($page<3){
	$start=1;
	$end=7;
}else{
	$start=$page-2;
	$end=$page+4;
}


if($end >= $totalPageNumber){
  $endnotifier = "";
  $end = $totalPageNumber;
}else{
  $endnotifier = "...";
}

$frst = '<a href="?page=0'.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>';
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=10">10</a>&nbsp;|&nbsp;<a href="?rpp=30">30</a>&nbsp;|&nbsp;<a href="?rpp=50">50</a>&nbsp;|&nbsp;<a href="?rpp=999"><strong>All</strong></a></span>';

$rspaging .= $endnotifier.$last.$ipp.'</div>';

?>
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/header.php');
require_once('page-sections/header-elements.php');
require_once('page-sections/sidebar-elements.php');
?>

<div class="col-md-9">
    <div class="border-box main-content daily-data">

        <div class="row">
            <div class="col-7">
                <h2 class="heading heading__2">Upload Transaction File</h2>
                <form>
                    <label>Upload Latest File</label>
                    <input type="text" id="theme_title" name="theme_title" value="File Name" class="mb1">
                    <a href="#" class="button button__raised button__inline mr1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22.87 22.25"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M0,19.39V13.8a2.14,2.14,0,0,1,.47-1.57L3.94,7.87C5,6.56,5.44,6.26,7,6.26H8.68V7.32H6.89a2,2,0,0,0-1.77.86l-3.55,4.5c-.29.37-.2.56.23.56H8.38A.59.59,0,0,1,9,13.9v0a2.45,2.45,0,1,0,4.89,0v0a.59.59,0,0,1,.61-.66h6.58c.43,0,.54-.17.23-.56L17.73,8.16A1.94,1.94,0,0,0,16,7.32H14.2V6.26h1.67c1.56,0,2,.3,3.06,1.59l3.47,4.38a2.12,2.12,0,0,1,.47,1.57v5.59A2.55,2.55,0,0,1,20,22.25H2.88A2.55,2.55,0,0,1,0,19.39ZM20,21a1.53,1.53,0,0,0,1.69-1.71v-5H15a3.5,3.5,0,0,1-3.59,3.21,3.5,3.5,0,0,1-3.6-3.21H1.21v5A1.52,1.52,0,0,0,2.9,21Zm-9.13-7.6V3.1l.05-1.42-.88.9L8.35,4.31a.61.61,0,0,1-.42.17A.52.52,0,0,1,7.39,4a.58.58,0,0,1,.18-.41L11,.2a.56.56,0,0,1,.44-.2.59.59,0,0,1,.43.2L15.3,3.55a.55.55,0,0,1,.19.41.53.53,0,0,1-.55.52.61.61,0,0,1-.42-.17L12.86,2.58,12,1.68,12,3.1V13.44a.6.6,0,0,1-1.2,0Z"/></g></g></svg>
                        Select File</a>
                    <a href="#" class="button button__raised button__inline">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18.13 20.38"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M16.67,10.53a.59.59,0,0,1,0,.83L9.32,18.71a5.29,5.29,0,0,1-7.68,0,5.29,5.29,0,0,1,0-7.69l9.79-9.8A3.76,3.76,0,0,1,17,1.14a3.75,3.75,0,0,1-.11,5.5l-9.6,9.6a2.21,2.21,0,1,1-3.12-3.12L11,6.28a.53.53,0,0,1,.79,0,.56.56,0,0,1,0,.79L4.93,13.86a1.13,1.13,0,1,0,1.59,1.6l9.56-9.58a2.74,2.74,0,0,0,.16-4,2.73,2.73,0,0,0-3.95.16L2.5,11.85a4.15,4.15,0,0,0,0,6.07,4.15,4.15,0,0,0,6.07,0l7.33-7.34A.57.57,0,0,1,16.67,10.53Z"/></g></g></svg>
                        Upload</a>
                </form>
                <!--<div id="transfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="transcontainer"><a id="picktrans" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div><input type="text" id="trans_file" name="trans_file" readonly>-->
            </div>
            <div class="col-5">
                <h2 class="heading heading__2">Frequent Tasks</h2>
                <a href="./funds.php" class="toggle button button__raised mb1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.59 19.59"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M0,9.79A9.84,9.84,0,0,1,9.79,0a9.85,9.85,0,0,1,9.8,9.79,9.85,9.85,0,0,1-9.8,9.8A9.85,9.85,0,0,1,0,9.79Zm18.33,0a8.53,8.53,0,1,0-8.54,8.54A8.52,8.52,0,0,0,18.33,9.79Zm-12,3.85a.48.48,0,0,1,.41-.5,1.91,1.91,0,0,0,1.45-2A4.11,4.11,0,0,0,8,10.1H6.84a.41.41,0,0,1-.44-.43.4.4,0,0,1,.44-.43h.92A5.09,5.09,0,0,1,7.57,8a2.71,2.71,0,0,1,3-2.82,4.47,4.47,0,0,1,1.61.27c.32.14.42.3.42.51a.39.39,0,0,1-.42.42,6.19,6.19,0,0,0-1.56-.28A1.84,1.84,0,0,0,8.57,7.9a4.76,4.76,0,0,0,.22,1.34h2.75a.41.41,0,0,1,.44.43.42.42,0,0,1-.44.43H9a4.32,4.32,0,0,1,.14,1.06,2.68,2.68,0,0,1-.89,2H12.6a.45.45,0,0,1,.48.47.45.45,0,0,1-.48.46H6.74A.44.44,0,0,1,6.28,13.64Z"/></g></g></svg>
                    Update Daily Prices</a>
                <a href="./clients.php" class="toggle button button__raised mb1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20.77 20.77"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M3.69,9.72a.66.66,0,0,1,0,1.32h-3a.66.66,0,1,1,0-1.32ZM5.2,14.65a.64.64,0,0,1,.92,0,.66.66,0,0,1,0,.93L4,17.71a.67.67,0,0,1-.93,0,.66.66,0,0,1,0-.93ZM3.07,4A.65.65,0,1,1,4,3.07L6.12,5.21a.64.64,0,0,1,0,.92.65.65,0,0,1-.92,0Zm6.2,6.61a.9.9,0,0,1,0-1.26.87.87,0,0,1,1.25,0l9.35,9.38a.91.91,0,0,1,0,1.26.88.88,0,0,1-1.26,0Zm3.92,2.26L10.27,9.93c-.16-.16-.32-.19-.47-.06a.31.31,0,0,0,0,.47l2.91,2.93ZM11,3.68a.66.66,0,1,1-1.31,0v-3A.66.66,0,0,1,11,.65Zm0,16.43a.66.66,0,1,1-1.31,0v-3a.66.66,0,1,1,1.31,0Zm5.74-17a.65.65,0,0,1,.93,0,.67.67,0,0,1,0,.93L15.57,6.13a.65.65,0,0,1-.93,0,.64.64,0,0,1,0-.92Zm.31,8a.66.66,0,1,1,0-1.32h3a.66.66,0,0,1,0,1.32Z"/></g></g></svg>
                    Edit Client</a>
                <a href="add_client.php" class="toggle button button__raised">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.59 21.18"><defs><style>.cls-1{fill:#1d1d1b;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M0,16.33a4.89,4.89,0,0,1,4.85-4.87,4.86,4.86,0,1,1,0,9.72A4.88,4.88,0,0,1,0,16.33ZM5.41,19V16.84h2a.52.52,0,1,0,0-1h-2V13.65a.52.52,0,0,0-.55-.53.52.52,0,0,0-.54.53V15.8h-2a.52.52,0,0,0,0,1h2V19a.52.52,0,0,0,.54.53A.52.52,0,0,0,5.41,19Zm16.18-2.6c0,1-.68,1.44-2.12,1.44H10.65a4.85,4.85,0,0,0,.19-1.15h9c.43,0,.59-.11.59-.42,0-1.85-2.62-4.9-7.21-4.9a8.39,8.39,0,0,0-4,.93,5.7,5.7,0,0,0-.87-.83,9.4,9.4,0,0,1,4.83-1.25C18.38,10.21,21.59,13.84,21.59,16.39ZM9.08,4.39A4.26,4.26,0,0,1,13.18,0a4.24,4.24,0,0,1,4.11,4.38,4.3,4.3,0,0,1-4.11,4.5A4.3,4.3,0,0,1,9.08,4.39Zm7,0a3.06,3.06,0,0,0-2.9-3.23,3.06,3.06,0,0,0-2.89,3.24,3.12,3.12,0,0,0,2.89,3.34A3.13,3.13,0,0,0,16.08,4.38Z"/></g></g></svg>
                    Create Client</a>
            </div>
        </div>

    </div>
</div>

		<!--   Upload TransFile  -->

			<!--<div id="result" class="col-md-12 mb-3" style="height:300px; max-height:300px; overflow-y: scroll;"><div id="data_info" class="col-md-12 text-center" style="height:300px; max-height:300px; overflow-y: scroll;"></div></div>-->

		<!-- / Upload Trans File -->

		<!--<div class="row">
              <div class="col-md-12">
                  <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart1" height="400"></canvas>
				  <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart2" height="400"></canvas>
				  <canvas class="my-4 w-100 chartjs-render-monitor" id="linechart3" height="400"></canvas>
              </div>
          </div>-->

		<!--<div id="assetdetails" class="col-md-12 mt-5"></div>-->
</div><!--row-->
</div><!--container-->

<?php require_once('page-sections/footer-elements.php');
require_once('modals/delete.php');
require_once('modals/logout.php');
require_once(__ROOT__.'/global-scripts.php');?>

    <script>

		/* #########################    Trans File Upload and display    ####################### */
		$("#result").load("showdata.php?f=<?=$flag;?>");

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

					if(myData.error=="NULL"){
						$( "#trans_file" ).val(myData.result);
				   		$("#result").load("showdata.php");
					}else{
						$("#result").html('<h3 style="text-align:center; color:red;"><strong>Error : '+myData.error+'</strong></h3>');
					}
				},

				Error: function(up, err) {
					console.log("\nError #" + err.code + ": " + err.message);
				}
			}
		});
		uploader.init();
	/* ########################    / Trans File Upload and display    ####################### */

		$(".toggler").click(function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-prod-name')).toggle();
          $('.head'+$(this).attr('data-prod-name')).toggleClass( "highlight normal" );
          $('.arrow'+$(this).attr('data-prod-name'), this).toggleClass("fa-caret-up fa-caret-down");
    	});

		Chart.defaults.global.legend.display = false;

/* ##########################################       LINE CHART     ################################################## */

		var ctxline = document.getElementById('linechart1');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:0,
					pointRadius:0,
					borderColor:['rgba(0, 0, 150, 0.75)'],
					borderWidth:2,
					label:'T. Bailey Growth Fund A Accumulation',
					data:[<?=mb_substr($GB0009346486, 0, -1);?>],
				}],
				labels: [<?=mb_substr($labels1,0, -1);?>]
			},

			options: { tooltips: {enabled: true}, legend: {display: true}}
		});

		var ctxline = document.getElementById('linechart2');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:0,
					pointRadius:0,
					borderColor:['rgba(0, 150, 150, 0.75)'],
					borderWidth:2,
					label:'T. Bailey Dynamic Fund A  Accumulation',
					data:[<?=mb_substr($GB00B1LB2Z79, 0, -1);?>],
				}],
				labels: [<?=mb_substr($labels2,0, -1);?>]
			},

			options: { tooltips: {enabled: true}, legend: {display: true}}
		});

		var ctxline = document.getElementById('linechart3');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [{
					fill:false,
					lineTension:0,
					pointRadius:0,
					borderColor:['rgba(150, 0, 150, 0.75)'],
					borderWidth:2,
					label:'T. Bailey Dynamic Fund F Accumulation',
					data:[<?=mb_substr($GB00BJQWRN41, 0, -1);?>],
				}],
				labels: [<?=mb_substr($labels3,0, -1);?>]
			},

			options: { tooltips: {enabled: true}, legend: {display: true}}
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
