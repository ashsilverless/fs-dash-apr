<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$theme_id = $_GET['id'];

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $query = "SELECT *  FROM `tbl_fs_themes` where id = $theme_id;";

    $result = $conn->prepare($query);
    $result->execute();

          // Parse returned data
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $theme_title = $row['fs_theme_title'];
			  $theme_narrative = $row['fs_theme_narrative'];
			  $row['fs_theme_icon'] != '' ? $theme_icon = '<img src="../icons_folder/'.$row['fs_theme_icon'].'" style="margin-right:10px; max-width:80px;">': $theme_icon = '';
			  $theme_icon_file = $row['fs_theme_icon'];
			  $steady = $row['fs_theme_steady'];
			  $serious = $row['fs_theme_serious'];
			  $sensible = $row['fs_theme_sensible'];
		  }

  $conn = null;        // Disconnect

}

catch(PDOException $e) {
  echo $e->getMessage();
}
?>

<form action="edittheme.php?id=<?=$theme_id;?>" method="post" id="edittheme" name="edittheme" class="asset-form">
    <div class="content">
        <h3 class="heading heading__2">Theme Details</h3>

<div class="details">
    <label>Theme Name</label>
    <input type="text" id="theme_title" name="theme_title" value="<?=$theme_title;?>" class="mb1">
    <label>Narrative</label>
    <textarea name="theme_narrative" id="theme_narrative" class="mb2"><?=$theme_narrative;?></textarea>
    <h4 class="heading heading__4">Theme Type</h4>
    <div class="row mt1">
        <div class="col-4">
            <label>Steady</label>
            <div class="radio-item">
                <input class="star-marker" name="theme_type_steady" type="checkbox" id="theme_type_steady" value="steady" <?php if($steady=='1'){?>checked="checked"<?php }?>>
                <?php define('__ROOT__', dirname(dirname(__FILE__)));
                include(__ROOT__.'/admin/images/star.php'); ?>
            </div><!--radio-->
        </div>
        <div class="col-4">
            <label>Serious</label>
            <div class="radio-item">
                <input class="star-marker" type="checkbox" name="theme_type_serious" value="serious" id="theme_type_serious" <?php if($serious=='1'){?>checked="checked"<?php }?>>
                <?php define('__ROOT__', dirname(dirname(__FILE__)));
                include(__ROOT__.'/admin/images/star.php'); ?>
            </div>
        </div>
        <div class="col-4">
            <label>Sensible</label>
            <div class="radio-item">
                <input class="star-marker" type="checkbox" name="theme_type_sensible" value="sensible" id="theme_type_sensible" <?php if($sensible=='1'){?>checked="checked"<?php }?>>
                <?php define('__ROOT__', dirname(dirname(__FILE__)));
                include(__ROOT__.'/admin/images/star.php'); ?>
            </div>
        </div>
    </div><!--row-->

</div><!--details-->

<div class="categories">
    <div id="icon_upload" style="float:left;"><label>Upload Icon</label>
    <div id="fundfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="fundcontainer"><a id="pickfund" href="javascript:;" class="button button__raised button__inline">Select File</a></div><input name="icon_file" type="hidden" id="icon_file" value="<?=$theme_icon_file;?>"><div id="theme_icon"><?=$theme_icon;?></div>
	<!--
	<label>Upload Icon</label>
    <input type="text" id="theme_icon" name="theme_file" value="File Name" class="mb1">
    <a href="#" class="button button__raised button__inline">Select File</a>
    -->
</div>

</div><!--cats-->

</div><!--content-->

<div class="control">
    <h3 class="heading heading__2">Theme Actions</h3>
    <p>Last edited on 14 Jan by James Barton</p>
    <input type="submit" class="button button__raised button__inline" value="Save Changes">
    <a href="" class="button button__raised button__inline button__danger">Cancel</a>
</div>

</form>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
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
	url : 'iconupload.php',
	flash_swf_url : 'js/plupload/Moxie.swf',
	silverlight_xap_url : '.js/plupload/Moxie.xap',
	unique_names : true,
	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Image files", extensions : "jpg,jpeg,gif,svg"}
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
			//
		},

        FileUploaded: function(up, file, info) {
            var myData;
				try {
					myData = eval(info.response);
				} catch(err) {
					myData = eval('(' + info.response + ')');
				}

		   $( "#icon_file" ).val(myData.result);
		   $( "#theme_icon" ).html('<img src="../icons_folder/'+myData.result+'" style="margin-right:10px; max-width:80px;">');
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
