<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$initialDate = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
?>


        
<form action="addfund.php" method="post" id="addfund" name="addfund">
			<div id="fund_details" class="col-md-6" style="float:left;">
				
					<h4>Details</h4>
					<p>Fund Name<br>
					<input type="text" id="fund_name" name="fund_name"></p>
					<p>ISIN Code<br>
					<input type="text" id="isin_code" name="isin_code"></p>
					<p>Fund Sedol<br>
					<input type="text" id="fund_sedol" name="fund_sedol"></p>
					<p>Benchmark<br>
					<input type="text" id="benchmark" name="benchmark"></p>
			  <p>Currency<br>
					<select name="currency" id="currency">
					  <option value="GBP" selected="selected">GBP</option>
					  <option value="USD">USD</option>
					  <option value="EUR">EUR</option>
					</select>
				
				
				
				
				
			</div>
			<div id="fund_upload" class="col-md-3" style="float:left;"><h4>Upload Data</h4>		
				<div id="fundfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="fundcontainer"><a id="pickfund" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div><div id="fund_info"></div>
			</div>
			<div id="fund_actions" class="col-md-3" style="float:left;">
				<h5>Fund Actions</h5>
				<p>Fund created on 12 Jan 20 by Andrew Cox</p>
				<p>Last edited on 14 Jan by James Barton</p>
				<p><a href="historic_fund_template.xlsx">Download</a> Historic Fund Template</p>
				<input type="submit" class="btn btn-grey" value="Add Fund">
			</div>
	
</form>			
		<div class="col-md-8 offset-2 mt-3 mb-3"><hr></div>
            
		<div id="funddetails" class="col-md-12 mt-5"></div>
            

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
		
 // Fund File Upload
var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfund',
	container: document.getElementById('fundcontainer'),
	url : 'fundupload.php',
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
			document.getElementById('fundfilelist').innerHTML = '';
		},

		FilesAdded: function(up, files) {
			for (var i in files) {
				$( "#fund_file" ).val(files[i].name); 
			}
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
 
		   $( "#fund_info" ).html(myData.result+'<br>'+myData.datacount+' records <em>pending</em>.'); 
		   $("#result").load("showfunddata.php"); 

        },


		Error: function(up, err) {
			console.log("\nError #" + err.code + ": " + err.message);
		}
	}
});
		
$('#uploadfiles').onclick = function() {
	uploader.start();
	return false;
};
		
uploader.init();
    
		
	 

    </script>
  </body>
</html>
